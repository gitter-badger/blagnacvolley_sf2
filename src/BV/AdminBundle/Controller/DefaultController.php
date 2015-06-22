<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\User;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function deleteFileAction(Request $request)
    {
        if (!$this->getUser()->isSuperAdmin()) {
            throw new AccessDeniedException();
        }

        if ('GET' === $request->getMethod())
        {
            if ($request->get('type') != null && $request->get('id') != null)
            {
                $user = $this->getDoctrine()->getRepository('FrontBundle:User')->find($request->get('id'));
                if ($user instanceof $user && User::isFileTypeValid($request->get('type')))
                {
                    $user->setFileFromType($request->get('type'), null);
                    try {
                        $this->container->get('sonata.user.admin.user')->getUserManager()->updateUser($user);
                        $this->container->get('bv_cache')->deleteFileFromUploadDir($user->getId(), $request->get('type'));
                        $this->container->get('bv_cache')->deleteFilesFromCache($user->getId(), $request->get('type'));
                        $this->get('session')->getFlashBag()->add('success', 'Suppression effectuée avec succès');
                    }
                    catch (DBALException $e)
                    {
                        $this->get('session')->getFlashBag()->add('error', 'Une image de profil est requise pour l\'utilisateur');
                    }
                }
            }
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    public function newSeasonResetUserAction(Request $request)
    {
        $result = ['success' => false];

        if($request->isXmlHttpRequest())
        {
            /* @var User $user */
            $user = $this->container->get('security.context')->getToken()->getUser();

            if (!is_object($user) || !$user instanceof User) {
                $result['error'] = true;
                $result['message'] = 'Vous devez vous authentifier pour pouvoir continuer.';
                $response = new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            if (!$user->isSuperAdmin()) {
                $result['error'] = true;
                $result['message'] = 'Vous devez ëtre admin pour effectuer cela.';
                $response = new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            $id = $request->get('id');

            $userRetrieved = $this->container->get('doctrine')->getRepository('FrontBundle:User')->find($id);
            if ($userRetrieved instanceof User && $userRetrieved->getStatus() != User::STATUS_INACTIVE)
            {
                $userRetrieved->setStatus(User::STATUS_ACTIVE_NOT_LICENSED);

                if ($userRetrieved->getCertif() != null)
                {
                    $this->container->get('bv_cache')->deleteFileFromUploadDir($userRetrieved->getId(), User::IMAGE_TYPE_CERTIF);
                    $this->container->get('bv_cache')->deleteFilesFromCache($userRetrieved->getId(), User::IMAGE_TYPE_CERTIF);
                    $userRetrieved->setCertif(null);
                    $userRetrieved->setDateCertif(null);
                }

                if ($userRetrieved->getAttestation() != null)
                {
                    $this->container->get('bv_cache')->deleteFileFromUploadDir($userRetrieved->getId(), User::IMAGE_TYPE_ATTESTATION);
                    $this->container->get('bv_cache')->deleteFilesFromCache($userRetrieved->getId(), User::IMAGE_TYPE_ATTESTATION);
                    $userRetrieved->setAttestation(null);
                    $userRetrieved->setDateAttestation(null);
                }

                if ($userRetrieved->getParentalAdvisory() != null)
                {
                    $this->container->get('bv_cache')->deleteFileFromUploadDir($userRetrieved->getId(), User::IMAGE_TYPE_PARENTAL_ADV);
                    $this->container->get('bv_cache')->deleteFilesFromCache($userRetrieved->getId(), User::IMAGE_TYPE_PARENTAL_ADV);
                    $userRetrieved->setParentalAdvisory(null);
                    $userRetrieved->setDateParentalAdvisory(null);
                }

//                $em = $this->container->get('doctrine')->getManager();
//                $em->persist($userRetrieved);
//                $em->flush();

                $this->container->get('bv_mailer')->sendNewSeason(
                    $userRetrieved
                );

                $result = ['success' => true, 'user' => [
                    'id' => $userRetrieved->getId(),
                    'name' => $userRetrieved->getFirstname().' '.$userRetrieved->getLastname(),
                    'status' => $userRetrieved->getStatus()
                ]];
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
