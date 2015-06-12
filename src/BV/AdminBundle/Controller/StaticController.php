<?php

namespace BV\AdminBundle\Controller;

use BV\FrontBundle\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tools\LogBundle\Entity\SystemLog;
use Symfony\Component\Filesystem\Filesystem;

class StaticController extends Controller
{
    public function markHasReadAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $object->setIsRead(true);
        $this->admin->update($object);

        $this->addFlash('sonata_flash_success', 'Mark as read successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function batchActionMerge(ProxyQueryInterface $selectedModelQuery)
    {
        if (!$this->admin->isGranted('EDIT') || !$this->admin->isGranted('DELETE'))
        {
            throw new AccessDeniedException();
        }

        $request = $this->get('request');
        $modelManager = $this->admin->getModelManager();

        $target = $modelManager->find($this->admin->getClass(), $request->get('targetId'));

        if( $target === null){
            $this->addFlash('sonata_flash_info', 'flash_batch_merge_no_target');

            return new RedirectResponse(
                $this->admin->generateUrl('list',$this->admin->getFilterParameters())
            );
        }

        $selectedModels = $selectedModelQuery->execute();

        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->setIsRead(true);
                $modelManager->update($selectedModel);
            }
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_batch_merge_error');

            return new RedirectResponse(
                $this->admin->generateUrl('list',$this->admin->getFilterParameters())
            );
        }

        $this->addFlash('sonata_flash_success', 'flash_batch_merge_success');

        return new RedirectResponse(
            $this->admin->generateUrl('list',$this->admin->getFilterParameters())
        );
    }

    /**
     * Triggered when user click on Accept link (for New Certif or new Attestation files)
     *
     * @return RedirectResponse
     */
    public function validateFileAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
        }

        $user = $object->getUser();
        if (!$user instanceof User) {
            return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
        }

        if ($object->getType() == SystemLog::TYPE_USER_WAITING_VALIDATION)
        {
            if (!$user->isAllowedToRenew() == null) {
                $this->addFlash('sonata_flash_error', 'Cet utilisateur n\'est pas dans les conditions de renouvellement de licence');
                return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
            }

            $user->setStatus(User::STATUS_ACTIVE_WAITING_LICENSE);
            $modelManager = $this->admin->getModelManager();
            $modelManager->update($user);

            $object->setIsRead(true);
            $this->admin->delete($object);
        }

        $this->addFlash('sonata_flash_success', 'Le fichier a bien été validé.');

        return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
    }

    /**
     * Triggered when user click on Refuse link (for New Certif or new Attestation files)
     *
     * @return RedirectResponse
     */
    public function refuseFileAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
        }

        $user = $object->getUser();
        if (!$user instanceof User) {
            return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
        }

        // Delete from Cache (If any) & Upload Dir
        $this->container->get('bv_cache')->deleteFileFromUploadDir($user->getId(), User::IMAGE_TYPE_ATTESTATION);
        $this->container->get('bv_cache')->deleteFileFromUploadDir($user->getId(), User::IMAGE_TYPE_PARENTAL_ADV);
        $this->container->get('bv_cache')->deleteFileFromUploadDir($user->getId(), User::IMAGE_TYPE_CERTIF);

        $this->container->get('bv_cache')->deleteFilesFromCache($user->getId(), User::IMAGE_TYPE_ATTESTATION);
        $this->container->get('bv_cache')->deleteFilesFromCache($user->getId(), User::IMAGE_TYPE_PARENTAL_ADV);
        $this->container->get('bv_cache')->deleteFilesFromCache($user->getId(), User::IMAGE_TYPE_CERTIF);

        $user->setFileFromType(User::IMAGE_TYPE_ATTESTATION, null);
        $user->setFileFromType(User::IMAGE_TYPE_PARENTAL_ADV, null);
        $user->setFileFromType(User::IMAGE_TYPE_CERTIF, null);

        // Update User
        $user->setStatus(User::STATUS_ACTIVE_NOT_LICENSED);
        $modelManager = $this->admin->getModelManager();
        $modelManager->update($user);

        // Delete Notification
        $this->admin->delete($object);

        // Send email for refused file
        $this->container->get('bv_mailer')->sendLicenseRenewalRefused($user, $object->getType());

        $this->addFlash('sonata_flash_success', 'Les fichiers ont bien été supprimé. Un email a été envoyé à l\'utilisateur pour le prévenir du refus de renouvelement' );

        return new RedirectResponse($this->admin->generateUrl('list',$this->admin->getFilterParameters()));
    }
}
