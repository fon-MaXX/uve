<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2015
 * Time: 4:21 PM
 */

namespace Site\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Site\BackendBundle\Entity\SubCategory;

class LoadSubCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $em=$this->container->get('doctrine')->getManager();
        $arr = [
            'кольца',
            'цепи и браслеты',
            'подвески',
            'аксессуары',
            'серьги',
            'пирсинг',
            'пандора',
            'золото',
        ];
//        всего 16 подкатегорий, через 1 по 3
        $num=0;
        foreach($arr as $k=>$item){
            $category = $this->getReference('category_'.$k);

            if($k%2==0){
                for($i=0;$i<3;$i++){
                    $subCategory = new SubCategory();
                    $subCategory->setTitle('Подкатегория+'.(string)$i);
                    $subCategory->setCategory($category);
                    $em->persist($subCategory);
                    $this->addReference('sub_category_'.$num, $subCategory);
                    $num++;
                }
            }
            else{
                $subCategory = new SubCategory();
                $subCategory->setTitle('Подкатегория+'.'0');
                $subCategory->setCategory($category);
                $em->persist($subCategory);
                $this->addReference('sub_category_'.$num, $subCategory);
                $num++;
            }
        }
        $em->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}