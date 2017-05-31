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

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
//        // ** 6 **
//        $price = [180,220,310,280,360,565];
//        // ** 3 **
//        $metal = [
//            'серебро 925',
//            'золото 975',
//            'платина',
//        ];
//        // ** 7 **
//        $weight = [
//            '1,9',
//            '2,3',
//            '3,4',
//            '4',
//            '5,1',
//            '6,6',
//            '8,5'
//        ];
//        // ** 3 **
//        $state = [
//            'в наличии',
//            'под заказ',
//            'только в наборе',
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
//        $insertionColors=[];
//        for($i=0;$i<6;$i++){
//            $insertionColors[$i]=$this->getReference('insertion_color_'.$i);
//        }
//        $chainSizes=[];
//        for($i=0;$i<6;$i++){
//            $chainSizes[$i]=$this->getReference('chain_size_'.$i);
//        }
//        $ringSize=[];
//        for($i=0;$i<6;$i++){
//            $ringSize[$i]=$this->getReference('ring_size_'.$i);
//        }
//        $number=1;
//        for($i=0;$i<16;$i++){
//            $subCategory = $this->getReference('sub_category_'.$i);
//            $category = $subCategory->getCategory();
//            for($j=0;$j<15;$j++){
//                $product = new Product();
//                $product->setSubCategory($subCategory);
//                $title=$name[rand(0,4)].'-'.$j;
//                $product->setTitle($category->getTitle().' '.$title);
//                $product->setTheme($theme[rand(0,2)]);
//                ($j==0)?$product->setState($state[2]):$product->setState($state[0]);
//                $value = $price[rand(0,5)];
//                $product->setPrice($value);
//                if($j%3){
//                    $product->setSharePrice($value-70);
//                    $tag=$this->getReference('share');
//                    $product->addShareTag($tag);
//                }
//                if($j%5){
//                    $tag=$this->getReference('novel');
//                    $product->addShareTag($tag);
//                }
//                if($j%2){
//                    $tag=$this->getReference('hit');
//                    $product->addShareTag($tag);
//                }
//                $product->setMetal($metal[rand(0,2)]);
//                $product->setWeight($weight[rand(0,6)]);
//                $product->setInsertionType('полудрагоценная');
//                $randomArray=[];
//                while(count($randomArray)<5){
//                    $value = rand(0,5);
//                    (!in_array($value,$randomArray))?$randomArray[]=$value: '';
//                }
//                foreach($randomArray as $item){
//                    $product->addInsertionColor($insertionColors[$item]);
//                    if($category->getTitle()=='цепи и браслеты'){
//                        $product->addChainSize($chainSizes[$item]);
//                    }
//                    else{
//                        $product->addRingSize($ringSize[$item]);
//                    }
//                }
//                $product->setPoster($posters[rand(0,3)]);
//                $product->setCod(1000000+$number);
//                $em->persist($product);
//                $this->addReference('product_'.$number, $product);
//                $number++;
//            }
//        }
//        $em->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}