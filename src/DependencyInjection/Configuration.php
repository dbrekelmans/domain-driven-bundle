<?php

declare(strict_types=1);

namespace DomainDrivenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('domain_driven');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('directories')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('context')
            ->defaultValue('/src')
                        ->end()
                        ->scalarNode('application')
                            ->defaultValue('Application')
                        ->end()
                        ->scalarNode('domain')
                            ->defaultValue('Domain')
                        ->end()
                        ->scalarNode('infrastructure')
                            ->defaultValue('Infrastructure')
                        ->end()
                        ->scalarNode('presentation')
                            ->defaultValue('Presentation')
                        ->end()
                        ->scalarNode('config')
                            ->defaultValue('config')
                        ->end()
            ->scalarNode('template')
            ->defaultValue('template')
            ->end()
                    ->end()
                ->end()
                ->arrayNode('files')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('routes')
                            ->defaultValue('routes')
                        ->end()
                        ->scalarNode('services')
                            ->defaultValue('services')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
