<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2015
 * Time: 4:21 PM
 */

namespace SiteBackendBundleDataFixturesORM;

use Site\BackendBundle\Entity\Set;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadSetData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
////        1- array of only-in-set elements
//        $onlyInSetIndexes=[];
//        $num=1;
//        for($i=1;$i<17;$i++){
//            $onlyInSetIndexes[]=$num;
//            $num+=15;
//        }
//        // ** 3 **
//        $state = [
//            'в наличии',
//            'под заказ',
//        ];
//        // ** 3 **
//        $theme = [
//            'детская',
//            'взрослая',
//            'с россыпью'
//        ];
//        // ** 5 **
//        $name = [
//            '"Ножка младенца"',
//            '"Крылья совы"',
//            '"С россыпью камней"',
//            '"Капля"',
//            '"Сердце в камнях"'
//        ];
//        $posters=[
//            0=>'{"default_file":"/test/products/1/file590c5d0b72329.jpg","extra_small":"/test/products/1/extra_small590c5d0f0bccd.png","small":"/test/products/1/small590c5d0f1f945.png","big":"/test/products/1/big590c5d0f45b67.png"}',
//            1=>'{"default_file":"/test/products/2/file590c5d47da3b1.png","extra_small":"/test/products/2/extra_small590c5d4b737c1.png","small":"/test/products/2/small590c5d4b77f00.png","big":"/test/products/2/big590c5d4ba0736.png"}',
//            2=>'{"default_file":"/test/products/3/file590c5d6c22671.png","extra_small":"/test/products/3/extra_small590c5d6e62d3a.png","small":"/test/products/3/small590c5d6e6916e.png","big":"/test/products/3/big590c5d6e9ba0b.png"}',
//            3=>''
//        ];
////        15 sets
//        for($i=0;$i<15;$i++){
////            5 elements to each set
//            $indexes=[];
//            $indexes[]= $onlyInSetIndexes[rand(0,15)];
//            while(count($indexes)<5){
//                $value=rand(1,240);
//                (!in_array($value,$indexes))?$indexes[]=$value:'';
//            }
//            $set=new Set();
//            if($i%5==0){
//                $tag=$this->getReference('share');
//                $set->addShareTag($tag);
//            }
//            if($i%3==0){
//                $tag=$this->getReference('novel');
//                $set->addShareTag($tag);
//            }
//            if($i%2==0){
//                $tag=$this->getReference('hit');
//                $set->addShareTag($tag);
//            }
//            $set->setInsertionType('полудрагоценная');
//            $title='Набор '.$name[rand(0,4)].'-'.$i;
//            $set->setTitle($title);
//            $set->setPoster($posters[rand(0,3)]);
//            $set->setCod(2000000+$i);
//            $set->setState($state[rand(0,1)]);
//            $set->setTheme($theme[rand(0,2)]);
//            foreach($indexes as $item){
//                $product = $this->getReference('product_'.$item);
//                $set->addProduct($product);
//                $em->persist($set);
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