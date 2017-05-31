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
use Site\BackendBundle\Entity\Slider;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Site\BackendBundle\Entity\Category;

class LoadSliderData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $image = '{"default_file":"/test/slides/1/file59197b7b7d4bf.jpg","big":"/test/slides/1/big59197bf330b77.png"}';
        $text = '<p>Только <strong>3 дня</strong><br /> скидка на обручальные<br /> кольца</p>';


        for($i=0;$i<3;$i++){
            $slide = new Slider();
            $slide->setPosition(rand(1,10));
            $slide->setImage($image);
            $slide->setText($text);
            $discount = '-'.rand(5,25).'%';
            $slide->setDiscount($discount);
            $em->persist($slide);
        }
        $em->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}