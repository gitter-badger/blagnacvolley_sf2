<?php

namespace BV\FrontBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use BV\FrontBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class Email
{
    protected $mailer;
    protected $templating;
    protected $container;

    const TYPE_SEND_CERTIF_REFUSED_EMAIL = 'SEND_CERTIF_REFUSED_EMAIL';

    private $from = "contact@blagnac-volley.fr";

    public function __construct($mailer, EngineInterface $templating, EntityManager $doctrine) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->doctrine = $doctrine;
    }

    protected function sendMessage($to, $from, $subject, $body) {
        $message = \Swift_Message::newInstance()
            ->setFrom($from, $from)
            ->setTo($to)
            ->setSubject($subject)
            ->setContentType('text/html')
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * Send message to contact from the contact form
     *
     * @param $name
     * @param $email
     * @param $message
     */
    public function sendContactEmail($name, $email, $message)
    {
        $subject = "[BlagnacVolley] Nouveau message depuis le formulaire de contacts";
        $template = 'FrontBundle:Mail:contact.html.twig';
        $body = $this->templating->render($template, array(
            'name'      => $name,
            'email'     => $email,
            'message'   => $message
        ));

        $this->sendMessage($email, $this->from, $subject, $body);
    }

    /**
     * Send message to contact when a user send a 'my informations have changed' request
     *
     * @param User $user
     * @param $message.
     */
    public function sendInformationsChangedEmail(User $user, $message)
    {
        $subject = "[BlagnacVolley] Les informations d'un utilisateur ont changé";
        $template = 'FrontBundle:Mail:informationsChanged.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
            'message'   => $message
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }

    /**
     * Send message to user when the Admin user validates his license renewal demand
     *
     * @param User $user
     */
    public function sendLicenseRenewalValidated(User $user)
    {
        $subject = "[BlagnacVolley] l'administrateur a validé votre demande de renouvellement de licence";
        $template = 'FrontBundle:Mail:licenseRenewalValidated.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }

    /**
     * Send message to user when the Admin user refuse his license renewal demand
     *
     * @param User $user
     * @param User $message
     */
    public function sendLicenseRenewalRefused(User $user, $message)
    {
        $subject = "[BlagnacVolley] l'admin a refusé votre demande de renouvellement de licence";
        $template = 'FrontBundle:Mail:licenseRenewalRefused.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
            'message'   => $message,
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }

    /**
     * Send message to user when the Admin user deactivate his account
     *
     * @param User $user
     */
    public function sendAccountDeactivated(User $user)
    {
        $subject = "[BlagnacVolley] l'admininistrateur du site vient de désactiver votre compte.";
        $template = 'FrontBundle:Mail:userDeactivated.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }

    /**
     * Send message to user when the Admin user deactivate his account
     *
     * @param User $user
     */
    public function sendAccountReactivated(User $user)
    {
        $subject = "[BlagnacVolley] l'admininistrateur du site vient de réactiver votre compte.";
        $template = 'FrontBundle:Mail:userReactivated.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }


    /**
     * Send message to user when the Admin user launch the "new season" process
     *
     * @param User $user
     */
    public function sendNewSeason(User $user)
    {
        $subject = "[BlagnacVolley] l'admininistrateur du site vient de renouveller la saison.";
        $template = 'FrontBundle:Mail:newSeason.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }

    /**
     * Send message to user when the Admin user creates the new account
     *
     * @param User $user
     */
    public function sendNewAccount(User $user, $plainPassword)
    {
        $subject = "[BlagnacVolley] l'admininistrateur du site vient de vous créer un compte.";
        $template = 'FrontBundle:Mail:newAccount.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
            'plainPassword' => $plainPassword
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }
}