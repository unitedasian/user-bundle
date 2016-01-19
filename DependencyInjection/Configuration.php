<?php

namespace UAM\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('uam_user');

        $this->addProfileSection($rootNode);
        $this->addRegistrationSection($rootNode);
        $this->addLoginSection($rootNode);
        $this->addRegistrationSection($rootNode);

        return $treeBuilder;
    }

    private function addProfileSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('home_route')
                    ->defaultValue('home')
                ->end()
                ->arrayNode('profile')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('class')
                        ->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('uam_user_profile')->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('UserProfile'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addRegistrationSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('registration')
                    ->canBeDisabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Register', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addLoginSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('login')
                    ->children()
                        ->arrayNode('facebook')
                            ->canBeEnabled()
                            ->end()
                    ->end()
                ->end()
            ->end();
    }
}
