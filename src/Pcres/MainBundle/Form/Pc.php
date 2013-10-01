<?php


namespace Pcres\MainBundle\Form;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Pc extends AbstractType
{
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ip', 'text', array(
            'required'  => true,
            'label'  => 'IP',
        ));

        $builder->add('name', 'text', array(
            'required'  => true,
            'label'  => 'PC Name',
        ));

        $builder->add('area', 'choice', array(
            'required'  => true,
            'label'  => 'PC Area',
            'choices'   => $this->getAllPcAreas()

        ));


    }

    public function getName()
    {
        return 'search';
    }

    public function getAllPcAreas()
    {
        $choices = array();
        $areas = $this->doctrine->getRepository('PcresMainBundle:PcArea')->findAll();

        foreach ($areas as $area) {
            $choices[$area->getId()] = $area->getName();
        }

        return $choices;
    }

}