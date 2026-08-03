<?php

namespace App\Http\Controllers;

use App\Models\CampaignProposal;
use App\Models\Card;
use App\Models\CardFile;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CardFileController extends Controller
{
    public function download(Card $card, CardFile $file): Response
    {
        abort_unless($card->is_active && $file->card_id === $card->id, 404);
        abort_unless(Storage::disk('local')->exists($file->file_path), 404);

        return $this->streamPdf($file->file_path, $file->label);
    }

    public function proposalPdf(Card $card, CampaignProposal $proposal): Response
    {
        abort_unless($card->is_active && $proposal->card_id === $card->id && $proposal->pdf_path, 404);
        abort_unless(Storage::disk('local')->exists($proposal->pdf_path), 404);

        return $this->streamPdf($proposal->pdf_path, $proposal->title);
    }

    private function streamPdf(string $path, string $label): Response
    {
        $filename = \Illuminate\Support\Str::slug($label) . '.pdf';

        return response(Storage::disk('local')->get($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
