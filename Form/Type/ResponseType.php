<?php

namespace QUIZ\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ResponseType extends AbstractType {

	
	public function __construct($container,  $disabled = false){
		$this->disabled = $disabled;
		$this->extra_response = $container->getParameter("extra_response");
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    	if( $this->disabled)
    		$builder
    		->add('content', TextType::class, array( "label" => "response.form.response", "attr" => array("disabled" => true, "class" => "flag_text")));
//     		->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "response.form.response", "attr" => array("disabled" => true)));
    		 else 
    		 	$builder
    		 	->add('content',TextType::class, array( "label" => "response.form.response", "attr"=> array("class" => "flag_text")));
//     		 	->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "response.form.response"));
    		 	 ;
        $builder
         ->add('order',IntegerType::class, array("label" => "response.form.order","attr" => array("min"=>0 ,"step"=>"1")))
//          ->add('extraQuestion', 'translatable_text', array("required" => false, "parent_data" => $builder->getData(), "type" => "flag_text", "label" => false, "attr" => array("class" => "extra_question")))
        ;
        if( $this->extra_response ){
        	$builder         
        		->add('has_text', CheckboxType::class, array("label" => "response.form.has_text", "required" => false))
        		->add('extraQuestion', TextType::class, array("required" => false, "label" => false, "attr" => array("class" => "extra_question flag_text")))
        	;
        }
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			 'data_class' => 'QUIZ\BackBundle\Entity\Response'
    	));
    }
    
    public function getName() {
    	return $this->getBlockPrefix();
    }
    
    public function getBlockPrefix()
    {
    	return '_quiz_response_type';
    }

}
