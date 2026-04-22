<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppointmentExportController extends Controller
{
    public function csv(Request $request): StreamedResponse
    {
        $user = auth()->user();
        abort_unless($user && ($user->hasRole('super_admin') || $user->business_id), 403);

        $month = $request->query('month', now()->format('Y-m'));

        try {
            $start = Carbon::parse($month.'-01')->startOfMonth();
            $end = $start->copy()->endOfMonth();
        } catch (\Throwable $e) {
            abort(422, 'Mes inválido. Formato esperado: YYYY-MM');
        }

        $query = Appointment::with(['service', 'employee', 'customer', 'business'])
            ->whereBetween('starts_at', [$start, $end])
            ->orderBy('starts_at');

        if (! $user->hasRole('super_admin')) {
            $query->where('business_id', $user->business_id);
        }

        $filename = 'citas-'.$start->format('Y-m').'.csv';

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // BOM para que Excel abra bien los acentos
            fprintf($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Negocio',
                'Cliente',
                'Teléfono cliente',
                'Email cliente',
                'Servicio',
                'Precio',
                'Profesional',
                'Inicio',
                'Fin',
                'Duración (min)',
                'Estado',
                'Notas',
                'Creada',
            ], ';');

            $query->chunk(500, function ($appointments) use ($handle) {
                foreach ($appointments as $appointment) {
                    fputcsv($handle, [
                        $appointment->id,
                        $appointment->business->name ?? '',
                        $appointment->customer->name ?? '',
                        $appointment->customer->phone ?? '',
                        $appointment->customer->email ?? '',
                        $appointment->service->name ?? '',
                        $appointment->service->price ?? 0,
                        $appointment->employee->name ?? 'Sin asignar',
                        $appointment->starts_at?->format('Y-m-d H:i'),
                        $appointment->ends_at?->format('Y-m-d H:i'),
                        $appointment->service->duration_minutes ?? '',
                        $appointment->status->label(),
                        str_replace(["\n", "\r"], ' ', (string) $appointment->notes),
                        $appointment->created_at?->format('Y-m-d H:i'),
                    ], ';');
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
