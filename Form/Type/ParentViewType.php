<?php

namespace QUIZ\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
    	         ->add('parent_question', ChoiceType::class, array("label" => false, 'choices' => $this->choices,'required' => false ,'empty_value' => 'Choisissez une question','attr' => array('class' =>'chzn_a form-control select_quiz_question' )))
    			 ->add('parent_response', TextType::class)
    	;
    }
    

    	public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
    	parent::buildView($view, $form, $options);
    	$view->parent->vars['disabled'] = $this->disabled ;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			"label" => false,
    			"disabled" => $this->disabled
    	));
    }
    
    public function getName() {
    	return $this->getBlockPrefix();
    }
    
    public function getBlockPrefix()
    {
    	return '_parent_view_type';
    }

}

?>
