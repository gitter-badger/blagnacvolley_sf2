<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use BV\FrontBundle\Entity\Events;
use Sonata\AdminBundle\Route\RouteCollection;
use Tools\LogBundle\Entity\SystemLog;

class NotificationAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('level', 'choice', array('choices' => SystemLog::getLevels()))
            ->add('content')
            ->add('created')
            ->add('isRead')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('level', 'doctrine_orm_string')
            ->add('content', 'doctrine_orm_string')
            ->add('created', 'doctrine_orm_datetime')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('level',  'choice', array ('choices' => SystemLog::getLevels() ) )
            ->add('type',  'choice', array ('choices' => SystemLog::getTypes() ) )
            ->add('content')
            ->add('created','datetime',array('format' => 'd M Y H:m'))
            ->add('isRead')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'Clone' => array(
                        'template' => 'AdminBundle:Static:list__action_mark_as_read.html.twig'
                    )
                )
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('markHasRead', $this->getRouterIdParameter().'/markHasRead');
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['merge'] = array(
            'label' => $this->trans('action_merge', array(), 'SonataAdminBundle'),
            'ask_confirmation' => true
        );

        return $actions;
    }
}
