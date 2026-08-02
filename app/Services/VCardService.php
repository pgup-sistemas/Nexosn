<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Support\Facades\Storage;

class VCardService
{
    public function generate(Card $card): string
    {
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'N:' . $this->buildN($card->display_name),
            'FN:' . $this->escape($card->display_name),
        ];

        if ($card->title || $card->company) {
            $lines[] = 'TITLE:' . $this->escape($card->title ?? '');
            $lines[] = 'ORG:' . $this->escape($card->company ?? '');
        }

        if ($card->contact_phone) {
            // O campo "Celular" é tratado no editor como celular/WhatsApp, então o
            // marcamos com um rótulo X-ABLabel — reconhecido pelo Apple Contacts,
            // ignorado sem erro por Android/Google/Samsung (aparece como celular normal).
            $lines[] = 'item1.TEL;TYPE=CELL:' . $this->cleanPhone($card->contact_phone);
            $lines[] = 'item1.X-ABLabel:WhatsApp';
        }

        if ($card->contact_landline) {
            $lines[] = 'TEL;TYPE=WORK:' . $this->cleanPhone($card->contact_landline);
        }

        if ($card->contact_email) {
            $lines[] = 'EMAIL:' . $this->escape($card->contact_email);
        }

        if ($card->website) {
            $lines[] = 'URL;TYPE=WORK:' . $this->escape($card->website);
        }

        if ($adr = $this->buildAdrLine($card)) {
            $lines[] = $adr;
        }

        if ($card->bio) {
            $lines[] = 'NOTE:' . $this->escape($card->bio);
        }

        if ($photo = $this->buildPhotoLine($card->profile_photo)) {
            $lines[] = $photo;
        }

        $lines[] = 'item2.URL:' . url('/u/' . $card->slug);
        $lines[] = 'item2.X-ABLabel:Cartão Digital';
        $lines[] = 'END:VCARD';

        return implode("\r\n", array_map([$this, 'fold'], $lines));
    }

    /**
     * Dobra linhas com mais de 75 octetos em continuações "\r\n " (RFC 6350 §3.2).
     * Necessário sobretudo para a linha PHOTO em base64, que fica bem longa.
     */
    private function fold(string $line): string
    {
        if (strlen($line) <= 75) {
            return $line;
        }

        $folded = substr($line, 0, 75);
        $rest   = substr($line, 75);

        while ($rest !== '') {
            $chunk   = substr($rest, 0, 74);
            $rest    = substr($rest, 74);
            $folded .= "\r\n " . $chunk;
        }

        return $folded;
    }

    /**
     * Monta ADR;TYPE=WORK:caixa;complemento;rua;cidade;estado;cep;pais.
     * Cartões novos preenchem city/state/zip_code/country separadamente; cartões
     * antigos só têm o campo único "address" — nesse caso ele vai inteiro no
     * componente de rua e os demais componentes ficam vazios (comportamento anterior).
     */
    private function buildAdrLine(Card $card): ?string
    {
        if (! $card->address && ! $card->city && ! $card->state && ! $card->zip_code) {
            return null;
        }

        $street  = $this->escape($card->address ?? '');
        $city    = $this->escape($card->city ?? '');
        $state   = $this->escape($card->state ?? '');
        $zip     = $this->escape($card->zip_code ?? '');
        $country = $this->escape($card->country ?? '');

        return "ADR;TYPE=WORK:;;{$street};{$city};{$state};{$zip};{$country}";
    }

    /**
     * Divide o nome de exibição em N:Sobrenome;Nome;;; — última palavra vira
     * sobrenome, o restante vira nome (heurística simples, sem campos separados no cadastro).
     */
    private function buildN(string $displayName): string
    {
        $parts = preg_split('/\s+/', trim($displayName));

        if (count($parts) < 2) {
            return $this->escape($parts[0] ?? '') . ';;;;';
        }

        $lastName  = array_pop($parts);
        $firstName = implode(' ', $parts);

        return $this->escape($lastName) . ';' . $this->escape($firstName) . ';;;';
    }

    /**
     * Remove tudo que não é dígito, preservando um "+" líder (código do país).
     */
    private function cleanPhone(string $phone): string
    {
        $hasPlus = str_starts_with(trim($phone), '+');
        $digits  = preg_replace('/\D/', '', $phone);

        return $hasPlus ? '+' . $digits : $digits;
    }

    /**
     * Embute a foto de perfil como PHOTO;ENCODING=b;TYPE=JPEG (base64 inline).
     * As fotos já são salvas em JPEG 400×400 pelo ImageService, então não há
     * necessidade de redimensionar novamente aqui.
     */
    private function buildPhotoLine(?string $profilePhotoPath): ?string
    {
        if (! $profilePhotoPath || ! Storage::disk('public')->exists($profilePhotoPath)) {
            return null;
        }

        $base64 = base64_encode(Storage::disk('public')->get($profilePhotoPath));

        return 'PHOTO;ENCODING=b;TYPE=JPEG:' . $base64;
    }

    private function escape(string $value): string
    {
        // Backslash precisa ser escapado primeiro — senão os backslashes
        // introduzidos pelas substituições seguintes seriam escapados de novo.
        return str_replace(['\\', ',', ';', "\n"], ['\\\\', '\\,', '\\;', '\\n'], $value);
    }
}
