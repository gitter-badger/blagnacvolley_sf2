<?php

namespace BV\FrontBundle\Form\Type;

use BV\FrontBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('current_password')
            ->add('certifFile', 'file', array('label'=>'Certificat médical',))
            ->add('attestationFile', 'file', array('label'=>'Attestation pôle emploi','required' => false))
            ->add('parentalAdvisoryFile', 'file', array('label'=>'Autorisation parentale','required' => false))
        ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'bv_user_profile';
    }
}
