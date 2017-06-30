<?php

namespace Site\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Site\UploadBundle\UpbeatTraits\UpbeatUploadFullAdminTrait;
use Site\UploadBundle\Form\UpbeatUploadType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Site\FrontendBundle\Form\FilterConfig;
class SetAdmin extends AbstractAdmin
{
    use UpbeatUploadFullAdminTrait;
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
        $this->childAdmins = new \SplObjectStorage();
        $imagesAdmin = new \StdClass;
        $imagesAdmin->fieldName = 'setGallery';
        $imagesAdmin->adminService = 'site_backend.admin.set.gallery';
        $this->childAdmins->attach($imagesAdmin);
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('cod',null,[
                'label'=>'field.cod'
            ])
            ->add('title',null,[
                'label'=>'field.title'
            ])
        ;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('cod',null,[
                'label'=>'field.cod'
            ])
            ->add('title',null,[
                'label'=>'field.title'
            ])
            ->add('products','string',[
                'label'=>'field.set_products',
                'template' => 'SiteBackendBundle:List:_setProducts.html.twig'
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
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $formConfig = new FilterConfig();
        $this->states = $formConfig->getAdminSetStates();
        $this->insertionTypes = $formConfig->getInsertionTypes();
        $formMapper
            ->with('SEO часть')
            ->add('title','text',[
                'required'=>true
            ])
            ->add('isFresh',CheckboxType::class,[
                'required'=>true,
                'label'=>'Присутствовал в синхронизации'
            ])
            ->add('keywords','textarea', [
                'attr' => [
                    'class' => 'keywords-textarea'
                ],
                'required'=>false
            ])
            ->add('description','textarea',[
                'required'=>false
            ])
            ->end()
            ->with('Контентная часть')
                ->add('products', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'tagTitle',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:Product')->getAllSortedByCod(),
                    'multiple' => true,
                    'attr' => ['class' => 'multiSelect'],
                    'label'=>'field.set_products',
                    'btn_add'=>false
                ])
                ->add('cod','text',[
                    'required'=>true,
                    'label'=>'field.cod'
                ])
                ->add('state',ChoiceType::class,[
                    'label'=>"field.state",
                    'choices'=>array_flip($this->states),
                ])
                ->add('poster', UpbeatUploadType::class,[
                    'file_type' => 'set_icon',
                    'template'  => 'SiteBackendBundle:Upload:product_image.html.twig',
                    'extensions' => 'jpg,gif,png',
                    'label'=>'field.poster'
                ])
                ->add('metal',null,[
                    'label'=>'field.metal',
                    'required'=>false
                ])
                ->add('insertionType',ChoiceType::class,[
                    'choices'=>array_flip($this->insertionTypes),
                    'label'=>'field.insertion_type',
                    'required'=>false
                ])
                ->add('theme',null,[
                    'label'=>'field.theme',
                    'required'=>false
                ])
                ->add('shareTags', 'sonata_type_model', [
                    'expanded' => false,
                    'property'=>'title',
                    'by_reference' => false,
                    'query' => $em->getRepository('SiteBackendBundle:ShareTag')->getAllSortedByTitle(),
                    'multiple' => true,
                    'attr' => ['class' => 'multiSelect'],
                    'label'=>'field.share_tag'
                ])
                ->add('rating','text',[
                    'required'=>false,
                    'label'=>'Рейтинг'
                ])
            ->end()
            ->with("Gallery")
            ->add('setGallery', 'sonata_type_collection', [
                'label' => 'Photos',
                'by_reference' => false
            ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'SiteBackendBundle\Entity\SetGallery'
                ])
            ->end();
        ;
        $formMapper->get('insertionType')->addModelTransformer(new CallbackTransformer(
            function ($insertionType) {
                //                  call`s after getter
                $types = array_flip($this->insertionTypes);
                return  (isset($types[$insertionType]))?$types[$insertionType]:null;
            },
            function ($insertionType) {
                //                   call`s before setter
                return (isset($this->insertionTypes[$insertionType]))?$this->insertionTypes[$insertionType]:null;
            }
        ));
        $formMapper->get('state')->addModelTransformer(new CallbackTransformer(
            function ($state) {
                //                  call`s after getter
                $states = array_flip($this->states);
                return  (isset($states[$state]))?$states[$state]:null;
            },
            function ($state) {
                //                   call`s before setter
                return (isset($this->states[$state]))?$this->states[$state]:null;
            }
        ));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('keywords')
            ->add('description')
            ->add('products', 'sonata_type_model', [
                'label'=>'field.set_products',
                'template'=>'SiteBackendBundle:Show:_tag.html.twig'
            ])
            ->add('cod','text',[
                'label'=>'field.cod'
            ])
            ->add('state','text',[
                'label'=>"field.state"
            ])
            ->add('rating','text',[
                'label'=>"Рейтинг"
            ])
            ->add('poster', 'text',[
                'template'  => 'SiteBackendBundle:Show:_poster.html.twig',
                'label'=>'field.poster'
            ])
            ->add('metal',null,[
                'label'=>'field.metal',
            ])
            ->add('insertionType',null,[
                'label'=>'field.insertion_type',
            ])
            ->add('theme',null,[
                'label'=>'field.theme',
            ])
            ->add('insertionColors', 'sonata_type_model', [
                'label'=>'field.insertion_colors',
                'template'=>'SiteBackendBundle:Show:_tag.html.twig'
            ])
        ;
    }
}
