<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    // ── Leitura com cache de 1h ──────────────────────────────────────────────

    public static function get(string $key, mixed $default = null): mixed
    {
        $raw = Cache::remember("app_setting:{$key}", 3600, function () use ($key) {
            return static::where('key', $key)->first();
        });

        if (! $raw) {
            return $default;
        }

        return static::cast($raw->value, $raw->type);
    }

    // ── Escrita (invalida cache) ─────────────────────────────────────────────

    public static function set(string $key, mixed $value): void
    {
        $row = static::where('key', $key)->firstOrNew(['key' => $key]);

        $row->value = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
        $row->save();

        Cache::forget("app_setting:{$key}");
    }

    // ── Cast de tipo ─────────────────────────────────────────────────────────

    private static function cast(mixed $value, string $type): mixed
    {
        return match ($type) {
            'integer' => (int) $value,
            'float'   => (float) $value,
            'boolean' => in_array($value, ['1', 'true', true], true),
            'json'    => json_decode($value, true),
            default   => (string) $value,
        };
    }
}
