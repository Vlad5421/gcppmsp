<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtensions extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('uploaded_asset', [AppUploadedAsset::class, 'uploaded_asset']),
            new TwigFunction('get_filials', [AppGetEntityList::class, 'get_filials']),
            new TwigFunction('get_pages', [AppGetPagesList::class, 'getPages']),
            new TwigFunction("zero_adding", [AppZeroAdder::class, 'zero_adding']),
            new TwigFunction("normaling_time", [AppZeroAdder::class, 'normaling_time']),
            new TwigFunction("vdump", [AppDumper::class, 'vdump']),
        ];
    }

}
