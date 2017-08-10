<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Site\BackendBundle\Entity\Callback;

class CallbackAdmin extends AbstractAdmin
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
        if(!($subject=$this->getSubject()))$subject= new Callback();
        $states = $subject->states;
        $datagridMapper
            ->add('id')
            ->add('state', 'doctrine_orm_choice', [
                'label' => 'Статус запроса',
            ],
                'choice',
                [
                    'choices' => $states,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => ['class' => 'filter-state-choices']
                ]
            );
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $callback  = new Callback();
        $listMapper
            ->add('id')
            ->add('name',null,[
                'label'=>'Имя'
            ])
            ->add('phone',null,[
                'label'=>'Телефон'
            ])
            ->add('state','choice',[
                'editable'=>true,
                'choices'=>array_flip($callback->states),
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [
                        'template' => 'SiteBackendBundle:List:_listEdit.html.twig'
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
        $callback = new Callback();
        $formMapper
            ->add('name','text',[
                'required'=>true
            ])
            ->add('phone','text', [
                'attr' => [],
                'required'=>true
            ])
            ->add('state','choice', [
                'attr' => [],
                'choices'=>$callback->states,
                'required'=>true,
                'label'=>'Статус'
            ])
        ;
    }
    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('show');
    }
}
