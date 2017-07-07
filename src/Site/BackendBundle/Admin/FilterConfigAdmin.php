<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FilterConfigAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('delete');
        $collection->remove('batch');
        $collection->remove('create');
        $collection->remove('show');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('price', null, [
                'label' => 'Максимальная стоимость товара'
            ])
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
            ->with("Настройки фильтров")
            ->add('price', null, [
                'label' => 'Максимальная стоимость товара'
            ])
            ->end()
            ->with("Цвет")
            ->add('filterConfigInsertionColors', 'sonata_type_collection',
                [
                    'label' => 'Цвет',
                    'by_reference' => false,
                    'type_options' =>
                        [
                            'delete' => true,
                        ]
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigInsertionColors'
                ])
            ->end()

            ->with("Тип вставки")
            ->add('filterConfigInsertionType', 'sonata_type_collection',
                [
                    'label' => 'Тип вставки',
                    'by_reference' => false,
                    'type_options' =>
                        [
                            'delete' => true,
                        ]
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigInsertionType'
                ])
            ->end()
            ->with("Статус")
            ->add('filterConfigState', 'sonata_type_collection',
                [
                    'label' => 'Статус',
                    'by_reference' => false,
                    'type_options' =>
                        [
                            'delete' => true,
                        ]
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigState'
                ])
            ->end()
            ->with("Длинна")
            ->add('filterConfigChainSizes', 'sonata_type_collection',
                [
                    'label' => 'Длинна',
                    'by_reference' => false,
                    'type_options' =>
                        [
                            'delete' => true,
                        ]
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigChainSizes'
                ])
            ->end()
            ->with("Типы товаров и их характеристики")
            ->add('filterConfigTheme', 'sonata_type_collection',
                [
                    'label' => 'Типы товаров и их характеристики',
                    'by_reference' => false,
                    'type_options' =>
                        [
                            'delete' => true,
                        ]
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\FilterConfigTheme'
                ])
            ->end()
        ;
    }
}
