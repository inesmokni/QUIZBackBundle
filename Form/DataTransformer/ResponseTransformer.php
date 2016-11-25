<?php

namespace quiz\BackBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * TranslatableTransformer
 *
 */
class ResponseTransformer implements DataTransformerInterface {

    private $em;
    private $entity;
    private $property;
    private $container;

    public function __construct($em) {
        $this->em = $em;
    }

    // transforms the Issue object to an array
    public function transform($val) {
    	
        if (!$val) {
            return array();
        }
        $array = array();
        foreach ($val as $resp){
        	$array[] = $resp->getId();
        }

        return implode(",",$array);
        
    }

    // transforms the entity id into an actual entity
    public function reverseTransform($val) {
   	        
        if (!$val) {
            return array();
        }
        
        $val = $this->em->getRepository('quizBackBundle:Response')->findById(explode(",", $val));
        return $val;
    }


}

?>
