<?php

namespace Leantime\Core;

use Illuminate\Container\Container;

/**
 * Application Class - IoC Container for the application
 *
 * @package    leantime
 * @subpackage core
 */
class Application extends Container
{
    /**
     * Application bootstrap status
     *
     * @var bool
     */
    private static bool $bootstrapped = false;

    /**
     * Check if application has been bootstrapped
     *
     * @return bool
     */
    public static function hasBeenBootstrapped(): bool
    {
        return self::$bootstrapped;
    }

    /**
     * Set the application as having been bootstrapped
     *
     * @return Application
     */
    public static function setHasBeenBootstrapped(): self
    {
        self::$bootstrapped = true;

        return self::getInstance();
    }

    /**
     * Get the application namespace
     *
     * @param bool $includeSuffix
     * @return string
     *
     * @see \Illuminate\Contracts\Foundation\Application::getNamespace()
     */
    public function getNamespace(bool $includeSuffix = true): string
    {
        static $namespace;

        if (! $namespace) {
            $namespace = substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, '\\') + 1);
        }

        return ! $includeSuffix ? rtrim($namespace, '\\') : $namespace;
    }
}
