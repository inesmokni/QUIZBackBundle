<?php

namespace QUIZ\BackBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use QUIZ\BackBundle\Form\DataTransformer\ResponseTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * 
 * @author ines mokni
 *
 */
class ParentType extends AbstractType {

    private $container;
    private $choices;

    public function __construct($em) {
    	$this->em = $em;
    }
    

    public function buildForm(FormBuilderInterface $builder, array $options) {
    	$transformer = new ResponseTransformer($this->em);
    	$builder->addModelTransformer($transformer);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    	));
    }
    

    public function getParent() {
    	return HiddenType::class;
    }
    
    public function getName() {
        return '_parent_type';
    }

}

?>
