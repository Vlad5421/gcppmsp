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
            new TwigFunction('uploaded_asset', [AppUploadedAsset::class, 'asset']),
            new TwigFunction('get_filials', [AppGetEntityList::class, 'get_filials']),
            new TwigFunction('get_pages', [AppGetPagesList::class, 'getPages']),
        ];
    }

}
