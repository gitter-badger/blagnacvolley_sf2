<?php

namespace BV\AdminBundle\Admin;

use BV\FrontBundle\Entity\Team;
use BV\FrontBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

use FOS\UserBundle\Model\UserManagerInterface;

class UserAdmin extends Admin
{
    protected $container;

    public function __construct($code, $class, $baseControllerName, $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('deactivate', '{id}/deactivate');
        $collection->add('reactivate', '{id}/reactivate');
        $collection->add('validate_renewal', '{id}/validate_renewal');
        $collection->add('refuse_renewal', '{id}/refuse_renewal');
        $collection->add('validate_license', '{id}/validate_license');
    }
    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder( $this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), function($v) {
            return !in_array($v, array('password', 'salt'));
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstname')
            ->add('lastname')
            ->add('address', 'text', array('label' => 'Adresse'))
            ->add('date', 'string', array( 'label'=>'Age', 'code' => 'getAge' ))
            ->add('dob', 'choice', array( 'label'=>'Catégorie', 'template' => 'AdminBundle:User:Fields/category_field.html.twig', 'code' => 'getCategory'))
            ->add('status', 'choice', array('label' => 'Statut', 'template' => 'AdminBundle:User:Fields/status_field.html.twig') )
            ->add('enabled', null, array('editable' => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('firstname')
            ->add('lastname')
            ->add('status', 'doctrine_orm_choice', array( 'label' => 'Statut'), 'choice',
                array(
                    'choices' => User::getStatusList(),
                    'expanded' => false,
                    'multiple' => false
                )
            )
            ->add('enabled')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('username')
                ->add('email')
            ->end()
            ->with('Profile')
                ->add('dob')
                ->add('firstname')
                ->add('lastname')
                ->add('gender')
                ->add('phone')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $user = $this->getSubject(); /* @var $user User */
        $em = $this->container->get('doctrine')->getEntityManager(); /* @var $em EntityManager */
        $choices = $em->getRepository('FrontBundle:Team')->findAllGroupedByType();

//        print_r($choices[Team::TYPE_MSC]);
//        die;
        /*
         * $groups
         * $roles (array)
         */
        $formMapper
            ->tab('Informations personelles')
                ->with('Informations de Connexion')
                    ->add('username', 'text', array('help' => 'Note: Le nom utilisateur doit être unique car utilisé pour se connecter.'))
                    ->add('email', 'text', array('help' => 'Note: Le mail doit être unique car utilisé pour les notifications.'))
                ->end()
                ->with('Informations utilisateur')
                    ->add('gender', 'sonata_user_gender', array('required' => true, 'attr' => ['class' => 'form-control']))
                    ->add('firstname', null, array('required' => true))
                    ->add('lastname', null, array('required' => true))
                    ->add('dob', 'sonata_type_date_picker', array('required' => true, 'format' => 'dd/MM/yyyy'))
                    ->add('address', 'autocomplete', array('required' => true,))
                    ->add('geo_lat', 'hidden')
                    ->add('geo_lng', 'hidden')
                    ->add('phone', null, array('required' => false))
                    ->add('phonePro', null, array('label' => 'Téléphone pro.', 'required' => false))
                    ->add('level', 'bv_user_level', array( 'required' => false, 'attr' => ['class' => 'form-control']))
                ->end()
            ->end()
            ->tab('Fichiers associés')
                ->with('Club')
                    ->add('pictureFile', 'file',            array('label' => 'Photo du joueur', 'required' => false, 'image_type' => User::IMAGE_TYPE_PICTURE))
                    ->add('certifFile', 'file',             array('label' => 'Certificat médical', 'required' => false, 'image_type' => User::IMAGE_TYPE_CERTIF))
                    ->add('attestationFile', 'file',        array('label' => 'Attestation pôle emploi', 'required' => false, 'image_type' => User::IMAGE_TYPE_ATTESTATION))
                    ->add('parentalAdvisoryFile', 'file',   array('label' => 'Accord parental', 'required' => false, 'image_type' => User::IMAGE_TYPE_PARENTAL_ADV))
                ->end()
            ->end()
        ;
        if ($user->getId() != null)
        {
            $formMapper
                ->tab('Informations administratives')
                    ->with('License et facturation')
                        ->add('status', 'bv_user_status', array( 'required' => true, 'attr' => ['class' => 'form-control']))
                        ->add('licenseType', 'choice', array('label' => 'Type license.', 'choices' => User::getLicenseTypeList(), 'required' => false, 'attr' => ['class' => 'form-control']))
                        ->add('licenseNumber', 'text', array('required' => false))
                        ->add('licenseBatch', 'text', array('label' => 'Lot license', 'required' => false))
                        ->add('billingGroup', 'choice', array('label' => 'Groupe de facturation', 'choices' => User::getGroupTypeList(), 'required' => false, 'attr' => ['class' => 'form-control']))
                        ->add('feeAmount')
                        ->add('datePayment', 'sonata_type_datetime_picker', array('required' => false))
                    ->end()
                    ->with('Shirt')
                        ->add('shirtSize')
                        ->add('dateShirtDelivered', 'sonata_type_datetime_picker', array('required' => false))
                    ->end()
                ->end()
            ;
            $qbMsc = $em->createQueryBuilder()
                ->add('select', 't')
                ->from('FrontBundle:Team', 't')
                ->where('t.type = \''.Team::TYPE_MSC.'\'')
            ;
            $qbMix = $em->createQueryBuilder()
                ->add('select', 't')
                ->from('FrontBundle:Team', 't')
                ->where('t.type = \''.Team::TYPE_MIX.'\'')
            ;
            $qbFem = $em->createQueryBuilder()
                ->add('select', 't')
                ->from('FrontBundle:Team', 't')
                ->where('t.type = \''.Team::TYPE_FEM.'\'')
            ;
            $formMapper
                ->tab('Equipes')
                    ->with('Equipes')
                        ->add('isLookingForMscTeam', null, array('label' => 'Recherche une équipe Masculine', 'required' => false))
                        ->add('isLookingForMixTeam', null, array('label' => 'Recherche une équipe Mixe', 'required' => false))
                        ->add('isLookingForFemTeam', null, array('label' => 'Recherche une équipe Féminine', 'required' => false))
                        ->add('mscTeam', 'sonata_type_model', array('query' => $qbMsc, 'btn_add' => false, 'required' => false, 'expanded' => false, 'attr' => ['class' => 'form-control']))
                        ->add('mixTeam', 'sonata_type_model', array('query' => $qbMix, 'btn_add' => false, 'required' => false, 'expanded' => false, 'attr' => ['class' => 'form-control']))
                        ->add('femTeam', 'sonata_type_model', array('query' => $qbFem, 'btn_add' => false, 'required' => false, 'expanded' => false, 'attr' => ['class' => 'form-control']))
                    ->end()
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
//        $this->getUserManager()->updateCanonicalFields($user);
//        $this->getUserManager()->updatePassword($user);
        $this->getUserManager()->updateUser($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}
