<?php

namespace App\Actions\Event;

use App\Models\Event;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class GenerateQrAction
{
    public function execute(Event $event): string
    {
        $token = Str::uuid()->toString();

        $event->update(['qr_code_token' => $token]);

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($token);
    }
}
