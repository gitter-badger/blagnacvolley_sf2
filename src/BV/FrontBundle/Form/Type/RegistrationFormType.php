<?php

namespace BV\FrontBundle\Form\Type;

use BV\FrontBundle\Form\Type\DatepickerType;
use BV\FrontBundle\Form\Type\AutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BV\FrontBundle\Entity\User;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder
            ->add('gender', 'sonata_user_gender', array('empty_value' => 'Choisissez une valeur','required' => true,'label'=>'Genre', 'attr' => array('class' => 'form-control')))
            ->add('firstname', 'text', array('label'=>'Prénom',))
            ->add('lastname', 'text', array('label'=>'Nom', ))
            ->add('level', 'bv_user_level', array('empty_value' => 'Choisissez une valeur','required' => false, 'label'=>'Niveau de jeu', 'attr' => array('class' => 'form-control')))
            ->add('dob', 'datePicker', array( 'label'=>'Date de naissance', ))
            ->add('address', 'autocomplete', array( 'label'=>'Adresse',))
            ->add("geo_lat","hidden")
            ->add("geo_lng","hidden")
            ->add('city', 'hidden')
            ->add('zip', 'hidden')
            ->add('phone', 'text', array( 'label'=>'Portable',))
            ->add('phonePro', 'text', array( 'label'=>'Tel Pro.', 'required' => false))
            ->add('pictureFile', 'file', array('label'=>'Photo',))
            ->add('parentalAdvisoryFile', 'file', array('label'=>'Attestation parentale', 'required' => false))
            ->add('isSubscribedInsurance', 'checkbox', array('label'=>'Assurance Optionelle', 'required' => false, 'attr' => ['class' => 'mt10']))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BV\FrontBundle\Entity\User',
        ));
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
