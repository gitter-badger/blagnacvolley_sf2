<?php

namespace BV\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

class TasksAdmin extends Admin
{
    protected $baseRoutePattern = 'tasks';
    protected $baseRouteName = 'tasks';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
        $collection->add('newSeason', $this->getRouterIdParameter().'/newSeason');
    }
}
