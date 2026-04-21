<?php

namespace App\Actions\Event;

use App\Models\EventUser;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class GenerateStudentQrAction
{
    public function execute(EventUser $registration): string
    {
        if (empty($registration->qr_token)) {
            $registration->qr_token = Str::uuid()->toString();
            $registration->save();
        }

        $url = route('checkin.scan', ['token' => $registration->qr_token]);

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd
        );

        return (new Writer($renderer))->writeString($url);
    }
}
