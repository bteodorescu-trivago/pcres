<?php


namespace Pcres\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Search extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array(
            'required'  => true,
            'label'  => 'Email'
        ));

        $builder->add('day', 'choice', array(
            'choices'   => array('today' => 'Today', 'tomorrow' => 'Tomorrow'),
            'required'  => true,
            'label'  => 'Day',
        ));

        $builder->add('start_hour', 'choice', array(
            'choices'   => array(   '8' => '08:00',
                                    '9' => '09:00',
                                    '10' => '10:00',
                                    '11' => '11:00',
                                    '12' => '12:00',
                                    '13' => '13:00',
                                    '14' => '14:00',
                                    '15' => '15:00',
                                    '16' => '16:00',
                                    '17' => '17:00',
                                    '18' => '18:00'
                                ),
            'required'  => true,
            'label'  => 'Start Hour',
        ));

        $builder->add('end_hour', 'choice', array(
            'choices'   => array(   '8' => '08:00',
                                    '9' => '09:00',
                                    '10' => '10:00',
                                    '11' => '11:00',
                                    '12' => '12:00',
                                    '13' => '13:00',
                                    '14' => '14:00',
                                    '15' => '15:00',
                                    '16' => '16:00',
                                    '17' => '17:00',
                                    '18' => '18:00'
                                ),
            'required'  => true,
            'label'  => 'End Hours',
        ));

        $builder->add('pc_area', 'text', array(
            'required'  => false,
            'label'  => 'PC Area',
        ));

        $builder->add('pc_name', 'text', array(
            'required'  => false,
            'label'  => 'PC Name',
        ));

    }

    public function getName()
    {
        return 'search';
    }

}