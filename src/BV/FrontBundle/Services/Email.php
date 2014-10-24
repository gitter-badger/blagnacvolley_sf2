<?php

namespace BV\FrontBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use BV\FrontBundle\Entity\User;

class Email
{
    protected $mailer;
    protected $templating;

    private $from = "contact@blagnac-volley.fr";

    public function __construct($mailer, EngineInterface $templating) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    protected function sendMessage($from, $to, $subject, $body) {
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
    public function sendInformationsChangedEmail($user, $message)
    {
        $subject = "[BlagnacVolley] Les informations d'un utilisateur ont changé";
        $template = 'FrontBundle:Mail:informationsChanged.html.twig';
        $body = $this->templating->render($template, array(
            'user'      => $user,
            'message'   => $message
        ));

        $this->sendMessage($user->getEmail(), $this->from, $subject, $body);
    }
}