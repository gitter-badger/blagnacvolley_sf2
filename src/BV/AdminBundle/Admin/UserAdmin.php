<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Model\UserInterface;

use FOS\UserBundle\Model\UserManagerInterface;

class UserAdmin extends Admin
{
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
            ->addIdentifier('username')
            ->add('email')
//            ->add('groups')
            ->add('enabled', null, array('editable' => true))
            ->add('locked', null, array('editable' => true))
//            ->add('createdAt')
        ;

        /*
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
            ;
        }
        */
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('locked')
            ->add('email')
//            ->add('groups')
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
//            ->with('Groups')
//                ->add('groups')
//            ->end()
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
        /*
         * $groups
         * $roles (array)
         */
        $formMapper
            ->tab('General')
                ->with('User')
                    ->add('username')
                    ->add('email')
                    ->add('plainPassword', 'text', array(
                        'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                    ))
                ->end()
                ->with('Profile')
                    ->add('gender', 'sonata_user_gender', array(
                        'required' => true,
                    ))
                    ->add('firstname', null, array('required' => false))
                    ->add('lastname', null, array('required' => false))
                    ->add('dob', 'birthday', array('required' => false))
                    ->add('address')
                    ->add('phone', null, array('required' => false))
                    ->add('picture')
                ->end()
            ->end()
            ->tab('Club')
                ->with('License')
                    ->add('status', 'bv_user_status', array(
                        'required' => true,
                    ))
                    ->add('licenseNumber', 'text')
                    ->add('feeAmount')
                    ->add('datePayment')
                ->end()
                ->with('Shirt')
                    ->add('shirtSize')
                    ->add('dateShirtDelivered')
                ->end()
                ->with('Teams')
                    ->add('level', 'bv_user_level')
                    ->add('mscTeam')
                    ->add('femTeam')
                    ->add('mixTeam')
                    ->add('isLookingForTeam')
                ->end()
            ->end()
//            ->with('Groups')
//                ->add('groups', 'sonata_type_model', array(
//                    'required' => false,
//                    'expanded' => true,
//                    'multiple' => true
//                ))
//            ->end()
        ;

//        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('Management')
                ->with('Management')
//                    ->add('realRoles', 'sonata_security_roles', array(
//                        'label'    => 'form.label_roles',
//                        'expanded' => true,
//                        'multiple' => true,
//                        'required' => false
//                    ))
                    ->add('locked', null, array('required' => false))
                    ->add('expired', null, array('required' => false))
                    ->add('enabled', null, array('required' => false))
                    ->add('credentialsExpired', null, array('required' => false))
                ->end()
                ->end()
            ;
//        }

    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
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
