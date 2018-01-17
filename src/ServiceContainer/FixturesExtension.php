<?php

/*
 * This file is part of the Behat Fixtures Extension.
 *
 * Copyright (c) 2018 Mateusz Kołecki <kolecki.mateusz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MKolecki\Behat\FixturesExtension\ServiceContainer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Behat\Definition\DefinitionRepository;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Mateusz Kołecki <kolecki.mateusz@gmail.com>
 */
class FixturesExtension implements ExtensionInterface
{
    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'MKoleckiFixturesLoader';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('delimiter')
                    ->info('Fixtures path delimiter')
                    ->defaultValue('/')
                ->end()
                ->arrayNode('fixtures')
                    ->info('paths to *.yaml files with fixtures')
                    ->performNoDeepMerging()
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        // setup parameters
        $container->setParameter('mkolecki.behat_fixtures_ext.fixtures_config', $config);
        $container->setParameter('mkolecki.behat_fixtures_ext.fixtures_config.delimiter', $config['delimiter']);

        // load basic configuration
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load('config/services.yaml');

        // resolve tagged dependencies for fixtures loader
        $container->getDefinition('mkolecki.behat_fixtures_ext.fixtures_loader')
            ->addArgument(
                $this->findTaggedServices($container, 'mkolecki.behat_fixtures_ext.parser')
            );
    }

    /**
     * @param ContainerBuilder $container
     * @param string  $tag
     *
     * @return array
     */
    private function findTaggedServices(ContainerBuilder $container, $tag)
    {
        $parsersIdentifiers = array_keys($container->findTaggedServiceIds($tag));
        return array_map(array($container, 'get'), $parsersIdentifiers);
    }
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }
}
