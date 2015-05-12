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
//    public function createQuery($context = 'list')
//    {
//        $query = parent::createQuery($context);
//        $query->setSortOrder('is_read');
//        return $query;
//    }

    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_page' => 1,
                '_sort_order' => 'ASC', // sort direction
                '_sort_by' => 'isRead' // field name
            );
        }
    }

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
            ->add('isRead', 'doctrine_orm_choice', array(), 'choice' , array('choices' => array(true => 'Oui', false => 'Non')))
            ->add('level', 'doctrine_orm_string')
            ->add('content', 'doctrine_orm_string')
            ->add('created', 'doctrine_orm_datetime')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('level',  'choice', array ('choices' => SystemLog::getLevels(), 'template' => 'AdminBundle:Notification:Fields/level_field.html.twig') )
            ->add('type',  'choice', array ('choices' => SystemLog::getTypes(), 'template' => 'AdminBundle:Notification:Fields/type_field.html.twig') )
            ->add('content', 'string', array('template' => 'AdminBundle:Notification:Fields/content_field.html.twig'))
            ->add('created','datetime',array('format' => 'd M Y H:i'))
            ->add('isRead', 'boolean', array('template' => 'AdminBundle:Notification:Fields/boolean_field.html.twig') )
            ->add('_action', 'actions', array(
                'actions' => array(
                    'markAsRead' => array('template' => 'AdminBundle:Notification:list__action_mark_as_read.html.twig'),
                )
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('markHasRead', $this->getRouterIdParameter().'/markHasRead');
        $collection->add('validateFile', $this->getRouterIdParameter().'/validateFile');
        $collection->add('refuseFile', $this->getRouterIdParameter().'/refuseFile');
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
