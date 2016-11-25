<?php

namespace quiz\BackBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResponseType extends AbstractType {

	
	public function __construct($container,  $disabled = false){
		$this->disabled = $disabled;
		$this->config = $container->getParameter("_quiz");
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    	if( $this->disabled)
    		$builder
    		->add('content', 'text', array( "label" => "response.form.response", "attr" => array("disabled" => true, "class" => "flag_text")));
//     		->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "response.form.response", "attr" => array("disabled" => true)));
    		 else 
    		 	$builder
    		 	->add('content', 'text', array( "label" => "response.form.response", "attr"=> array("class" => "flag_text")));
//     		 	->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "response.form.response"));
    		 	 ;
        $builder
         ->add('order','integer', array("label" => "response.form.order","attr" => array("min"=>0 ,"step"=>"1")))
//          ->add('extraQuestion', 'translatable_text', array("required" => false, "parent_data" => $builder->getData(), "type" => "flag_text", "label" => false, "attr" => array("class" => "extra_question")))
        ;
        if( isset($this->config["extra_response"]) && $this->config["extra_response"] ){
        	$builder         
        		->add('has_text', 'checkbox', array("label" => "response.form.has_text", "required" => false))
        		->add('extraQuestion', 'text', array("required" => false, "label" => false, "attr" => array("class" => "extra_question flag_text")))
        	;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'quiz\BackBundle\Entity\Response'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return '_quiz_response_type';
    }

}
