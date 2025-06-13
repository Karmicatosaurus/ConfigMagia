<?php

namespace Magia\Config;

use Dotenv\Dotenv;

class ChargeDotenv
{
    private static ?self $instance = null;

    private function __construct()
    {
        $chemin_env = dirname(__dir__,2);
        $dotenv = dotenv::createImmutable($chemin_env);
        $dotenv->load();
    }

    public static function charge(): self
    {
        return self::$instance ??= new self();
    }
}