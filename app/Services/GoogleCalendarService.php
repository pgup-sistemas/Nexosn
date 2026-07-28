<?php

namespace App\Services;

use App\Models\CardAppointment;
use App\Models\User;
use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\EventReminder;
use Google\Service\Calendar\EventReminders;

class GoogleCalendarService
{
    private GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->addScope(Calendar::CALENDAR_EVENTS);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function exchangeCode(string $code): array
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        return $token;
    }

    public function isConnected(User $user): bool
    {
        return !empty($user->google_calendar_token);
    }

    public function createEvent(User $user, CardAppointment $appointment): ?string
    {
        if (!$this->isConnected($user)) return null;

        try {
            $this->setUserToken($user);

            $schedule  = $appointment->schedule;
            $duration  = $schedule->slot_duration ?? 60;
            $service   = $schedule->service_name ?: 'Atendimento';

            $start = Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time);
            $end   = $start->copy()->addMinutes($duration);

            $timezone = 'America/Porto_Velho';

            $event = new Event([
                'summary'     => "[NEXOSN] {$service} — {$appointment->visitor_name}",
                'description' => $this->buildDescription($appointment),
            ]);

            $event->setStart(new EventDateTime([
                'dateTime' => $start->toAtomString(),
                'timeZone' => $timezone,
            ]));
            $event->setEnd(new EventDateTime([
                'dateTime' => $end->toAtomString(),
                'timeZone' => $timezone,
            ]));

            // Convidado: o visitante recebe convite por e-mail
            $attendee = new EventAttendee();
            $attendee->setEmail($appointment->visitor_email);
            $attendee->setDisplayName($appointment->visitor_name);
            $event->setAttendees([$attendee]);

            // Lembretes: e-mail 24h antes + notificação 1h antes
            $reminders = new EventReminders();
            $reminders->setUseDefault(false);
            $reminders->setOverrides([
                new EventReminder(['method' => 'email',  'minutes' => 1440]),
                new EventReminder(['method' => 'popup',  'minutes' => 60]),
            ]);
            $event->setReminders($reminders);

            $calendar = new Calendar($this->client);
            $created  = $calendar->events->insert('primary', $event, [
                'sendNotifications' => true,
            ]);

            // Salva o ID do evento para poder deletar depois
            $appointment->update(['google_event_id' => $created->getId()]);

            return $created->getId();

        } catch (\Throwable $e) {
            \Log::warning('GoogleCalendar::createEvent falhou: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteEvent(User $user, CardAppointment $appointment): void
    {
        if (!$this->isConnected($user) || !$appointment->google_event_id) return;

        try {
            $this->setUserToken($user);
            $calendar = new Calendar($this->client);
            $calendar->events->delete('primary', $appointment->google_event_id, [
                'sendNotifications' => true,
            ]);
            $appointment->update(['google_event_id' => null]);
        } catch (\Throwable $e) {
            \Log::warning('GoogleCalendar::deleteEvent falhou: ' . $e->getMessage());
        }
    }

    public function disconnect(User $user): void
    {
        if ($user->google_calendar_token) {
            try {
                $this->client->setAccessToken($user->google_calendar_token);
                $this->client->revokeToken();
            } catch (\Throwable) {}
        }
        $user->update(['google_calendar_token' => null]);
    }

    private function setUserToken(User $user): void
    {
        $token = is_string($user->google_calendar_token)
            ? json_decode($user->google_calendar_token, true)
            : $user->google_calendar_token;

        $this->client->setAccessToken($token);

        // Renova automaticamente se expirado
        if ($this->client->isAccessTokenExpired() && $this->client->getRefreshToken()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken();
            $merged   = array_merge($token, $newToken);
            $user->update(['google_calendar_token' => $merged]);
            $this->client->setAccessToken($merged);
        }
    }

    private function buildDescription(CardAppointment $appointment): string
    {
        $lines = [
            "Visitante: {$appointment->visitor_name}",
            "E-mail: {$appointment->visitor_email}",
        ];
        if ($appointment->visitor_phone) {
            $lines[] = "Telefone: {$appointment->visitor_phone}";
        }
        if ($appointment->notes) {
            $lines[] = "\nObservações:\n{$appointment->notes}";
        }
        $lines[] = "\n— Agendado via NEXOSN";
        return implode("\n", $lines);
    }
}
