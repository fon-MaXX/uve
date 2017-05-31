<?php

namespace Site\BackendBundle\Admin;

use Site\BackendBundle\Entity\ShareTag;
use Site\UploadBundle\UpbeatTraits\UpbeatUploadFilesAdminTrait;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Site\UploadBundle\Form\UpbeatUploadType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ProductGalleryAdmin extends AbstractAdmin
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
        ;
    }
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('image','text',[
                'template'=>"SiteBackendBundle:List:_poster.html.twig",
                'label'=>'Фото'
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
        $formMapper
            ->with('Контентная часть')
                ->add('image', UpbeatUploadType::class,[
                    'file_type' => 'product_gallery_icon',
                    'template'  => 'SiteBackendBundle:Upload:product_image.html.twig',
                    'extensions' => 'jpg,gif,png',
                    'label'=>'Фото'
                ])
                ->add('imageAlt','text',[
                    'label'=>'field.poster_alt',
                    'required'=>false
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
            ->add('id')
            ->add('image', 'text',[
                'template'  => 'SiteBackendBundle:Show:_poster.html.twig',
                'label'=>'Фото'
            ])
            ->add('imageAlt','text',[
                'label'=>"field.poster_alt"
            ])
        ;
    }
    public function getObjectMetadata($object)
    {
        $url = $this->generateUrl('create');

        return new Metadata($object->getTitle(), $object->getShortcut(), $url);
    }
}
