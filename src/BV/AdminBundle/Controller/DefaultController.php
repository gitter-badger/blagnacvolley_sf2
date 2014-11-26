<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\User;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
}
