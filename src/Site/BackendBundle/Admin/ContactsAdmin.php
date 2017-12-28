<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\Contacts;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class ContactsAdmin extends AbstractAdmin
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
        if(!($subject=$this->getSubject()))$subject= new Contacts();
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
        $subject= new Contacts();
        $listMapper
            ->add('id')
            ->add('name',null,[
                'label'=>'Имя'
            ])
            ->add('phone',null,[
                'label'=>'Телефон'
            ])
            ->add('theme',null,[
                'label'=>'Тема'
            ])
            ->add('text',null,[
                'label'=>'Текст'
            ])
            ->add('createdAt', null, [
                'label' => 'Дата создания'
            ])
            ->add('state','choice',[
                'editable'=>true,
                'choices'=>array_flip($subject->states),
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [
                        'template' => 'SiteBackendBundle:List:_listShow.html.twig'
                    ],
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
        $subject= new Contacts();
        $formMapper
            ->add('name','text',[
                'required'=>true
            ])
            ->add('phone','text',[
                'required'=>true
            ])
            ->add('email','text',[
                'required'=>false
            ])
            ->add('theme','text',[
                'required'=>false
            ])
            ->add('text','text',[
                'required'=>true
            ])
            ->add('state','choice', [
                'attr' => [],
                'choices'=>$subject->states,
                'required'=>true,
                'label'=>'Статус'
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('theme')
            ->add('text')
        ;
    }
}
