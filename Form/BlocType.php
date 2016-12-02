<?php

namespace QUIZ\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use mHQ\AdminBundle\Form\MenuType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BlocType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    	
    	
        $builder
        		->add("title", TextType::class, array("required" => true, "attr" =>array("class" => "form-control")))
        		->add("order", IntegerType::class, array("required" => true, "attr" =>array("class" => "form-control", "min" => 0, "step" => 1)))
        ; 
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    			 'data_class' => 'QUIZ\BackBundle\Entity\Bloc'
    	));
    }
    
    public function getName() {
    	return $this->getBlockPrefix();
    }
    
    public function getBlockPrefix()
    {
    	return '_bloc';
    }

}
