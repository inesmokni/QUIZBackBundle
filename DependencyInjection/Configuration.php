<?php

namespace QUIZ\BackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('quiz_back');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        
        $rootNode
        ->children()
        ->variableNode('categories')->treatNullLike(array())->end()
        ->variableNode('question_type')->defaultValue(array('Texte libre' => 0,  'Radio' => 1 ,'Checkbox' => 2, 'Combobox' => 3))->treatNullLike(array())->end()
        ->booleanNode('extra_response')->defaultFalse()->end()
        ->booleanNode('question_has_score')->defaultFalse()->end()
        ->booleanNode('question_has_condition')->defaultFalse()->end()
        ->booleanNode('question_has_help')->defaultFalse()->end()
        ->end()
        ;
         
        return $treeBuilder;
    }
}
