<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2015
 * Time: 4:21 PM
 */

namespace SiteBackendBundleDataFixturesORM;

use Site\BackendBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Site\BackendBundle\Entity\ProductGallery;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductGalleryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
//        $em=$this->container->get('doctrine')->getManager();
//        $posters=[
//            0=>'{"default_file":"/test/products/1/file590c5d0b72329.jpg","extra_small":"/test/products/1/extra_small590c5d0f0bccd.png","small":"/test/products/1/small590c5d0f1f945.png","big":"/test/products/1/big590c5d0f45b67.png"}',
//            1=>'{"default_file":"/test/products/2/file590c5d47da3b1.png","extra_small":"/test/products/2/extra_small590c5d4b737c1.png","small":"/test/products/2/small590c5d4b77f00.png","big":"/test/products/2/big590c5d4ba0736.png"}',
//            2=>'{"default_file":"/test/products/3/file590c5d6c22671.png","extra_small":"/test/products/3/extra_small590c5d6e62d3a.png","small":"/test/products/3/small590c5d6e6916e.png","big":"/test/products/3/big590c5d6e9ba0b.png"}',
//        ];
//        for($i=1;$i<241;$i++){
//            $product=$this->getReference('product_'.$i);
//            for($j=0;$j<5;$j++){
//                $gallery=new ProductGallery();
//                $gallery->setProduct($product);
//                $gallery->setImage($posters[rand(0,2)]);
//                $em->persist($gallery);
//            }
//        }
//        $em->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}