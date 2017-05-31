<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\Slider;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Site\UploadBundle\Form\UpbeatUploadType;
use Site\UploadBundle\UpbeatTraits\UpbeatUploadFilesAdminTrait;

class SliderAdmin extends AbstractAdmin
{
    use UpbeatUploadFilesAdminTrait;
    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName, $container = null,$entityManager = null,$fileHandler=null)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
        $this->entityManager=$entityManager;
        $this->fileHandler=$fileHandler;
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('text')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $this->last_position = 0;
        if (!empty($this->positionService)) {
            $this->last_position = $this->positionService
                ->getLastPosition($this->getRoot()->getClass());
        }
        $listMapper
            ->add('id')
            ->add('image','text',[
                'template'=>"SiteBackendBundle:List:_sliderImage.html.twig",
                'label'=>'изображение для слайдера'
            ])
            ->add('discount')
            ->add('link')
            ->add('text','text',[
                'template'=>"SiteBackendBundle:List:_text.html.twig",
                'label'=>'text'
            ])
            ->add('position','text',[
                'editable'=>true,
                'label'=>'Приоритет'
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
        $formMapper
            ->add('text','textarea',[
                'required'=>true,
                'label'=>'Текст для слайдера',
                'attr' => [
                    'class' => 'tinymce',
                    'data-theme' => 'simple' // simple, advanced, bbcode
                ]
            ])
            ->add('link','text',[
                'label'=>'Ссылка для слайда:',
                'required'=>true
            ])
            ->add('discount','text',[
                'required'=>true,
                'label'=>'Текст скидки'
            ])
            ->add('image', UpbeatUploadType::class,[
                'file_type' => 'slider',
                'label'=>"изображение для слайдера",
                'template'  => 'SiteBackendBundle:Upload:product_image.html.twig',
                'extensions' => 'jpg,gif,png,jpeg',
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('discount')
            ->add('image','text',[
                'template'=>'SiteBackendBundle:Show:_poster.html.twig'
            ])
            ->add('text','text',[
                "template"=>'SiteBackendBundle:Show:_text.html.twig'
            ])
        ;
    }
}
