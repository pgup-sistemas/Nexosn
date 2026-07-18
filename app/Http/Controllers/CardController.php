<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Services\VCardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    public function show(string $slug)
    {
        $card = Card::with(['user', 'links' => fn ($q) => $q->where('is_active', true), 'photos'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Registra visualização de forma assíncrona (sem bloquear a resposta)
        $card->views()->create([
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer'    => request()->header('referer'),
        ]);

        return view('card.show', compact('card'));
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
}
