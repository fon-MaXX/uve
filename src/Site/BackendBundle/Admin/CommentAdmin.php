<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Site\BackendBundle\Entity\Comment;

class CommentAdmin extends AbstractAdmin
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
        if(!($subject=$this->getSubject()))$subject= new Comment();
        $states = $subject->states;
        $datagridMapper
            ->add('id')
            ->add('state', 'doctrine_orm_choice', [
                'label' => 'Статус комментария',
            ],
                'choice',
                [
                    'choices' => $states,
                    'expanded' => true,
                    'multiple' => true,
                    'attr' => ['class' => 'filter-state-choices']
                ]
            )
            ->add('type', 'doctrine_orm_choice', [
                'label' => 'Тип комментария',
            ],
                'choice',
                [
                    'choices' => [
                        'отзыв'=>'review',
                        'комментарий'=>'comment'
                    ],
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
            ->add('username',null,[
                'label'=>'Имя'
            ])
            ->add('text',null,[
                'label'=>'Текст'
            ])
            ->add('state','text',[
                'template'=>"SiteBackendBundle:List:_comment_state.html.twig",
                'label'=>'Статус'
            ])
            ->add('type','text',[
                'template'=>"SiteBackendBundle:List:_comment_type.html.twig",
                'label'=>'Тип'
            ])
            ->add('pageUrl',null,[
                'label'=>'Адрес страницы'
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
        $comment = new Comment();
        $formMapper
            ->add('type','text', [
                'attr' => [
                    'readonly'=>true
                ],
                'required'=>false,
                'label'=>'Тип'
            ])
            ->add('pageUrl','text', [
                'attr' => [
                    'readonly'=>true
                ],
                'required'=>false,
                'label'=>'Адрес страницы'
            ])
            ->add('username','text',[
                'required'=>true
            ])
            ->add('rating','choice',[
                'required'=>true,
                'choices'=>[
                    1=>1,
                    2=>2,
                    3=>3,
                    4=>4,
                    5=>5,
                ],
                'label'=>'Рейтинг'
            ])
            ->add('gender','choice', [
                'attr' => [],
                'choices'=>[
                    'муж'=>'муж',
                    'жен'=>'жен'
                ],
                'required'=>true,
                'label'=>'Пол'
            ])
            ->add('text','textarea', [
                'attr' => [],
                'required'=>true,
                'label'=>'Текст'
            ])
            ->add('state','choice', [
                'attr' => [],
                'choices'=>$comment->states,
                'required'=>true,
                'label'=>'Статус'
            ])
            ->add('answer','textarea', [
                'attr' => [],
                'required'=>false,
                'label'=>'Ответ'
            ])
        ;
    }
    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->remove('show');
    }
}
