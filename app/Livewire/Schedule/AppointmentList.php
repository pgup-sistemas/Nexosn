<?php

namespace App\Livewire\Schedule;

use App\Mail\AppointmentConfirmedMail;
use App\Mail\AppointmentRefusedMail;
use App\Models\CardAppointment;
use App\Services\AppointmentService;
use App\Services\GoogleCalendarService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

class AppointmentList extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function confirm(int $id): void
    {
        $appointment = $this->getAppointment($id);
        if (!$appointment || !$appointment->isPending()) return;

        app(AppointmentService::class)->confirm($appointment);
        Mail::to($appointment->visitor_email)->send(new AppointmentConfirmedMail($appointment));

        // Cria evento no Google Calendar do titular (se conectado)
        app(GoogleCalendarService::class)->createEvent(auth()->user(), $appointment);

        session()->flash('sucesso', 'Agendamento confirmado! Evento criado no Google Calendar. ✅');
    }

    public function refuse(int $id): void
    {
        $appointment = $this->getAppointment($id);
        if (!$appointment || !$appointment->isPending()) return;

        app(AppointmentService::class)->refuse($appointment);
        Mail::to($appointment->visitor_email)->send(new AppointmentRefusedMail($appointment));

        // Remove evento do Google Calendar se existir
        app(GoogleCalendarService::class)->deleteEvent(auth()->user(), $appointment);

        session()->flash('sucesso', 'Agendamento recusado.');
    }

    private function getAppointment(int $id): ?CardAppointment
    {
        $card = auth()->user()->card;
        return $card?->schedule?->appointments()->find($id);
    }

    public function render()
    {
        $card        = auth()->user()->card;
        $query       = $card?->schedule?->appointments()->getQuery() ?? CardAppointment::whereRaw('0=1');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $appointments = $query->orderByDesc('appointment_date')->paginate(15);

        return view('livewire.schedule.appointment-list', compact('appointments'));
    }
}
