<?php

namespace QUIZ\BackBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class QUIZBackExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('quiz.yml');
        $loader->load('services.yml');
        
        $this->remapParametersNamespaces($config, $container, array(
        		'' => array(
        				'extra_response' => 'extra_response',
        				'question_type' => 'question_type',
        				'extra_response' => 'extra_response',
        				'question_has_score' => 'question_has_score',
        				'question_has_condition' => 'question_has_condition',
        				'question_has_help' => 'question_has_help',
        				'categories' => 'categories'
        		),
        ));
    }
    
    
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
    	foreach ($namespaces as $ns => $map) {
    		if ($ns) {
    			if (!array_key_exists($ns, $config)) {
    				continue;
    			}
    			$namespaceConfig = $config[$ns];
    		} else {
    			$namespaceConfig = $config;
    		}
    		if (is_array($map)) {
    			$this->remapParameters($namespaceConfig, $container, $map);
    		} else {
    			foreach ($namespaceConfig as $name => $value) {
    				$container->setParameter(sprintf($map, $name), $value);
    			}
    		}
    	}
    }
    
    
    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
    	foreach ($map as $name => $paramName) {
    		if (array_key_exists($name, $config)) {
    			$container->setParameter($paramName, $config[$name]);
    		}
    	}
    }
    
    public function getXsdValidationBasePath()
    {
    	return __DIR__.'/../Resources/config/';
    } 
    
}
