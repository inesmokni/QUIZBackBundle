<?php

namespace QUIZ\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity
 */
class Question {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_noted", type="boolean", length=1)
     * 
     */
    private $isNoted = false;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=200)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Bloc", inversedBy="questions", cascade={"persist"} )
     * @ORM\JoinColumn(name="bloc_id", referencedColumnName="id")
     */
    protected $bloc;

    /**
     *  @ORM\OneToMany(targetEntity="Response", mappedBy="question", cascade={"persist","remove"} )
     *
     */
    private $responses;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Response", inversedBy="child_questions")
     * @ORM\JoinTable(name="question_response",
     *   joinColumns={
     *     @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $parent_responses;


    /**
     * @var string
     *
     * @ORM\Column(name="help", type="text", nullable = true)
     */
    private $help;

//     /**
//      *  @ORM\OneToMany(targetEntity="Application", mappedBy="question", cascade={"remove"} )
//      *
//      */
//     private $application;

    /**
     * @ORM\Column(name="`order`", type="integer")
     *
     * */
    private $order;

    public function __toString() {
        return $this->getContent() ? $this->getContent() : "";
    }

    public function __clone() {
        if ($this->id) {
            $this->setId(null);
            foreach ($this->responses as $rep) {
                $this->addAqResponse(clone $rep);
            }
            if ($this->getParentResponse())
                $this->setParentResponse(clone $this->getParentResponse());
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


	public function setHelp($help) {
		$this->help = $help;
	}

    /**
     * Get help
     *
     * @return string
     */
	public function getHelp() {
		return $this->help;
	}

    /**
     * Constructor
     */
    public function __construct() {
        $this->responses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set bloc
     *
     * @param  AQBloc $aqBloc
     * @return Question
     */
    public function setBloc( Bloc $aqBloc = null) {
        $this->bloc = $aqBloc;

        return $this;
    }

    /**
     * Get bloc
     *
     * @return  AQBloc 
     */
    public function getBloc() {
        return $this->bloc;
    }

    /**
     * Add responses
     *
     * @param  Response $aqResponses
     * @return Question
     */
    public function addResponse( Response $Response) {
        $Response->setQuestion($this);
        $this->responses[] = $Response;

        return $this;
    }

    /**
     * Remove responses
     *
     * @param  Response $aqResponses
     */
    public function removeResponse( Response $Response) {
        $this->responses->removeElement($Response);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses() {
        return $this->responses;
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setResponses($Responses) {
        return $this->responses = $Responses;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Question
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType() {
        return $this->type;
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
     * Set content
     *
     * @param string $content
     * @return Page
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
     * Add parent_responses
     *
     * @param  Response $ParentResponses
     * @return Question
     */
    public function addParentResponse($ParentResponses) {
        $this->parent_responses[] = $ParentResponses;
        return $this;
    }

    /**
     * Remove parent_responses
     *
     * @param  Response $ParentResponses
     */
    public function removeParentResponse(Response $ParentResponses) {
        $this->parent_responses->removeElement($ParentResponses);
    }

    /**
     * Get parent_responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParentResponses() {
        return $this->parent_responses;
    }
    
    /**
     * Set isNoted
     *
     * @param integer $isNoted
     * @return Question
     */
    public function setIsNoted($isNoted)
    {
        $this->isNoted = $isNoted;

        return $this;
    }

    /**
     * Get isNoted
     *
     * @return integer 
     */
    public function getIsNoted()
    {
        return $this->isNoted;
    }

}
