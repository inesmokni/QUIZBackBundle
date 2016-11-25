<?php

namespace quiz\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="quiz\BackBundle\Repository\QuizRepository")
 */
class Quiz
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
     * @ORM\Column(name="version", type="string", length=9)
     */
    private $version;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    

    /**
     *  @ORM\OneToMany(targetEntity="Bloc", mappedBy="quiz", cascade={"persist", "remove"} )
     *
     */
    private $bloc;
    
    
    
    public function __clone() {
    	if ($this->id) {
    		$this->setId(null);
    		$this->setIsActive(false);
    		$this->setCreatedAt(new \DateTime());
    		$this->setUpdatedAt(new \DateTime());
    		foreach ($this->bloc as $bloc){
    			$this->removeBloc($bloc);
    		}
    	}
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function setId($id)
    {
    	return $this->id = $id;
    }

    /**
     * Set version
     *
     * @param string $version
     * @return Quiz
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Quiz
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Quiz
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Quiz
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bloc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bloc
     *
     * @param \quiz\BackBundle\Entity\Bloc $bloc
     * @return Quiz
     */
    public function addBloc(\quiz\BackBundle\Entity\Bloc $bloc)
    {
    	$bloc->setQuiz($this);
        $this->bloc[] = $bloc;

        return $this;
    }

    /**
     * Remove bloc
     *
     * @param \quiz\BackBundle\Entity\Bloc $bloc
     */
    public function removeBloc(\quiz\BackBundle\Entity\Bloc $bloc)
    {
        $this->bloc->removeElement($bloc);
    }

    /**
     * Get bloc
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBloc()
    {
        return $this->bloc;
    }
    
    public function setBloc($blocs)
    {
    	return $this->bloc =$blocs;
    }
    
    public function getNbQuestion(){
    	$nb = 0;
    	foreach ($this->bloc as $bloc)
    		$nb+= $bloc->getCountQuestion();
    	
    	return $nb;
    }
    
    public function getNbApplication(){
    	return count($this->applications);
    }
    
    public function init() {
    	$this->setVersion(1);
    	$this->setIsActive(true);
    	$this->setCreatedAt(new \DateTime());
    	$this->setUpdatedAt(new \DateTime());
    }
}
