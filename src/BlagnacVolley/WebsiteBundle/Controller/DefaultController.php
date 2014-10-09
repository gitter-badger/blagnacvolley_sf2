<?php

namespace BlagnacVolley\WebsiteBundle\Controller;

use BlagnacVolley\WebsiteBundle\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlagnacVolleyWebsiteBundle:Default:index.html.twig', array());
    }

    public function staticAction($page)
    {
        $tpl = '    :static:'.$page.'.html.twig';
        if ($this->container->get('templating')->exists($tpl)) {
            $response = $this->render($tpl, array());
        } else {
            throw $this->createNotFoundException('Page not found');
        }
        return $response;
    }

    /**
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(new ContactType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($form->get('subject')->getData())
                    ->setFrom($form->get('email')->getData())
                    ->setTo('contact@example.com')
                    ->setBody(
                        $this->renderView(
                            'BlagnacVolleyWebsiteBundle:Mail:contact.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('name')->getData(),
                                'message' => $form->get('message')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('success', 'Your email has been sent! Thanks!');

                return $this->redirect($this->generateUrl('contact'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
