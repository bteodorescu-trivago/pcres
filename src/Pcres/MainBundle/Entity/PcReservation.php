<?php


namespace Pcres\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pcreservation")
 */
class PcReservation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Pc")
     */
    private $pc;

    /**
    * @ORM\Column(type="datetime")
    */

    protected $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endTime;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;


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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return PcReservation
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    
        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return PcReservation
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    
        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set pc
     *
     * @param \Pcres\MainBundle\Entity\Pc $pc
     * @return PcReservation
     */
    public function setPc(\Pcres\MainBundle\Entity\Pc $pc = null)
    {
        $this->pc = $pc;
    
        return $this;
    }

    /**
     * Get pc
     *
     * @return \Pcres\MainBundle\Entity\Pc 
     */
    public function getPc()
    {
        return $this->pc;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return PcReservation
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}