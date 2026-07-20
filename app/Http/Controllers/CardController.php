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

        $referer = request()->header('referer', '');
        $card->views()->create([
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer'    => $referer,
            'source'     => self::detectSource($referer),
        ]);

        // QR Code inline: evita requisição extra — funciona offline após 1ª carga
        $qrSvg = $qrService->generateSvg($card);

        return view('card.show', compact('card', 'qrSvg'));
    }

    public function trackClick(string $slug, int $linkId): \Illuminate\Http\RedirectResponse
    {
        $card = Card::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $link = $card->links()->where('id', $linkId)->where('is_active', true)->firstOrFail();
        $link->increment('click_count');
        return redirect()->away($link->url);
    }

    private static function detectSource(string $referer): string
    {
        if (empty($referer)) return 'direct';
        $r = strtolower($referer);
        return match (true) {
            str_contains($r, 'whatsapp') || str_contains($r, 'wa.me')      => 'whatsapp',
            str_contains($r, 'instagram')                                   => 'instagram',
            str_contains($r, 'google')                                      => 'google',
            str_contains($r, 'facebook') || str_contains($r, 'fb.com')     => 'facebook',
            str_contains($r, 'linkedin')                                    => 'linkedin',
            str_contains($r, 'twitter') || str_contains($r, 'x.com')       => 'twitter',
            str_contains($r, 'tiktok')                                      => 'tiktok',
            str_contains($r, 't.me') || str_contains($r, 'telegram')       => 'telegram',
            default                                                          => 'outros',
        };
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
