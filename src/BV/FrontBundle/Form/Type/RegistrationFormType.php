<?php

namespace BV\FrontBundle\Form\Type;

use BV\FrontBundle\Form\Type\DatepickerType;
use BV\FrontBundle\Form\Type\AutocompleteType;
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
                'label'=>'Genre',
                'choices' => array('m' => 'Masculin', 'f' => 'Féminin')
            ))
            ->add('firstname', 'text', array(
                'label'=>'Prénom',
            ))
            ->add('lastname', 'text', array(
                'label'=>'Nom',
            ))
            ->add('level', 'choice', array(
                'empty_value' => 'Choisissez une valeur',
                'required' => true,
                'label'=>'Niveau de jeu',
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
            ->add('dob', 'birthday', array(
                'label'=>'Date de naissance',
            ))
            ->add('address', 'autocomplete', array(
                'label'=>'Adresse',
            ))
            ->add("geo_lat","hidden")
            ->add("geo_lng","hidden")
            ->add('phone', 'text', array(
                'label'=>'Portable',
            ))
            ->add('picture', 'file', array(
                'label'=>'Photo',
            ))
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
