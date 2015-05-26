<?php

namespace BV\FrontBundle\Form\Handler;

use BV\FrontBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use Symfony\Component\DependencyInjection\Container;

class RegistrationFormHandler extends BaseHandler
{
    protected $container;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, Container $container)
    {
        parent::__construct($form, $request, $userManager, $mailer, $tokenGenerator);
        $this->container = $container;
    }

    /**
     * @param UserInterface $user
     * @param bool $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        /* @var $user User */

        if ($confirmation) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
        }

        // Check if dob is valid
        if ($user->getDob() != null && !$user->getDob() instanceof \DateTime)
        {
            $dt = \DateTime::createFromFormat("d/m/Y", $user->getDob());
            $user->setDob($dt);
        }

        // Check if Adress has been entered correctly
        if ($user->getCity() == null || $user->getZip() == null || $user->getGeoLat() == null || $user->getGeoLng() == null)
        {
            throw new Exception('Vous devez sélectionner une adresse valide dans le champ associé');
        }

        $this->userManager->updateUser($user);
    }
}
