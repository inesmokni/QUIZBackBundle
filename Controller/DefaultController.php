<?php

namespace QUIZ\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/quiz_menu")
     * @Template()
     */
    public function indexAction()
    {
    	$quiz_front = $this->container->getParameter("quiz_front");
        return array(
        	"quiz_front"	 => $quiz_front
        );
    }
}
