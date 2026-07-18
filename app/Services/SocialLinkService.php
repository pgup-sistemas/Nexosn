<?php

namespace App\Services;

class SocialLinkService
{
    public function detectIcon(string $url): string
    {
        $url = strtolower($url);
        return match (true) {
            str_contains($url, 'instagram.com')  => 'camera',
            str_contains($url, 'linkedin.com')   => 'briefcase',
            str_contains($url, 'youtube.com')    => 'play-circle',
            str_contains($url, 'tiktok.com')     => 'music',
            str_contains($url, 'twitter.com'),
            str_contains($url, 'x.com')          => 'at-sign',
            str_contains($url, 'facebook.com')   => 'users',
            str_contains($url, 'telegram.me'),
            str_contains($url, 't.me')           => 'send',
            str_contains($url, 'pinterest.com')  => 'pin',
            str_contains($url, 'spotify.com')    => 'music-2',
            str_contains($url, 'wa.me'),
            str_contains($url, 'whatsapp.com')   => 'message-circle',
            str_contains($url, 'github.com')     => 'code-2',
            str_contains($url, 'mailto:')        => 'mail',
            str_contains($url, 'tel:')           => 'phone',
            default                              => 'link',
        };
    }

    public function detectType(string $url): string
    {
        $socialDomains = [
            'instagram.com', 'linkedin.com', 'youtube.com', 'tiktok.com',
            'twitter.com', 'x.com', 'facebook.com', 'telegram.me', 't.me',
            'pinterest.com', 'spotify.com', 'wa.me', 'whatsapp.com', 'github.com',
        ];

        $url = strtolower($url);
        foreach ($socialDomains as $domain) {
            if (str_contains($url, $domain)) return 'social';
        }
        return 'custom';
    }
}
