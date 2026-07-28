<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    public function __construct(private GoogleCalendarService $google) {}

    public function redirect(): RedirectResponse
    {
        return redirect($this->google->getAuthUrl());
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->route('dashboard.settings')
                ->with('erro', 'Conexão com Google cancelada.');
        }

        $code  = $request->input('code');
        $token = $this->google->exchangeCode($code);

        if (isset($token['error'])) {
            return redirect()->route('dashboard.settings')
                ->with('erro', 'Erro ao conectar com Google: ' . ($token['error_description'] ?? $token['error']));
        }

        auth()->user()->update(['google_calendar_token' => $token]);

        return redirect()->route('dashboard.settings')
            ->with('sucesso', 'Google Calendar conectado com sucesso! 🎉');
    }

    public function disconnect(): RedirectResponse
    {
        $this->google->disconnect(auth()->user());

        return redirect()->route('dashboard.settings')
            ->with('sucesso', 'Google Calendar desconectado.');
    }
}
