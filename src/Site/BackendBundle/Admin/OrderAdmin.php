<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\Order;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class OrderAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        // display the first page (default = 1)
        '_page' => 1,
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',
        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'createdAt',
    ];
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $subject = false;
        if(!($subject=$this->getSubject()))$subject= new Order();
        $states = $subject->states;
        $datagridMapper
            ->add('id')
            ->add('state', 'doctrine_orm_choice', [
                'label' => 'Статус заказа',
            ],
                'choice',
                [
                    'choices' => $states,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => ['class' => 'filter-state-choices']
                ]
            )
        ;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('phone')
            ->add('username')
            ->add('state')
            ->add('createdAt')
            ->add('price','text',[
                'template'=>"SiteBackendBundle:List:_price.html.twig",
                'label'=>'Сумма'
            ])
            ->add('items','text',[
                'template'=>"SiteBackendBundle:List:_items.html.twig",
                'label'=>'Состав заказа'
            ])
            ->add('_action', 'actions', [
                'actions' => [
//                    'show' => [
//                        'template' => 'SiteBackendBundle:List:_listShow.html.twig'
//                    ],
                    'edit' => [
                        'template' => 'SiteBackendBundle:List:_listOrderEdit.html.twig'
                    ],
                    'delete' => [
                        'template' => 'SiteBackendBundle:List:_listDelete.html.twig'
                    ],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Контактные данные')
            ->add('username','text',[
                'required'=>true,
                'label'=>'Ф.И.О.'
            ])
            ->add('phone','text',[
                'required'=>true,
                'label'=>'Телефон',
                'attr'=>[
                    'class'=>'phone-mask'
                ]
            ])
            ->add('email','text',[
                'required'=>false,
                'label'=>'Email'
            ])
            ->add('comment','textarea',[
                'required'=>false,
                'label'=>'Comment'
            ])
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
        ;
    }
    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('show');
        $collection->add('orderaddproduct', $this->getRouterIdParameter().'/order-add-product');
        $collection->add('orderaddset', $this->getRouterIdParameter().'/order-add-set');
        $collection->add('newpostform', $this->getRouterIdParameter().'/order-get-newpost-form');
        $collection->add('ukrposhtaform', $this->getRouterIdParameter().'/order-get-ukrpost-form');
    }
}
