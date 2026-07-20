<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Foto de perfil: crop quadrado 400×400, ancorando no topo para preservar o rosto.
     */
    public function storeProfile(UploadedFile $file, int $userId): string
    {
        $image = $this->manager->read($file->getRealPath());

        // Corrige orientação EXIF (foto tirada em retrato no celular)
        $image->orient();

        // Crop: recorta um quadrado a partir do topo, depois redimensiona para 400×400
        $w = $image->width();
        $h = $image->height();
        $side = min($w, $h);

        // Centraliza horizontalmente, ancora no topo (rosto fica no topo da maioria das fotos)
        $x = (int) (($w - $side) / 2);
        $y = 0;

        $image->crop($side, $side, $x, $y)
              ->resize(400, 400);

        return $this->saveJpeg($image, $userId, 'profile');
    }

    /**
     * Foto de capa: crop 3:1 (1200×400) centralizado — paisagem panorâmica.
     */
    public function storeCover(UploadedFile $file, int $userId): string
    {
        $image = $this->manager->read($file->getRealPath());

        $image->orient();

        $w = $image->width();
        $h = $image->height();

        // Proporção alvo: 3:1
        $targetW = $w;
        $targetH = (int) ($w / 3);

        if ($targetH > $h) {
            // Foto muito alta (retrato): ajusta pela altura
            $targetH = $h;
            $targetW = $h * 3;
        }

        // Centraliza crop
        $x = (int) (($w - $targetW) / 2);
        $y = (int) (($h - $targetH) / 2);

        $image->crop($targetW, $targetH, $x, $y)
              ->resize(1200, 400);

        return $this->saveJpeg($image, $userId, 'cover');
    }

    /**
     * Fotos da galeria: redimensiona para no máximo 1200px de largura, sem crop forçado.
     */
    public function storePhoto(UploadedFile $file, int $userId, string $folder = 'photos'): string
    {
        $image = $this->manager->read($file->getRealPath());

        $image->orient();

        if ($image->width() > 1200) {
            $image->scale(width: 1200);
        }

        return $this->saveJpeg($image, $userId, $folder);
    }

    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    private function saveJpeg($image, int $userId, string $folder): string
    {
        $filename = Str::uuid() . '.jpg';
        $path     = "cards/{$userId}/{$folder}/{$filename}";

        Storage::disk('public')->put($path, $image->toJpeg(85)->toString());

        return $path;
    }
}
