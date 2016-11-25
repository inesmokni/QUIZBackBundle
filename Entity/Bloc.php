<?php

namespace quiz\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bloc
 *
 * @ORM\Table(name="bloc")
 * @ORM\Entity
 */
class Bloc
{
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
     * @ORM\Column(name="category", type="string", length=9)
     */
    private $category;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="bloc")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    protected $quiz;
    
    

    /**
     *  @ORM\OneToMany(targetEntity="Question", mappedBy="bloc", cascade={"persist", "remove"} )
     *
     */
    private $questions;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;
    
    
	/** 
	 * @ORM\Column(name="`order`", type="integer", nullable=true) 
	 * 
	 **/
    
	private $order;
    


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
    public function setId($id)
    {
    	return $this->id = $id;
    }
    
    public function __clone(){
    	if ($this->id) {
    		$this->setId(null);
    		foreach ($this->questions as $qu){
    			$this->removeQuestion(clone $qu);
    		}
    	}
    }
    /**
     * Set aqCategory
     *
     * @param string $aqCategory
     * @return Bloc
     */
    public function setCategory($aqCategory)
    {
        $this->category = $aqCategory;

        return $this;
    }

    /**
     * Get aqCategory
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set quiz
     *
     * @param \quiz\BackBundle\Entity\Quiz $autoQuestionnaire
     * @return Bloc
     */
    public function setQuiz(\quiz\BackBundle\Entity\Quiz $autoQuestionnaire = null)
    {
        $this->quiz = $autoQuestionnaire;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \quiz\BackBundle\Entity\Quiz 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Add questions
     *
     * @param \quiz\BackBundle\Entity\ $Questions
     * @return Bloc
     */
    public function addQuestion(\quiz\BackBundle\Entity\Question $Questions)
    {
    	$Questions->setQuiz($this);
        $this->questions[] = $Questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \quiz\BackBundle\Entity\ $Questions
     */
    public function removeQuestion(\quiz\BackBundle\Entity\Question $Questions)
    {
        $this->questions->removeElement($Questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
    
    public function getCountQuestion(){
    	return count($this->questions);
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
	
	
	public function setTitle($title)
	{
		$this->title = $title;
	
		return $this;
	}
	
	/**
	 * Get aqCategory
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
}
