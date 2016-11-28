<?php

namespace QUIZ\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use QUIZ\BackBundle\Entity\Question; 
use QUIZ\BackBundle\Form\QuizQuestionType;
use QUIZ\BackBundle\Entity\Bloc;

/**
 * 
 * @author ines mokni
 *
 * @Route("/quiz/question")
 */
class QuestionController extends Controller
{
	
    /**
     * Creates a new Question entity.
     *
     * @Route("/{bloc}", name="quizquestion_create")
     * @Method("POST")
     * @Template("quizBackBundle:Question:new.html.twig")
     */
    public function createAction(Request $request, Bloc $bloc)
    {
    	$em = $this->getDoctrine()->getManager();
        $question = new Question();
        $form = $this->createCreateForm($question, $bloc);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $question->setBloc($bloc);
            $em->persist($question);
            $em->flush();

            return $this->redirect($this->generateUrl('edit_bloc', array('bloc' => $bloc->getId())));
        }

        return array(
            'entity' => $question,
            'form'   => $form->createView(),
        	"categories" => $this->categories,
        );
    }

    /**
     * Creates a form to create a Question entity.
     *
     * @param Question $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Question $entity, $bloc)
    {
        $form = $this->createForm(QuizQuestionType::class, $entity, array(
            'action' => $this->generateUrl('quizquestion_create', array('bloc' => $bloc->getId())),
            'method' => 'POST',
        	'block' => $bloc
        ));

        $form->add('submit', 'submit', array('label' => 'button.add','attr'=> array('class' => 'width-link-add-edit btn btn-default btn-sm right')));

        return $form;
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/{bloc}/new", name="quizquestion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Bloc $bloc)
    {
        $question = new Question();
        
        $form   = $this->createCreateForm($question, $bloc);
        $config = $this->container->getParameter("_quiz"); 
        $categories = $config["categories"];
        
        return array(
            'entity' => $bloc,
            'form'   => $form->createView(),
        	"categories" => $categories,
        );
    }


    /**
a
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{question}/edit", name="question_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Question $question)
    {

        if (!$question) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createEditForm($question);
        $deleteForm = $this->createDeleteForm($question->getId());

        $config = $this->container->getParameter("_quiz"); 
        $categories = $config["categories"];
        
        return array(
            'entity'      => $question,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	"categories" => $categories,
        );
    }
    
    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{question}/show", name="quizquestion_edit_disabled")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Question $question)
    {
    
    	if (!$question) {
    		throw $this->createNotFoundException('Unable to find Question entity.');
    	}
    
    	$editForm = $this->createEditForm($question, true);
    	$deleteForm = $this->createDeleteForm($question->getId());
    
    	$config = $this->container->getParameter("_quiz"); 
        $categories = $config["categories"];
    	
    	return array(
    			'entity'      => $question,
    			'form'   => $editForm->createView(),
    			'delete_form' => $deleteForm->createView(),
    			"categories" => $categories,
    	);
    }
    

    /**
    * Creates a form to edit a Question entity.
    *
    * @param Question $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Question $entity, $disabled = false)
    {
    	$form = $this->createForm(QuizQuestionType::class, $entity, array(
    			'action' => $this->generateUrl('quizquestion_update', array('question' => $entity->getId())),
    			'method' => 'PUT',
    			'block' => $entity->getBloc()
    	));
    	
//         $form = $this->createForm(new QuizQuestionType($em, $entity->getBloc()->getId(),$disabled), $entity, array(
//             'action' => $this->generateUrl('quizquestion_update', array('id' => $entity->getId())),
//             'method' => 'PUT',
//         ));

        $form->add('submit', 'submit', array('label' => 'button.update','attr'=> array('class' => 'width-link-add-edit btn btn-default btn-sm right')));

        return $form;
    }
    
    /**
     * Edits an existing Question entity.
     *
     * @Route("/update/{question}", name="quizquestion_update")
     * @Method("PUT")
     * @Template("mHQAdminBundle:Question:edit.html.twig")
     */
    public function updateAction(Request $request, Question $question)
    {

        $em = $this->getDoctrine()->getManager();


        if (!$question) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($question->getId());
        $editForm = $this->createEditForm($question);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('edit_bloc', array('bloc' => $question->getBloc())));
        }

        return array(
            'entity'      => $question,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        	"categories" => $this->categories,
        );
    }
    /**
     * Deletes a Question entity.
     *
     * @Route("/delete/{id}", name="quizquestion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('quizBackBundle:Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quizquestion'));
    }
    
    /**
     * Deletes a Question entity.
     *
     * @Route("/ajax_delete/{id}", name="quizquestion_delete_ajax")
     */
    public function deleteAjaxAction(Request $request, $id)
    {
    		$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('quizBackBundle:Question')->find($id);
    
    		if (!$entity) {
    			throw $this->createNotFoundException('Unable to find Question entity.');
    		}
    		$bloc= $entity->getBloc()->getId();
    		$em->remove($entity);
    		$em->flush();
    
    	return $this->redirect($this->generateUrl('edit_bloc', array("bloc" => $bloc)));
    }

    /**
     * Creates a form to delete a Question entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quizquestion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
