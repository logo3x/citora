<?php

namespace App\Filament\Pages;

use App\Enums\AppointmentStatus;
use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

class TestNotifications extends Page
{
    protected string $view = 'filament.pages.test-notifications';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $navigationLabel = 'Probar notificaciones';

    protected static ?string $title = 'Probar mis notificaciones';

    protected static ?int $navigationSort = 95;

    protected static ?string $slug = 'test-notificaciones';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && ($user->hasRole('business_owner') || $user->hasRole('super_admin'));
    }

    public function getHeading(): string
    {
        return 'Probar mis notificaciones';
    }

    public function getSubheading(): ?string
    {
        return 'Mira cómo le llegan los correos a tus clientes y a tu equipo. Envíate uno a ti para confirmarlo en tu bandeja real.';
    }

    /**
     * @return array<int, array{event: string, role: string, label: string, description: string, color: string}>
     */
    public function getNotificationTypes(): array
    {
        return [
            [
                'event' => AppointmentStatusMail::EVENT_CREATED,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '✅ Cita confirmada — Cliente',
                'description' => 'Lo que recibe el cliente cuando reserva.',
                'color' => 'success',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_CREATED,
                'role' => AppointmentStatusMail::ROLE_EMPLOYEE,
                'label' => '🔔 Nueva cita — Empleado',
                'description' => 'Lo que recibe el profesional cuando le asignan una cita.',
                'color' => 'info',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_CREATED,
                'role' => AppointmentStatusMail::ROLE_OWNER,
                'label' => '🔔 Nueva cita — Dueño',
                'description' => 'Lo que tú recibes (correo del negocio) cuando entra una cita.',
                'color' => 'info',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_REMINDER_24H,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '⏰ Recordatorio 24h — Cliente',
                'description' => 'Se envía el día anterior a la cita.',
                'color' => 'warning',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_REMINDER_1H,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '⏰ Recordatorio 1h — Cliente',
                'description' => 'Una hora antes de la cita.',
                'color' => 'warning',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_CANCELLED,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '❌ Cita cancelada — Cliente',
                'description' => 'Cuando la cita se cancela (cliente o negocio).',
                'color' => 'danger',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_RESCHEDULED,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '🔄 Cita reprogramada — Cliente',
                'description' => 'Cuando la fecha/hora cambia.',
                'color' => 'info',
            ],
            [
                'event' => AppointmentStatusMail::EVENT_COMPLETED,
                'role' => AppointmentStatusMail::ROLE_CUSTOMER,
                'label' => '🎉 Cita completada — Cliente',
                'description' => 'Después de atender al cliente. Invita a volver.',
                'color' => 'success',
            ],
        ];
    }

    public function sendTest(string $event, string $role): void
    {
        $user = auth()->user();
        $appointment = $this->demoAppointment();

        try {
            Mail::to($user->email)->send(new AppointmentStatusMail(
                $appointment,
                $event,
                $role,
                $this->extraDataFor($event),
            ));

            Notification::make()
                ->success()
                ->title('Test enviado')
                ->body("Revisa tu bandeja en {$user->email} (y la carpeta de spam por si acaso).")
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title('No se pudo enviar')
                ->body($e->getMessage())
                ->duration(8000)
                ->send();
        }
    }

    /**
     * Build a realistic Appointment instance for previews. Tries to reuse the
     * latest real appointment of the business so the user sees their own data;
     * falls back to a constructed (non-persisted) demo if there is nothing yet.
     */
    public function demoAppointment(): Appointment
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        if ($businessId) {
            $real = Appointment::with(['service', 'employee', 'customer', 'business'])
                ->where('business_id', $businessId)
                ->latest()
                ->first();

            if ($real) {
                return $real;
            }
        }

        $business = $businessId
            ? Business::find($businessId)
            : Business::query()->first();

        $service = new Service([
            'name' => 'Corte clásico',
            'price' => 25000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);
        $service->id = 0;
        $service->business_id = $businessId;
        $service->exists = true;

        $employee = new Employee([
            'name' => 'Carlos',
            'position' => 'Barbero',
            'phone' => '3001234567',
        ]);
        $employee->id = 0;
        $employee->business_id = $businessId;
        $employee->exists = true;

        $customer = new User([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '3001234567',
        ]);
        $customer->id = 0;
        $customer->exists = true;

        $appointment = new Appointment([
            'service_id' => 0,
            'employee_id' => 0,
            'customer_id' => 0,
            'starts_at' => Carbon::tomorrow()->setTime(15, 0),
            'ends_at' => Carbon::tomorrow()->setTime(15, 30),
            'status' => AppointmentStatus::Confirmed,
            'notes' => 'Esta es una cita de muestra para previsualizar el correo.',
        ]);
        $appointment->id = 0;
        $appointment->business_id = $businessId;
        $appointment->exists = true;

        $appointment->setRelation('business', $business);
        $appointment->setRelation('service', $service);
        $appointment->setRelation('employee', $employee);
        $appointment->setRelation('customer', $customer);

        return $appointment;
    }

    /**
     * @return array<string, mixed>
     */
    public function extraDataFor(string $event): array
    {
        return match ($event) {
            AppointmentStatusMail::EVENT_RESCHEDULED => [
                'old_date' => 'lunes 12 de mayo',
                'old_time' => '10:00 AM',
                'changed_by' => 'Reprogramada por el negocio',
                'share_link' => url('/c/abc12345'),
            ],
            AppointmentStatusMail::EVENT_CANCELLED => [
                'changed_by' => 'Cancelada por el cliente',
                'share_link' => url('/c/abc12345'),
            ],
            AppointmentStatusMail::EVENT_REMINDER_24H,
            AppointmentStatusMail::EVENT_REMINDER_1H => [
                'share_link' => url('/c/abc12345'),
            ],
            default => [
                'share_link' => url('/c/abc12345'),
            ],
        };
    }
}
