<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FilterConfigThemeAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
//        $collection->remove('delete');
        $collection->remove('batch');
//        $collection->remove('create');
        $collection->remove('show');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->add('filterConfigProductType', 'sonata_type_model', [
            'label' => 'Тип продукта'
        ])
        ->add('filterConfigThemeValue', 'sonata_type_collection',
            [
                'label' => 'Значение',
                'by_reference' => false,
                'type_options' =>
                    [
                        'delete' => true,
                    ]
            ],
            [
                'edit' => 'inline',
                'inline' => 'table',
                'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigThemeValue'
            ])
        ;
    }
}
