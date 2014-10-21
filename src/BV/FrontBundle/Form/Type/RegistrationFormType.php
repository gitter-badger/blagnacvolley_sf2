<?php

namespace BV\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('gender', 'choice', array(
                'empty_value' => 'Choisissez une valeur',
                'required' => true,
                'choices' => array('m' => 'Masculin', 'f' => 'Féminin')
            ))
            ->add('firstname')
            ->add('lastname')
            ->add('level', 'choice', array(
                'empty_value' => 'Choisissez une valeur',
                'required' => true,
                'choices' => array(
                    'exc_a' => 'Excellence A',
                    'exc_b' => 'Excellence B',
                    'exc_c' => 'Excellence C',
                    'hon_a' => 'Honneur A',
                    'hon_b' => 'Honneur B',
                    'hon_c' => 'Honneur C',
                    'prom_a' => 'Promotion A',
                    'prom_b' => 'Promotion B',
                    'prom_c' => 'Promotion C',
                )
            ))
            ->add('picture')
        ;
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'bv_user_registration';
    }
}
