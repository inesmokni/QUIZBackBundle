<?php

namespace QUIZ\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * 
 * @author ines mokni
 *
 */
class ParentViewType extends AbstractType {

    private $questions;
    private $em;
    private $choices;
    
	public function __construct($em,$choices, $disabled = false){
		$this->em = $em;
		$this->disabled = $disabled;
		$this->choices = $choices;
	}

    public function buildForm(FormBuilderInterface $builder, array $options) {
    	$builder
    	         ->add('parent_question', 'choice', array("label" => false, 'choices' => $this->choices,'required' => false ,'empty_value' => 'Choisissez une question','attr' => array('class' =>'chzn_a form-control select_quiz_question' )))
    			 ->add('parent_response', 'text')
    	;
    }
    

    	public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
    	parent::buildView($view, $form, $options);
    	$view->parent->vars['disabled'] = $this->disabled ;
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
        		"label" => false,
        		"disabled" => $this->disabled
        ));
    }
    
    public function getName() {
        return '_parent_view_type';
    }

}

?>
