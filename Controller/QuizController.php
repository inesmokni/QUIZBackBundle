<?php

namespace QUIZ\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use QUIZ\BackBundle\Entity\Quiz;
use QUIZ\BackBundle\Entity\Bloc; 
use QUIZ\BackBundle\Form\BlocType;
use QUIZ\BackBundle\Entity\Question;
use QUIZ\BackBundle\Entity\Response as QuizResponse;


/**
 * @Route("/quiz")
 * 
 * @author ines mokni
 *
 */

class QuizController extends Controller
{
	
	/**
	 *
	 * @Route("/init", name="init_quiz")
	 */
	public function initAction()
	{
		$em = $this->getDoctrine()->getManager();
		$quiz = new Quiz();
		$quiz->init();
		$em->persist($quiz);
		$em->flush();
		
		return $this->redirect($this->generateUrl('quiz'));
	}
	
	
	/**
	 *
	 * @Route("/", name="quiz")
	 * @Template()
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('QUIZBackBundle:Quiz')->findBy(array(),array('isActive' => 'DESC'));
		
		return array(
				"entities" => $entities,
		);
	}
	
	/**
	 *
	 * @Route("/edit/{quiz}/{category_code}", name="edit_quiz")
	 * @Template()
	 */
	public function editAction(Quiz $quiz, $category_code = null)
	{
		$em = $this->getDoctrine()->getManager();
		$config = $this->container->getParameter("_quiz"); 
        $categories = $config["categories"];
		if(!$category_code){
			$keys = array_keys($categories);
			$category_code = reset($keys);
		}
		$blocs = array();
		foreach ($categories as $key=>$category):
			$aq_blocs = $em->getRepository('QUIZBackBundle:Bloc')->findBy(array("category" =>$key, "quiz" => $quiz));
			$blocs[$key] = $aq_blocs;
		endforeach;
		$allow_edit=null;
		return array(
				"categories" => $categories,
				"blocs" => $blocs,
				"entity" => $quiz,
				"current_category" => $category_code,
				"allow_edit" => $allow_edit == 0 ? true : false
		);
	}
	
	/**
	 *
	 * @Route("/bloc/{bloc}", name="edit_bloc")
	 * @Template()
	 */
	public function editBlocAction(Bloc $bloc)
	{
		$config = $this->container->getParameter("_quiz"); 
        $categories = $config["categories"];
		
		return array(
				"categories" => $categories,
				"entity" => $bloc,
		);
		
	}
	
	
	
	
	/**
	 * Edits an existing Bloc entity.
	 *
	 * @Route("/{quiz}/{category}/{bloc}/edit_init_bloc", name="edit_init_bloc")
	 * @Template()
	 */
	public function editInitBlocAction(Quiz $quiz,$category, Bloc $bloc)
	{
		if (!$quiz) {
			throw $this->createNotFoundException('Unable to find AQ entity.');
		}
		$form = $this->createEditInitBlocForm($quiz,$category,$bloc);
		
		return array(
				"form" =>$form->createView()
		) ;
	
	}
	

	/**
	 * Edits an existing Bloc entity.
	 *
	 * @Route("/{quiz}/{category}/new_bloc", name="new_bloc")
	 * @Template()
	 */
	public function newBlocAction(Quiz $quiz,$category)
	{
		if (!$quiz) {
			throw $this->createNotFoundException('Unable to find AQ entity.');
		}
		$entity = new Bloc();
		$form = $this->createInitBlocForm($quiz,$category,$entity);
		
		return array(
				"form" =>$form->createView()
		) ;
	
	}
	
	/**
	 * Edits an existing Bloc entity.
	 *
	 * @Route("/{quiz}/{category}/create_bloc", name="create_bloc")
	 * @Method("POST")
	 */
	public function createAction(Request $request,Quiz $quiz,$category)
	{
		$em = $this->getDoctrine()->getManager();
		if (!$quiz) {
			throw $this->createNotFoundException('Unable to find AQ entity.');
		}
		
		$entity = new Bloc();
		$form = $this->createInitBlocForm($quiz,$category,$entity);
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			$entity->setCategory($category);
			$entity->setQuiz($quiz);
			
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('msg_success', 'page.update.success');
			return $this->redirect($this->generateUrl('edit_quiz', array("quiz" => $quiz->getId())));
		} else {
			$msg_error = "create.error";
			return $this->redirect($this->generateUrl('edit_quiz', array("quiz" => $quiz->getId())));
		}
	}
	
	/**
	 * Edits an existing Bloc entity.
	 *
	 * @Route("/{aq}/{category}/{id}/update_init_bloc", name="update_init_bloc")
	 * @Method("POST")
	 */
	public function updateInitAction(Request $request,Quiz $quiz,$category, Bloc $bloc)
	{
		$em = $this->getDoctrine()->getManager();
		if (!$quiz) {
			throw $this->createNotFoundException('Unable to find AQ entity.');
		}
	
		if (!$quiz) {
			throw $this->createNotFoundException('Unable to find Page entity.');
		} 
		
		$form = $this->createEditInitBlocForm($quiz,$category,$bloc);
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			$bloc->setCategory($category);
			$bloc->setQuiz($quiz);
			$em->persist($bloc);
			$em->flush();
			$this->get('session')->getFlashBag()->add('msg_success', 'page.update.success');
			return $this->redirect($this->generateUrl('edit_quiz', array("quiz" => $quiz->getId())));
		} else {
			$msg_error = "page.create.error";
			return $this->redirect($this->generateUrl('edit_quiz', array("quiz" => $quiz->getId())));
		}
	}
	
	private function createInitBlocForm($quiz,$category,$entity = null){
		$form = $this->createForm(new BlocType(), $entity, array(
				'action' => $this->generateUrl('create_bloc', array('quiz' => $quiz->getId(), 'category' => $category)),
				'method' => 'POST',
		));
		$form->add('submit', 'submit', array('label' => 'Enregister', 'attr'=> array( 'class' => 'right btn btn-default btn-sm')));
		
		return $form;
	}
	
	private function createEditInitBlocForm($aq,$category,$entity = null){
		$form = $this->createForm(new BlocType(), $entity, array(
				'action' => $this->generateUrl('update_init_bloc', array('aq' => $aq->getId(), 'category' => $category, "id" => $entity->getId())),
				'method' => 'POST',
		));
		$form->add('submit', 'submit', array('label' => 'Enregister', 'attr'=> array( 'class' => 'right btn btn-default btn-sm')));
	
		return $form;
	}
	
	
	/**
	 * Creates a form to delete a Bloc entity by id.
	 *
	 * @param mixed $id The entity id
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()
		->setAction($this->generateUrl('bloc_delete', array('id' => $id)))
		->setMethod('DELETE')
		->add('submit', 'submit', array('label' => 'Delete'))
		->getForm()
		;
	}
	

	/**
	 * Deletes a Page entity.
	 *
	 * @Route("/delete_quiz_bloc/{bloc}", name="aq_bloc_delete")
	 */
	public function deleteBlocAction(Request $request, Bloc $bloc)
	{
			$em = $this->getDoctrine()->getManager();
			if (!$bloc) {
				throw $this->createNotFoundException('Unable to find Bloc entity.');
			}
	
			$aq = $bloc->getQuiz();
			$em->remove($bloc);
			$em->flush();
	
		return $this->redirect($this->generateUrl('edit_quiz', array("quiz" => $aq->getId())));
	}
	

	/**
	 * Deletes a Page entity.
	 *
	 * @Route("/delete_quiz/{id}", name="quiz_delete")
	 */
	public function deleteAQAction(Request $request,Quiz $quiz)
	{
			$em = $this->getDoctrine()->getManager();
	
			if (!$quiz) {
				throw $this->createNotFoundException('Unable to find Auto questionnaire entity.');
			}
	
			$em->remove($quiz);
			$em->flush();
	
		return $this->redirect($this->generateUrl('quiz'));
	}
	
	
	/**
	 *
	 * @Route("/render_reponses/{id}", name="render_reponses")
	 * @Route("/render_reponses/")
	 * @Template()
	 */
	
	public function renderQuestionTreeAction($id = null){
		$result = array();
		$result["success"] = true;
		$result["choices"]=array();
		$em = $this->getDoctrine()->getManager();
		if( $id !== null ){
		$question = $em->getRepository('QUIZBackBundle:Question')->find($id);
		
		if (!$question) {
			$result["success"] = false;
		}
		
		$choices =array();
		foreach ($question->getResponses() as $rep){
			$choices[$rep->getId()] = $rep->getTranslatedContent();
		}
		$result["choices"] = $choices;
		}
		
		return new Response(json_encode($result), 200, array());
	}
	
	
	
	private function cloneQuiz($entity){
		$em = $this->getDoctrine()->getManager();
		$last = $em->getRepository('QUIZBackBundle:Quiz')->findOneby(array(),array('version' => 'DESC'),1);
		$new_entity = new Quiz();
		$new_entity->setVersion($last->getVersion() + 1);
		$new_entity->setIsActive(false);
		$new_entity->setCreatedAt(new \DateTime());
		$new_entity->setUpdatedAt(new \DateTime());
		$em->persist($new_entity);
		$em->flush($new_entity);
		
		
		return $new_entity;
	}
	
	
	private function cloneBloc($bloc, $new_entity){
		$em = $this->getDoctrine()->getManager();
		$new_bloc = new Bloc();
		$new_bloc->setQuiz($new_entity);
		$new_bloc->setCategory($bloc->getCategory());
		$new_bloc->setTitle($bloc->getTitle());
		$new_bloc->setOrder($bloc->getOrder());
			
			
		$em->persist($new_bloc);
		$em->flush($new_bloc);
		
		return  $new_bloc;
		
	}
	
	
	private function cloneQuestion($question, $new_bloc){
		$em = $this->getDoctrine()->getManager();
		$new_question = new Question();
		$new_question->setBloc($new_bloc);
		$new_question->setContent($question->getTranslatedContent());
		$new_question->setType($question->getType());
		$new_question->setOrder($question->getOrder());
		
		foreach ($question->getTranslations() as $trans){
			$new_translation = clone $trans;
			$new_translation->setTranslatable($new_question);
			$em->persist($new_translation);
		}
		
		$em->persist($new_question);
		$em->flush($new_question);
		
		
		return $new_question;
		
	}
	
	
	
	private function cloneAQResponse($response, $new_question){
		$em = $this->getDoctrine()->getManager();
		$new_response = new QuizResponse();
		$new_response->setQuestion($new_question);
		$new_response->setContent($response->getTranslatedContent());
		$new_response->setHasText($response->getHasText());
		$new_response->setOrder($response->getOrder());
			
		foreach ($response->getTranslations() as $trans){
			$new_translation = clone $trans;
			$new_translation->setTranslatable($new_response);
			$em->persist($new_translation);
		}
			
		$em->persist($new_response);
		$em->flush($new_response);
		
		return $new_response;
	}
	
	/**
	 *
	 * @Route("/duplicate_quiz/{id}", name="duplicate_quiz")
	 * @Template()
	 */
	
	public function dupliacateAqAction($id){
		$result =array();
		$result["success"] = true;
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('QUIZBackBundle:Quiz')->findOneById($id);
		
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Page entity.');
		}
		

		$question_map = array();
		$response_map = array();

		$new_entity = $this->cloneQuiz($entity);
		foreach ($entity->getBloc() as $bloc){
			$new_bloc = $this->cloneBloc($bloc,$new_entity);
			foreach ($bloc->getQuestions() as $question){
				$new_question = $this->cloneQuestion($question, $new_bloc);
				$question_map[$question->getId()]["source"]= $question;
				$question_map[$question->getId()]["destination"]= $new_question;
				foreach ($question->getResponses() as $response){
					$new_response = $this->cloneAQResponse($response,$new_question );
					$response_map[$response->getId()] = $new_response;
				}
		}
		}
		
		try {
		foreach ($question_map as $key => $question){
				foreach ($question["source"]->getParentResponses() as $parent_resp){
					$question["destination"]->addParentResponse($response_map[$parent_resp->getId()]);
				}
			$em->persist($question["destination"]);
		}
			
		$em->flush();
		
		} catch (\Doctrine\DBAL\DBALException $e) {
			$result["success"] = false;
		}
		
		$result["url"] = $this->generateUrl('edit_quiz', array("quiz" => $new_entity->getId()));
		
		return new Response(json_encode($result), 200, array());
		
// 		return $this->redirect($this->generateUrl('edit_quiz', array("id" => $new_entity->getId())));
	}
	
	/**
	 *
	 * @Route("/activate_quiz/{id}", name="activate_quiz")
	 * @Template()
	 */
	
	public function activateAqAction($id){
		$result =array();
		$result["success"] = true;
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('QUIZBackBundle:Quiz')->findOneById($id);
	
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Page entity.');
		}
		
		$active = $em->getRepository('QUIZBackBundle:Quiz')->findOneby(array("isActive" => true));
		$active->setIsActive(false);
		$entity->setIsActive(true);
		
		try {
			$em->persist($entity);
			$em->persist($active);
			$em->flush();
			
		} catch (\Doctrine\DBAL\DBALException $e) {
				$this->get('session')->getFlashBag()->add('msg_error', $this->get('translator')->trans("Une erreur s'est produite. Veuillez verifier vos données"));
				$result["success"] = false;
		}
		
		
		$result["url"] = $this->generateUrl('quiz');
		return new Response(json_encode($result), 200, array());
	
	
	}
	
}