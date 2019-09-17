<?php

declare(strict_types=1);

namespace DomainDrivenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /** @var string */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('domain_driven');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('context_dir')
                    ->defaultValue($this->projectDir . '/src')
                ->end()
                ->scalarNode('config_dir')
                    ->defaultValue('Infrastructure/config')
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
