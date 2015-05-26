<?php

namespace BV\FrontBundle\Controller;

use BV\FrontBundle\Form\Type\ProfileFormType;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use BV\FrontBundle\Entity\User;
use Tools\LogBundle\Entity\SystemLog;

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
                'isRequiredAttestation' => ($user->getAttestation() == null),
                'isRequiredParentalAdvisory' => ($user->getAge() <= 18 && $user->getParentalAdvisory() == null),
            )
        );
    }

    public function renewAction(Request $request)
    {
        /* @var $user User */
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        // Check if user is allowed to renew his License
        if (!$user->isAllowedToRenew())
        {
            throw new Exception('This user cannot perform this action.');
        }

        if ('GET' === $request->getMethod()) {
            $userManager = $this->container->get('fos_user.user_manager');
            try {
                $user->setStatus(User::STATUS_ACTIVE_WAITING_VALIDATION);
                $userManager->updateUser($user);
                $request->getSession()->getFlashBag()->add('success', 'Modifications sauvegardées avec succès' );

                // Send notification to Admin to validate the account
                $this->container->get('tools.logbundle.logger')->addWarning(SystemLog::TYPE_USER_LICENSE_RENEWAL, $user);
            }
            catch (DBALException $e) {
                $request->getSession()->getFlashBag()->add('error', 'Cet email est déjà utilisé sur la plateforme' );
            }
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function updateRequestAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser(); /* @var $user User */
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
                'user' => $user,
                'isRequiredCertif' => ($user->getCertif() == null),
                'isRequiredAttestation' => ($user->getAttestation() == null),
                'isRequiredParentalAdvisory' => ($user->getAge() <= 18 && $user->getParentalAdvisory() == null),
            )
        );
    }

    public function toggleGroupAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        if ('GET' === $request->getMethod()) {
            if ($this->getDoctrine()->getRepository('FrontBundle:Events')->isEventTypeValidForGroup($request->get('type')))
            {
                /* @var $user User */
                $user->toggleGroup($request->get('type'));
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);
                $request->getSession()->getFlashBag()->add('success', 'Modifications apportées avec succès' );

                // user left group, remove availabilities associated
                if (!$user->isInGroup($request->get('type')))
                {
                    $em = $this->container->get('doctrine.orm.entity_manager');
                    foreach ($this->getDoctrine()->getRepository('FrontBundle:Availability')->findByUser($user) as $availability)
                    {
                        $em->remove($availability);
                    }
                    $em->flush();
                }

                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            }
            else
            {
                $request->getSession()->getFlashBag()->add('error', 'Type invalide' );
            }
        }

        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }
}
