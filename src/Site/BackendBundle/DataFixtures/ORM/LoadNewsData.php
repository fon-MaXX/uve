<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/2/2015
 * Time: 4:21 PM
 */

namespace SiteBackendBundleDataFixturesORM;

use Site\BackendBundle\Entity\News;
use Site\BackendBundle\Entity\Set;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
//
//        $name = [
//            'Лорем ипсум долор сит амет, ид темпор',
//            'Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но',
//            'Ат пер магна молестие, вис новум тибияуе минимум ех, симул',
//            'Вих бонорум цонцептам те, вим модус плацерат мандамус те.',
//            'Нец тамяуам пхаедрум адолесценс не. Но еним прима мелиоре вих, хендрерит пертинациа не вис.'
//        ];
//        $posters=[
//            0=>'{"default_file":"/test/products/1/file590c5d0b72329.jpg","extra_small":"/test/products/1/extra_small590c5d0f0bccd.png","small":"/test/products/1/small590c5d0f1f945.png","big":"/test/products/1/big590c5d0f45b67.png"}',
//            1=>'{"default_file":"/test/products/2/file590c5d47da3b1.png","extra_small":"/test/products/2/extra_small590c5d4b737c1.png","small":"/test/products/2/small590c5d4b77f00.png","big":"/test/products/2/big590c5d4ba0736.png"}',
//            2=>'{"default_file":"/test/products/3/file590c5d6c22671.png","extra_small":"/test/products/3/extra_small590c5d6e62d3a.png","small":"/test/products/3/small590c5d6e6916e.png","big":"/test/products/3/big590c5d6e9ba0b.png"}',
//            3=>''
//        ];
//        $tags = [
//            'news_share',
//            'news_article'
//        ];
//        $shortcuts=[
//            0=>'<p>Лорем ипсум долор сит амет, ид темпор епицури сеа. Ид пер анимал долорем пертинах, но еам фабеллас цонсеяуат. Ан либрис цетеро иус, хис ут нобис иуварет, не усу порро алтера. Нец тамяуам пхаедрум адолесценс не. Но еним прима мелиоре вих, хендрерит пертинациа не вис.</p>',
//            1=>'<p>Лорем ипсум долор сит амет, ид темпор епицури сеа. Ид пер анимал долорем пертинах, но еам фабеллас цонсеяуат. Ан либрис цетеро иус, хис ут нобис иуварет, не усу порро алтера. Нец тамяуам пхаедрум адолесценс не. Но еним прима мелиоре вих, хендрерит пертинациа не вис.</p><p>Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но, мелиус абхорреант усу ид. Вим зрил цонсецтетуер волуптатибус ид. Ат пер магна молестие, вис новум тибияуе минимум ех, симул аппетере рецтеяуе еу ест. Но еос малорум елаборарет, еу иус делецтус цонсеяуат.</p>',
//            2=>'<p>Лорем ипсум долор сит амет, ид темпор епицури сеа. Ид пер анимал долорем пертинах, но еам фабеллас цонсеяуат. Ан либрис цетеро иус, хис ут нобис иуварет, не усу порро алтера. Нец тамяуам пхаедрум адолесценс не. Но еним прима мелиоре вих, хендрерит пертинациа не вис.</p><p>Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но, мелиус абхорреант усу ид. Вим зрил цонсецтетуер волуптатибус ид. Ат пер магна молестие, вис новум тибияуе минимум ех, симул аппетере рецтеяуе еу ест. Но еос малорум елаборарет, еу иус делецтус цонсеяуат.</p><p>Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но, мелиус абхорреант усу ид. Вим зрил цонсецтетуер волуптатибус ид. Ат пер магна молестие, вис новум тибияуе минимум ех, симул аппетере рецтеяуе еу ест. Но еос малорум елаборарет, еу иус делецтус цонсеяуат.</p>'
//        ];
////        25 news
//        for($i=0;$i<25;$i++){
//            $tag=$this->getReference($tags[rand(0,1)]);
//            $shortcut=$shortcuts[rand(0,2)];
//            $poster=$posters[rand(0,3)];
//            $title=$name[rand(0,4)];
//            $text="<p>Лорем ипсум долор сит амет, ид темпор епицури сеа. Ид пер анимал долорем пертинах, но еам фабеллас цонсеяуат. Ан либрис цетеро иус, хис ут нобис иуварет, не усу порро алтера. Нец тамяуам пхаедрум адолесценс не. Но еним прима мелиоре вих, хендрерит пертинациа не вис.</p>
//                    <p>Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но, мелиус абхорреант усу ид. Вим зрил цонсецтетуер волуптатибус ид. Ат пер магна молестие, вис новум тибияуе минимум ех, симул аппетере рецтеяуе еу ест. Но еос малорум елаборарет, еу иус делецтус цонсеяуат.</p>
//                    <p>Еи малорум еррорибус тинцидунт вис. Про яуот алияуип ад, еи при тритани фацилис, еум ид виде облияуе перципитур. Промпта инсоленс адиписци яуо еу, фабулас рецтеяуе не цум, граеци семпер волуптариа еи хас. Усу не нисл граецо. Бландит деленити нам ид. Еа вивендо минимум мел.</p>
//                    <p>Нумяуам инсоленс еос еа. Идяуе аудире еррорибус вим но, мелиус абхорреант усу ид. Вим зрил цонсецтетуер волуптатибус ид. Ат пер магна молестие, вис новум тибияуе минимум ех, симул аппетере рецтеяуе еу ест. Но еос малорум елаборарет, еу иус делецтус цонсеяуат.</p>";
//            $article=new News();
//            $article->addNewsTag($tag);
//            $article->setPoster($poster);
//            $article->setTitle($title);
//            $article->setText($text);
//            $article->setShortcut($shortcut);
//            $em->persist($article);
//        }
//        $em->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}