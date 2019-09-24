<?php

declare(strict_types=1);

namespace DomainDrivenBundle\DependencyInjection;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use function implode;
use function range;
use function sprintf;

final class DomainDrivenExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.project_dir'));
    }

    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container) : void
    {
        $this->setConfigParameters($mergedConfig, $container);
        $this->loadBundleServices($container);

        $configFiles = (new Finder())
            ->in(
                sprintf(
                    '%s/*/%s/%s',
                    $container->getParameter('domain_driven.directories.context'),
                    $container->getParameter('domain_driven.directories.infrastructure'),
                    $container->getParameter('domain_driven.directories.config')
                )
            )
            ->files();

        $this->loadContextServices(
            $container,
            $configFiles->name(
                sprintf('/^%s\.(ya?ml|xml|php)$/', $container->getParameter('domain_driven.files.services'))
            )
        );
    }

    /**
     * @param mixed[] $config
     */
    private function setConfigParameters(array $config, ContainerBuilder $container) : void
    {
        $nodes = new RecursiveIteratorIterator(new RecursiveArrayIterator(['domain_driven' => $config]));
        foreach ($nodes as $node) {
            $keys = [];

            foreach (range(0, $nodes->getDepth()) as $depth) {
                $keys[] = $nodes->getSubIterator($depth)->key();
            }

            $container->setParameter(implode('.', $keys), $node);
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
