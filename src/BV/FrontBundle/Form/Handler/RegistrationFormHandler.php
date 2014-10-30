<?php

namespace BV\FrontBundle\Form\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
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
        if ($confirmation) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
        }

        if (null !== $user->pictureFile) {
            $picturesPath = $this->container->getParameter('front.profile.pictures_path');
            $uploadDir = $this->container->getParameter('front.web_dir').$picturesPath;

            $filename = $user->getUsernameCanonical().'.'.$user->pictureFile->guessExtension();

            $user->pictureFile->move($uploadDir, $filename);
            $user->setPicture($picturesPath.'/'.$filename);
            $user->pictureFile = null;
        }

        $this->userManager->updateUser($user);
    }
}
