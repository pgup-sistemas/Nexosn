<?php

namespace App\Services;

use App\Models\Card;

class VCardService
{
    public function generate(Card $card): string
    {
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:' . $this->escape($card->display_name),
        ];

        if ($card->title || $card->company) {
            $lines[] = 'TITLE:' . $this->escape($card->title ?? '');
            $lines[] = 'ORG:' . $this->escape($card->company ?? '');
        }

        if ($card->contact_phone) {
            $lines[] = 'TEL;TYPE=CELL:' . preg_replace('/\D/', '', $card->contact_phone);
        }

        if ($card->contact_email) {
            $lines[] = 'EMAIL:' . $card->contact_email;
        }

        if ($card->website) {
            $lines[] = 'URL:' . $card->website;
        }

        if ($card->address) {
            $lines[] = 'ADR;TYPE=WORK:;;' . $this->escape($card->address) . ';;;;';
        }

        $lines[] = 'URL:' . url('/u/' . $card->slug);
        $lines[] = 'END:VCARD';

        return implode("\r\n", $lines);
    }

    private function escape(string $value): string
    {
        return str_replace([',', ';', '\\', "\n"], ['\\,', '\\;', '\\\\', '\\n'], $value);
    }
}
