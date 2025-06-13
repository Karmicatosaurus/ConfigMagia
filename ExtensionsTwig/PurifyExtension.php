<?php

namespace Magia\Config\ExtensionsTwig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use HTMLPurifier;
use HTMLPurifier_Config;

class PurifyExtension extends AbstractExtension
{
    public function getFilters(): array {
        
        // On crée un nouveau filter Twig pour 'purify'
        return [
            new TwigFilter('purify', [$this, 'purifyHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function purifyHtml(?string $html): string {

        if($html === null) {
            return '';
        }

        // Configuration de base de HTML Purifier
        $config = HTMLPurifier_Config::createDefault();

        $cachePath = __DIR__ . '/../../../var/cache/htmlpurifier';
        $config->set('Cache.SerializerPath', $cachePath);        

        $config->set('HTML.TargetBlank', true);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true]);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}
