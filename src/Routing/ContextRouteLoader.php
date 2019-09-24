<?php

declare(strict_types=1);

namespace DomainDrivenBundle\Routing;

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

    /** @var string */
    private $fileName;

    public function __construct(string $contextDir, string $configDir, string $fileName)
    {
        $this->contextDir = $contextDir;
        $this->configDir = $configDir;
        $this->fileName = $fileName;
    }

    /**
     * @return RouteCollection
     *
     * @inheritDoc
     */
    public function load($resource, $type = null)
    {
        $routeCollection = new RouteCollection();

        $routeResources = (new Finder())
            ->in($this->contextDir . '/*/' . $this->configDir)
            ->files()
            ->name(sprintf('/^%s\.(ya?ml|xml|php)$/', $this->fileName));

        foreach ($routeResources->getIterator() as $routeResource) {
            $routeCollection->addCollection(
                $this->import($routeResource->getRealPath(), $routeResource->getExtension())
            );
        }

        return $routeCollection;
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, $type = null)
    {
        return $type === self::TYPE;
    }
}
