<?php

namespace BlagnacVolley\WebsiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name', 'text', array(
                'attr' => array(
                    'placeholder' => 'Votre nom',
                    'pattern'     => '.{2,}', //minlength
                ),
                'label'  => 'Nom',
            ))
            ->add('email', 'email', array(
                'attr' => array(
                    'placeholder' => 'Votre email'
                ),
                'label'  => 'Email',
            ))
            ->add('message', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'Votre message'
                ),
                'label'  => 'Message',
            ))
            ->add('captcha', 'genemu_recaptcha', array(
                    'mapped' => false,
                )
            )
            ->add('save', 'submit', array(
                'label'  => 'Envoyer le message',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'Le nom ne doit pas être vide.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'l\'email ne doit pas être vide.')),
                new Email(array('message' => 'Adresse email invalide.'))
            ),
            'message' => array(
                new NotBlank(array('message' => 'Le message ne doit pas être vide.')),
                new Length(array('min' => 5))
            ),
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'contact';
    }
}