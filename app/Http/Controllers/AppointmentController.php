<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmedMail;
use App\Mail\AppointmentRefusedMail;
use App\Models\CardAppointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentService $service) {}

    public function confirm(string $token)
    {
        $appointment = CardAppointment::where('token', $token)->firstOrFail();

        if (!$appointment->isTokenValid()) {
            return view('appointments.expired');
        }

        if ($appointment->status !== 'pending') {
            return view('appointments.already-handled', compact('appointment'));
        }

        $this->service->confirm($appointment);

        Mail::to($appointment->visitor_email)->send(new AppointmentConfirmedMail($appointment));

        return view('appointments.confirmed', compact('appointment'));
    }

    public function refuse(string $token)
    {
        $appointment = CardAppointment::where('token', $token)->firstOrFail();

        if (!$appointment->isTokenValid()) {
            return view('appointments.expired');
        }

        if ($appointment->status !== 'pending') {
            return view('appointments.already-handled', compact('appointment'));
        }

        $this->service->refuse($appointment);

        Mail::to($appointment->visitor_email)->send(new AppointmentRefusedMail($appointment));

        return view('appointments.refused', compact('appointment'));
    }

    public function slots(Request $request, string $slug)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d|after_or_equal:today']);

        $card = \App\Models\Card::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $schedule = $card->schedule;
        if (!$schedule || !$schedule->is_active) {
            return response()->json([]);
        }

        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('date'));

        return response()->json($this->service->availableSlots($schedule, $date));
    }
}
