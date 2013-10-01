<?php

namespace Pcres\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pc")
 */
class Pc
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PcArea")
     */
    private $area;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $ip;




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
     * Set name
     *
     * @param string $name
     * @return Pc
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Pc
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set area
     *
     * @param \Pcres\MainBundle\Entity\PcArea $area
     * @return Pc
     */
    public function setArea(\Pcres\MainBundle\Entity\PcArea $area = null)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return \Pcres\MainBundle\Entity\PcArea 
     */
    public function getArea()
    {
        return $this->area;
    }
}