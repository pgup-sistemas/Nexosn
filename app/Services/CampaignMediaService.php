<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CampaignMediaService
{
    private const ALLOWED_VIDEO_HOSTS = [
        'youtube.com', 'www.youtube.com', 'youtu.be',
        'vimeo.com', 'www.vimeo.com',
    ];

    /**
     * Aceita apenas embeds do YouTube/Vimeo (oEmbed), nunca upload de arquivo
     * de vídeo bruto — evita custo de storage/transcoding fora do escopo do MVP
     * e reduz superfície de SSRF (ver auditoria §16, risco R4).
     */
    public function assertAllowedVideoUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (! $host || ! in_array(strtolower($host), self::ALLOWED_VIDEO_HOSTS, true)) {
            throw ValidationException::withMessages([
                'video_url' => 'Apenas links do YouTube ou Vimeo são aceitos.',
            ]);
        }

        return $url;
    }

    /** Valida o MIME real do arquivo (não a extensão) antes de aceitar o PDF. */
    public function storePdf(UploadedFile $file, int $cardId): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            throw ValidationException::withMessages([
                'pdf' => 'O arquivo enviado não é um PDF válido.',
            ]);
        }

        $path = "cards/{$cardId}/documents/" . Str::uuid() . '.pdf';
        Storage::disk('local')->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('local')->delete($path);
        }
    }
}
