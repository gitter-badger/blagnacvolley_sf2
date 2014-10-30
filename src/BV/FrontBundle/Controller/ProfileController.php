<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Form\Type\ProfileFormType;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use BV\FrontBundle\Entity\User;

class ProfileController extends Controller
{
    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm(new ProfileFormType(), $user);

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $userManager = $this->container->get('fos_user.user_manager');
                try {
                    $userManager->updateUser($user);

                    $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );
                    return $this->container->get('templating')->renderResponse(
                        'FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
                        array(
                            'form' => $form->createView(),
                            'user' => $user
                        )
                    );
                }
                catch (DBALException $e) {
                    $request->getSession()->getFlashBag()->add('error', 'Cet email est déjà utilisé sur la plateforme' );
                }
            }
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array(
                'form' => $form->createView(),
                'user' => $user,
                'isRequiredCertif' => ($user->getCertif() == null),
                'isRequiredAttestation' => ($user->getAttestation() == null)
            )
        );
    }

    public function updateRequestAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');

        $params = $request->request->get('fos_user_profile_form');
        if (!array_key_exists('message', $params) || $params['message'] == '')
        {
            $request->getSession()->getFlashBag()->add('error', 'Un message est requis' );
        }

        $this->container->get('bv_mailer')->sendInformationsChangedEmail(
            $user, $params['message']
        );

        $request->getSession()->getFlashBag()->add('success', 'Votre message a bien été envoyé' );

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }
}
