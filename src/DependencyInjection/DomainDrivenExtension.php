<?php

declare(strict_types=1);

namespace DomainDrivenBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class DomainDrivenExtension extends ConfigurableExtension
{
    private const PARAMETER_PREFIX = 'domain_driven.';

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.project_dir'));
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container) : void
    {
        $this->setConfigParameters($mergedConfig, $container);
        $this->loadBundleServices($container);

        $configFiles = (new Finder())
            ->in(
                sprintf(
                    '%s/*/%s',
                    $container->getParameter(self::PARAMETER_PREFIX . 'context_dir'),
                    $container->getParameter(self::PARAMETER_PREFIX . 'config_dir')
                )
            )
            ->files();

        $this->loadContextServices($container, $configFiles->name('/^services\.(ya?ml|xml|php)$/'));
    }

    private function setConfigParameters(array $config, ContainerBuilder $container) : void
    {
        foreach ($config as $name => $value) {
            $container->setParameter(self::PARAMETER_PREFIX . $name, $value);
        }
    }

    private function loadBundleServices(ContainerBuilder $container) : void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yaml');
    }

    private function loadContextServices(ContainerBuilder $container, Finder $finder) : void
    {
        foreach ($finder->getIterator() as $file) {
            $fileLocator = new FileLocator($file->getRealPath());

            $configLoader = new DelegatingLoader(
                new LoaderResolver(
                    [
                        new YamlFileLoader($container, $fileLocator),
                        new XmlFileLoader($container, $fileLocator),
                        new PhpFileLoader($container, $fileLocator),
                    ]
                )
            );

            $configLoader->load($file->getRealPath());
        }
    }
}
