<?php

namespace QUIZ\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use QUIZ\BackBundle\Form\Type\ParentViewType;
use QUIZ\BackBundle\Form\Type\ParentType;
use QUIZ\BackBundle\Form\Type\ResponseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use QUIZ\BackBundle\Form\Type\QuestionType;

/**
 * 
 * @author ines mokni
 *
 */
class QuizQuestionType extends AbstractType {
	
	private $config;

    public function __construct($container) {
        $this->em = $container->get('doctrine')->getEntityManager();
        $this->question_has_condition = $container->getParameter("question_has_condition");
        $this->question_has_score = $container->getParameter("question_has_score");
        $this->question_has_help = $container->getParameter("question_has_help");
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $array = array();
        $choices=array();
        
        if( $this->config["question_has_condition"] == true){
        	$questions = array();
        	$bloc = $options["block"];
        	$questions = $this->em->getRepository('QUIZBackBundle:Quiz')->getQuestionByAQAndType($bloc->getQuiz(), array(1, 2, 3));
        	foreach ($questions as $qu)
        		$choices[$qu->getId()] = $qu->getContent();
        	
        	$builder->add('parent_responses_view', CollectionType::class, array("mapped" => false, "label" => "page.parent", "entry_type" => new ParentViewType($this->em, $choices), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'data' => $array))
        	;
        }
        
        if( $this->config["question_has_score"] == true){
        	$builder ->add('isNoted', CheckboxType::class, array('attr' => array(), 'required' => false,  "label" => "page.form.isNoted"));
        }
        if( $this->config["question_has_help"] == true){
        	$builder->add('help', TextareaType::class, array("data" => $builder->getData(), "label" => "page.form.help", 'required' => false))
//          ->add('help', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "wysiwg_type", "label" => "page.form.help", 'required' => false))
        	;
        }
        
        if ($builder->getData()->getId())
            foreach ($builder->getData()->getParentResponses() as $key => $resp) {
                $array[$resp->getQuestion()->getId()]['parent_question'] = $resp->getQuestion()->getId();
                $array[$resp->getQuestion()->getId()]['parent_response'][] = $resp->getId();
            }

        if (!$options["disabled"]) {
            $builder
            		->add('content', TextType::class, array("data" => $builder->getData(), "label" => "page.form.question", "attr" => array("class" => "flag_text")))
//                     ->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "page.form.question"))
                    ->add('order',IntegerType::class, array("label" => "page.form.order", "attr" => array("min" => 0)))
                    ->add('type', QuestionType::class, array("label" => "page.form.type"))
                    ->add('responses', CollectionType::class, array("entry_type" => ResponseType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, "attr" => array("class" => "form-control")))
            ;
        } else {
            $builder
            		->add('content',TextType::class, array("data" => $builder->getData(), "label" => "page.form.question", "attr" => array("disabled" => true, "class" => "flag_text")))
//                     ->add('content', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "flag_text", "label" => "page.form.question", "attr" => array("disabled" => true)))
                    ->add('order', IntegerType::class, array("label" => "page.form.order", "attr" => array("min" => 0)))
                    ->add('type',  QuestionType::class, array("label" => "page.form.type"))
                    ->add('responses', CollectionType::class, array("entry_type" => new ResponseType(true), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, "attr" => array("class" => "form-control")))
//                     ->add('help', 'translatable_text', array("parent_data" => $builder->getData(), "type" => "wysiwg_type", "label" => "page.form.help", 'required' => false))
            ;
        }
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    	$this->configureOptions($resolver);
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array(
    					'data_class' => 'QUIZ\BackBundle\Entity\Question',
        				'block' => null
    			));
    }
    
    
    public function getName() {
    	return $this->getBlockPrefix();
    }
    
    public function getBlockPrefix()
    {
    	return '_quiz_question_type';
    }

}
