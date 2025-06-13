<?php

namespace Magia\Core;

use InvalidArgumentException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use Magia\Config\ExtensionTwig\PurifyExtension;
use Magia\Config\ConfigDotenv;

ConfigDotenv::getInstance();

class ConfigTwig
{
    private static ?self $instance = null;
    private Environment $twig;

    private function __construct()
    {
        $this->initialiseTwig();
    }

    public static function getInstance(): self {
        
        return self::$instance ??= new self();

    }

    private function initialiseTwig(): void {

        $theme = $_ENV['TWIG_THEME'] ?? '';
        $cache = $_ENV['TWIG_CACHE'] ?? false;
        $modeDev = filter_var($_ENV['TWIG_DEV'],FILTER_VALIDATE_BOOL);

        $cheminTheme = RACINE_MAGIA . $theme;
        $cheminCache = $cache ? RACINE_MAGIA . $cache : false;

        if(empty($cheminTheme) || !is_dir($cheminTheme)) {
            throw new InvalidArgumentException("Le chemin du thème n'est pas défini ou est invalide.");
        }

        $loader = new FilesystemLoader($cheminTheme);
        $loader->addPath($cheminTheme . '/public','public');
        $loader->addPath($cheminTheme . '/admin','admin');        

        $this->twig = new Environment($loader, [
            'cache' => $cheminCache,
            'debug' => $modeDev,
            'auto_reload' => $modeDev
        ]);

        $this->twig->addExtension(new PurifyExtension());

        if($modeDev) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension);
        }
    }

    public function getTwig(): Environment {
        return $this->twig;
    }
}
