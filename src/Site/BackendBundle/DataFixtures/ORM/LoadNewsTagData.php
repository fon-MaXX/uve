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
use Site\BackendBundle\Entity\ChainSize;
use Site\BackendBundle\Entity\NewsTag;
use Site\BackendBundle\Entity\RingSize;
use Site\BackendBundle\Entity\ShareTag;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadNewsTagData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
            'news_share'=>'акция',
            'news_article'=>'новинка'
        ];
        foreach($arr as $k=>$item){
            $nT = new NewsTag();
            $nT->setTitle($item);
            $em->persist($nT);
            $this->addReference($k, $nT);
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