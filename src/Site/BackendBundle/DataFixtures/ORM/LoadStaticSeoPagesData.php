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
use Site\BackendBundle\Entity\StaticSeoPages;

class LoadStaticSeoPagesData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $arr=[
            1=>[
                'linkname'=>'main',
                'title'=>'главная'
            ],
            2=>[
                'linkname'=>'about_company',
                'title'=>'о нас'
            ],
            3=>[
                'linkname'=>'set_list',
                'title'=>'наборы'
                ],
            4=>[
                'linkname'=>'about_delivery',
                'title'=>'доставка'
                ],
            5=>[
                'linkname'=>'news',
                'title'=>'Новости'
                ],
            6=>[
                'linkname'=>'about_payment',
                'title'=>'Оплата'
            ],
            7=>[
                'linkname'=>'contacts',
                'title'=>'Контакты'
                ],
            8=>[
                'linkname'=>'recalls',
                'title'=>'Отзывы'
            ],
            9=>[
                'linkname'=>'selected',
                'title'=>'Избранное'
            ],
            10=>[
                'linkname'=>'comparing_list',
                'title'=>'Сравнение'
            ],
            11=>[
                'linkname'=>'comparing_show',
                'title'=>'Приступить к сравнению'
            ],
            12=>[
                'linkname'=>'search',
                'title'=>'Результаты поиска'
            ],
            13=>[
                'linkname'=>'order',
                'title'=>'Оформление заказа'
            ],
            14=>[
                'linkname'=>'static_reviews',
                'title'=>'Отзывы'
            ]
        ];
        foreach($arr as $item){
            $object = new StaticSeoPages();
            $object->setTitle($item['title']);
            $object->setLinkname($item['linkname']);
            $em->persist($object);
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