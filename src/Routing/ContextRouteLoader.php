<?php

declare(strict_types=1);

namespace Framework\DomainDrivenBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\RouteCollection;

final class ContextRouteLoader extends Loader
{
    private const TYPE = 'domain_driven.context';

    /** @var string */
    private $contextDir;

    /** @var string */
    private $configDir;

    public function __construct(string $contextDir, string $configDir)
    {
        $this->contextDir = $contextDir;
        $this->configDir = $configDir;
    }

    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        $routesConfig = (new Finder())
            ->in($this->contextDir . '/*/' . $this->configDir)
            ->files()
            ->name('/^routes\.(ya?ml|xml|php)$/');

        foreach ($routesConfig->getIterator() as $routeConfig) {
            $this->import($routeConfig);
        }

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return $type === self::TYPE;
    }
}
