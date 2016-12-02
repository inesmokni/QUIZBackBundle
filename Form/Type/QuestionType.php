<?php

namespace QUIZ\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates  QuestionType field
 * @author ines mokni
 *
 */
class QuestionType extends AbstractType {

    private $container;
    private $choices;

    public function __construct($container) {
        $this->container = $container;
        $aq_types = $this->container->getParameter("question_type");
        $this->choices = $aq_types;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    		'choices' => $this->choices,
        	'expanded' => true
    	));
    }

    public function getParent() {
    	return ChoiceType::class;
    }

    
    public function getName() {
    	return $this->getBlockPrefix();
    }
    
    public function getBlockPrefix()
    {
    	return '_question_type';
    }
    
}

?>
