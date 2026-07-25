<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CardController;
use App\Models\Card;
use App\Models\CardService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ServicePixController extends Controller
{
    public function __construct(private QrCodeService $qr) {}

    /**
     * Retorna payload PIX + QR SVG para uso no modal (fetch assíncrono).
     */
    public function payload(Card $card, CardService $service): JsonResponse
    {
        abort_unless($card->is_active && $service->card_id === $card->id && $service->is_active, 404);
        abort_unless($card->pix_key, 422, 'Titular sem chave PIX cadastrada.');

        $city = 'Brasil';
        if ($card->address) {
            $parts = explode(',', $card->address);
            $city  = trim($parts[0]);
        }

        $payload = $this->qr->pixPayload(
            pixKey:       $card->pix_key,
            amount:       (float) $service->price,
            merchantName: $card->display_name,
            city:         $city,
            txid:         'SRV' . $service->id,
        );

        return response()->json([
            'payload'   => $payload,
            'qr_svg'    => $this->qr->svg($payload, 220),
            'formatted' => $service->formatted_price,
            'name'      => $service->name,
        ]);
    }

    /**
     * Link direto de pagamento: /u/{slug}/pagar/{service}
     * Reutiliza card.show com $autoOpenService para abrir o modal automaticamente.
     */
    public function show(string $slug, CardService $service): View
    {
        $card = Card::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        abort_unless($service->card_id === $card->id && $service->is_active, 404);

        $card->load(['links', 'photos', 'schedule', 'services', 'user']);

        // Registra view (mesmo fluxo do CardController::show)
        $referer = request()->header('referer', '');
        $card->views()->create([
            'ip_hash'    => hash('sha256', request()->ip() . config('app.key')),
            'user_agent' => request()->userAgent(),
            'referer'    => $referer,
            'source'     => CardController::detectSource($referer),
        ]);

        return view('card.show', [
            'card'            => $card,
            'qrSvg'           => $this->qr->svg(url("/u/{$card->slug}")),
            'autoOpenService' => $service->id,
        ]);
    }

}
