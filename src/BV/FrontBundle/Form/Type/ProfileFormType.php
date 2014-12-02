<?php

namespace BV\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('current_password')
            ->add('certifFile', 'file', array('label'=>'Certificat médical',))
            ->add('attestationFile', 'file', array('label'=>'Attestation pôle emploi','required' => false
            ))
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
