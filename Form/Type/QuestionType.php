<?php

namespace QUIZ\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
        $resolver->setDefaults(array(
        	'choices' => $this->choices,
        	'expanded' => true
        ));
    }

    public function getParent() {
    	return 'choice';
    }
    
    public function getName() {
        return '_question_type';
    }

}

?>
