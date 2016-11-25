<?php

namespace quiz\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use mHQ\AdminBundle\Form\MenuType;

class BlocType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
    	
    	
        $builder
        		->add("title", "text", array("required" => true, "attr" =>array("class" => "form-control")))
        		->add("order", "integer", array("required" => true, "attr" =>array("class" => "form-control", "min" => 0, "step" => 1)))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'quiz\BackBundle\Entity\Bloc'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return '_bloc';
    }

}
