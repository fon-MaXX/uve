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
use Site\BackendBundle\Entity\Category;
use Site\BackendBundle\Entity\SubCategory;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
            'кольца'=>[
                0=>[
                    'title'=>'Женские кольца',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Мужские кольца и печати',
                    'position'=>4
                ],
                2=>[
                    'title'=>'Обручальные кольца',
                    'position'=>3
                ]
            ],
            'серьги'=>[
                0=>[
                    'title'=>'Английская застежка',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Гвоздики (на закрутке)',
                    'position'=>4
                ],
                2=>[
                    'title'=>'Французская застежка (петелька)',
                    'position'=>3
                ],
                3=>[
                    'title'=>'Каффы',
                    'position'=>2
                ]
            ],
            'подвесы'=>[
                0=>[
                    'title'=>'Подвес',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Церковная тематика',
                    'position'=>4
                ],
                2=>[
                    'title'=>'Зодиаки',
                    'position'=>3
                ],
                3=>[
                    'title'=>'Обереги',
                    'position'=>2
                ]
            ],
            'цепи и браслеты'=>[
                0=>[
                    'title'=>'Цепи',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Браслеты со вставками камней',
                    'position'=>4
                ],
                2=>[
                    'title'=>'Браслеты без вставок',
                    'position'=>3
                ]
            ],
            'аксессуары'=>[
                0=>[
                    'title'=>'Столовые приборы',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Булавки и брошки',
                    'position'=>4
                ],
                2=>[
                    'title'=>'Сувениры / талисманы',
                    'position'=>3
                ],
                3=>[
                    'title'=>'Прочее',
                    'position'=>2
                ]
            ],
            'пирсинг'=>[
                0=>[
                    'title'=>'Пирсинг',
                    'position'=>5
                ]
            ],
            'пандора'=>[
                0=>[
                    'title'=>'Браслеты',
                    'position'=>5
                ],
                1=>[
                    'title'=>'Чармсы и бусины',
                    'position'=>4
                ]
            ],
            'золото'=>[
                0=>[
                    'title'=>'Золото',
                    'position'=>5
                ]
            ]
        ];
        foreach($arr as $k=>$item){
            $category  = new Category();
            $category->setTitle($k);
            $em->persist($category);
            foreach($item as $value){
                $subCategory = new SubCategory();
                $subCategory->setTitle($value['title']);
                $subCategory->setPosition($value['position']);
                $subCategory->setCategory($category);
                $em->persist($subCategory);
            }
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