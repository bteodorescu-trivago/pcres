<?php
namespace Pcres\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PcArea extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'required'  => true,
            'label'  => 'PC Name',
        ));
    }

    public function getName()
    {
        return 'search';
    }
}