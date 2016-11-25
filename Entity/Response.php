<?php

namespace quiz\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AQResponse
 *
 * @ORM\Table(name="response")
 * @ORM\Entity
 */
class Response {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=200)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="aq_responses", cascade={})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $question;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_text", type="boolean", nullable=true)
     */
    private $has_text;

    /**
     * @var string
     *
     * @ORM\Column(name="extra_question", type="string", length=200, nullable=true)
     */
    private $extraQuestion;


    /**
     * @ORM\Column(name="`order`", type="integer")
     *
     * */
    private $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Question", mappedBy="parent_responses")
     */
    private $child_questions;

    public function __toString() {
        return $this->getTranslatedContent();
    }

    public function __clone() {
        if ($this->id) {
            $this->setId(null);
        }
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        return $this->id = $id;
    }

    /**
     * Set question
     *
     * @param  Question $Question
     * @return AQResponse
     */
    public function setQuestion( Question $Question = null) {
        $this->question = $Question;
        return $this;
    }

    /**
     * Get question
     *
     * @return  Question 
     */
    public function getQuestion() {
        return $this->question;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Response
     */
    public function setContent($content) {
            $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
    	return $this->content;
    }
    
    public function getTranslatedContent() {
    	return $this->content;
    }


    /**
     * Set has_text
     *
     * @param boolean $hasText
     * @return AQResponse
     */
    public function setHasText($hasText) {
        $this->has_text = $hasText;

        return $this;
    }

    /**
     * Get has_text
     *
     * @return boolean 
     */
    public function getHasText() {
        return $this->has_text;
    }


    /**
     *
     * @return the unknown_type
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     *
     * @param unknown_type $order
     */
    public function setOrder($order) {
        $this->order = $order;
        return $this;
    }


    /**
     * Set extra_question
     *
     * @param string $content
     * @return Page
     */
    public function setExtraQuestion($content) {
        	$this->extraQuestion = $content;
        return $this;
    }

    /**
     * Get extra_question
     *
     * @return string
     */
    public function getExtraQuestion() {
		return $this->extraQuestion;
    }


    /**
     * Add child_questions
     *
     * @param  Question $ChildQuestions
     * @return AQResponse
     */
    public function addAqChildQuestion( Question $ChildQuestions) {
        $this->child_questions[] = $ChildQuestions;

        return $this;
    }

    /**
     * Remove child_questions
     *
     * @param  Question $ChildQuestions
     */
    public function removeAqChildQuestion( Question $ChildQuestions) {
        $this->child_questions->removeElement($ChildQuestions);
    }

    /**
     * Get child_questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildQuestions() {
        return $this->child_questions;
    }

}
