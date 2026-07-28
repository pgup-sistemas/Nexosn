<?php

namespace App\Livewire\Schedule;

use App\Jobs\SendAppointmentNotification;
use App\Models\Card;
use App\Models\CardAppointment;
use App\Models\CardSchedule;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AppointmentCalendar extends Component
{
    public Card $card;

    public string $currentMonth;
    public ?string $selectedDate = null;
    public array $availableSlots = [];
    public ?string $selectedTime  = null;

    // Formulário
    public string $step = 'calendar'; // calendar | form | success

    #[Validate('required|string|max:100')]
    public string $visitor_name  = '';

    #[Validate('required|email|max:255')]
    public string $visitor_email = '';

    #[Validate('nullable|string|max:20')]
    public string $visitor_phone = '';

    #[Validate('nullable|string|max:500')]
    public string $notes = '';

    public function mount(Card $card): void
    {
        $this->card         = $card;
        $this->currentMonth = now()->format('Y-m');
    }

    public function prevMonth(): void
    {
        $prev = Carbon::parse($this->currentMonth . '-01')->subMonth();
        // Não permite navegar para meses anteriores ao mês atual
        if ($prev->gte(now()->startOfMonth())) {
            $this->currentMonth = $prev->format('Y-m');
            $this->selectedDate = null;
            $this->availableSlots = [];
            $this->selectedTime = null;
        }
    }

    public function nextMonth(): void
    {
        $dt = Carbon::parse($this->currentMonth . '-01')->addMonth();
        if ($dt->lte(now()->addMonths(2))) {
            $this->currentMonth = $dt->format('Y-m');
        }
        $this->selectedDate = null;
        $this->availableSlots = [];
        $this->selectedTime = null;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedTime = null;
        $this->step = 'calendar';

        $schedule = $this->card->schedule;
        if (!$schedule || !$schedule->is_active) {
            $this->availableSlots = [];
            return;
        }

        $this->availableSlots = app(AppointmentService::class)
            ->availableSlots($schedule, Carbon::parse($date));
    }

    public function selectTime(string $time): void
    {
        $this->selectedTime = $time;
        $this->step = 'form';
    }

    public function back(): void
    {
        $this->step = 'calendar';
        $this->selectedTime = null;
    }

    public function submit(): void
    {
        $this->validate();

        $schedule = $this->card->schedule;
        if (!$schedule || !$schedule->is_active) {
            return;
        }

        if (!app(AppointmentService::class)->isSlotAvailable(
            $schedule,
            Carbon::parse($this->selectedDate),
            $this->selectedTime
        )) {
            $this->addError('visitor_name', 'Este horário já foi reservado. Escolha outro.');
            $this->step = 'calendar';
            return;
        }

        $appointment = app(AppointmentService::class)->createRequest($this->card, [
            'visitor_name'     => $this->visitor_name,
            'visitor_email'    => $this->visitor_email,
            'visitor_phone'    => $this->visitor_phone,
            'appointment_date' => $this->selectedDate,
            'appointment_time' => $this->selectedTime,
            'notes'            => $this->notes,
        ]);

        SendAppointmentNotification::dispatch($appointment);

        $this->step = 'success';
        $this->reset('visitor_name', 'visitor_email', 'visitor_phone', 'notes');
    }

    public function getDaysInMonth(): array
    {
        $start   = Carbon::parse($this->currentMonth . '-01');
        $end     = $start->copy()->endOfMonth();
        $today   = now()->startOfDay();

        $schedule = $this->card->schedule;
        $activeWeekdays = [];
        if ($schedule && $schedule->is_active) {
            $activeWeekdays = $schedule->slots->pluck('weekday')->unique()->toArray();
        }

        $days = [];
        $firstDow = $start->dayOfWeek; // 0=Dom
        for ($i = 0; $i < $firstDow; $i++) {
            $days[] = null;
        }

        $current = $start->copy();
        while ($current->lte($end)) {
            $days[] = [
                'date'      => $current->format('Y-m-d'),
                'day'       => $current->day,
                'available' => $current->gte($today) && in_array($current->dayOfWeek, $activeWeekdays),
                'past'      => $current->lt($today),
            ];
            $current->addDay();
        }

        return $days;
    }

    // Agendamentos do mês agrupados por data (pending + confirmed)
    public function getAppointmentsByDay(): array
    {
        $start = Carbon::parse($this->currentMonth . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $scheduleId = $this->card->schedule?->id;
        if (!$scheduleId) return [];

        $appointments = CardAppointment::where('card_schedule_id', $scheduleId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('appointment_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('appointment_time')
            ->get(['appointment_date', 'appointment_time', 'visitor_name', 'status']);

        $grouped = [];
        foreach ($appointments as $appt) {
            $dateKey = $appt->appointment_date instanceof \Carbon\Carbon
                ? $appt->appointment_date->toDateString()
                : (string) $appt->appointment_date;
            $grouped[$dateKey][] = [
                'time'   => substr((string) $appt->appointment_time, 0, 5),
                'name'   => $appt->visitor_name,
                'status' => $appt->status,
            ];
        }
        return $grouped;
    }

    public function render()
    {
        return view('livewire.schedule.appointment-calendar', [
            'days'            => $this->getDaysInMonth(),
            'monthLabel'      => Carbon::parse($this->currentMonth . '-01')->locale('pt_BR')->isoFormat('MMMM [de] YYYY'),
            'appointmentsByDay' => $this->getAppointmentsByDay(),
        ]);
    }
}
