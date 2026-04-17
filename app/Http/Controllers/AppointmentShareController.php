<?php

namespace App\Http\Controllers;

use App\Models\AppointmentShareToken;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppointmentShareController extends Controller
{
    public function show(string $token): View
    {
        $share = AppointmentShareToken::with([
            'appointment.service.media',
            'appointment.employee',
            'appointment.customer',
            'appointment.business.media',
        ])
            ->where('token', $token)
            ->first();

        if (! $share || $share->isExpired()) {
            throw new NotFoundHttpException;
        }

        return view('appointment-share', [
            'appointment' => $share->appointment,
        ]);
    }
}
