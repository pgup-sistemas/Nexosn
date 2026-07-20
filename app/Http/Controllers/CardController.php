<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\QrCodeService;
use App\Services\VCardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    public function show(string $slug, QrCodeService $qrService)
    {
        $card = Card::with(['user', 'links' => fn ($q) => $q->where('is_active', true), 'photos', 'schedule.slots'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Registra visualização de forma assíncrona (sem bloquear a resposta)
        $card->views()->create([
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer'    => request()->header('referer'),
        ]);

        // QR Code inline: evita requisição extra — funciona offline após 1ª carga
        $qrSvg = $qrService->generateSvg($card);

        return view('card.show', compact('card', 'qrSvg'));
    }

    public function vcard(string $slug, VCardService $vcardService): Response
    {
        $card = Card::with('user')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $vcf = $vcardService->generate($card);
        $filename = \Illuminate\Support\Str::slug($card->display_name) . '.vcf';

        return response($vcf, 200, [
            'Content-Type'        => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function qrSvg(string $slug, QrCodeService $qr): Response
    {
        $card = Card::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return response($qr->generateSvg($card), 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function qrPng(string $slug, QrCodeService $qr): Response
    {
        $card = Card::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $filename = $slug . '-qrcode.png';
        return response($qr->generatePng($card), 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
