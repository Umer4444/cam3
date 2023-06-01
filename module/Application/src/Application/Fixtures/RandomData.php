<?php

namespace Application\Fixtures;

use Application\Entity\User;
use PerfectWeb\Core\Utils\Status;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Images\Entity\AlbumCoverImage;
use Images\Entity\BlogImage;
use Images\Entity\Photo;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Crypt\Password\Bcrypt;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Provider\Internet;
use Faker\Provider\DateTime as FakeDate;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\Text;
use Faker\Provider\Lorem;
use Faker\Provider\Base;
use Faker\Provider\Image;
use Faker\Provider\en_US\PhoneNumber;
use Faker\Provider\en_US\Address;
use Faker\Provider\Color;
use Doctrine\ORM\Query\Expr;
use Videos\Entity;

/**
 * Class RandomData
 * @package Application\Fixtures
 */
class RandomData implements FixtureInterface, ServiceLocatorAwareInterface, OrderedFixtureInterface
{

    /**
     * persists and flushes to database
     */
    use ServiceLocatorAwareTrait;

    function __construct()
    {

        $this->faker = \Faker\Factory::create('en_US');
        $this->person = new Person($this->faker);
        $this->internet = new Internet($this->faker);
        $this->date = new FakeDate($this->faker);
        $this->lorem = new Lorem($this->faker);
        $this->base = new Base($this->faker);
        $this->phoneNumber = new PhoneNumber($this->faker);
        $this->color = new Color($this->faker);
        $this->addressGenerator = new Address($this->faker);
        $this->text = new Text($this->faker);
        $this->image = new Image($this->faker);
        $users = 60;
        $this->nr = [
            'users' => $users,
            'blogs' => $this->base->numberBetween(20, 45),
            'images' => $this->base->numberBetween(2, 15),
            'videos' => $this->base->numberBetween(0, 5),
            'categories' => $this->base->numberBetween(2, 5),
            'shows' => $this->base->numberBetween(0, 5),
            'pledges' => $this->base->numberBetween(0, 5),
            'albums' => $this->base->numberBetween(2, 5),
            'photos' => $this->base->numberBetween(2, 5),
            'comments' => $this->base->numberBetween(2, 5),
        ];

        $this->videoClasses = array(
            \Videos\Entity\PremiereVideo::class,
            \Videos\Entity\PledgeVideo::class,
            \Videos\Entity\VodVideo::class,
            \Videos\Entity\ProfileVideo::class,
            \Videos\Entity\ShowVideo::class
        );

        $this->videoTypes = array(
            'profile' => Entity\ProfileVideo::class,
            'pledge' =>Entity\PledgeVideo::class,
            'show' => Entity\ShowVideo::class,
            'vod' => Entity\VodVideo::class,
            'premiere' => Entity\PremiereVideo::class
        );

        $this->userStatusesProfile = array(
            'banned',
            'online',
            'normal',
            'online',
        );

        $this->userStatuses = array(
            'online',
            'offline',
        );

    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        \Nelmio\Alice\Fixtures::load('module/Application/config/fixtures/fixtures.yml', $manager);

        $resourceType = ['model', \Videos\Entity\Video::class, 'pledge', 'blog', 'image'];
        $userResourceProfile = $manager->getRepository(\PerfectWeb\Core\Entity\Resource::class)->findBy(['group' => 'profile']);

        foreach($manager->getRepository(User::class)->findAll() as $user) {
            foreach($userResourceProfile as $userResource) {

                $setting = new \PerfectWeb\Core\Entity\ResourceValue();
                $setting->setValue($this->text->realText());
                $setting->setResource($userResource);

                $user->addSetting($setting);

            }
        }

        //for ($j = 0; $j < $this->nr['users']; $j++) {


            /*if ($isPerformer) {

                // user resource values
                foreach($userResourceProfile as $userResource) {

                    $accessEntity = new \PerfectWeb\Core\Entity\UserAccess();
                    $accessEntity->setResource($userResource);
                    $accessEntity->setPermission($this->base->randomElement(array('r', 'rw')));

                    $user->addAccessResource($accessEntity);

                    $setting = new \PerfectWeb\Core\Entity\ResourceValue();
                    // @todo provide meaningful data - in some cases we need it
                    // valid id - check if resource has Entity and add an id
                    $setting->setValue($this->text->realText());
                    $setting->setResource($userResource);

                    $user->addSetting($setting);

                }

                }

            }

            $manager->persist($user);

        }*/

        // comments
        for ($i = 0; $i < $this->nr['comments']; $i++) {

            $comment = $this->generateComment();

            if ($this->base->numberBetween(0, 1)) {
                $comment->addChild($this->generateComment());
            }

            $manager->persist($comment);

        }

        $manager->flush();
        $manager->clear();

    }

    function generateComment()
    {

        $url = '/guestbook/'. $this->base->numberBetween(0, $this->nr['users']);

        $comment = new \Application\Entity\RbComments();
        $comment->setContent($this->lorem->sentence());
        $comment->setVisible($this->base->numberBetween(0, 1));
        $comment->setSpam($this->base->numberBetween(0, 1));
        $comment->setAuthor($this->person->firstName());
        $comment->setUri($url);
        $comment->setThread(md5($url));
        $comment->setContact($this->internet->email());

        return $comment;

    }

    /**
     * @param string $class
     *
     * @return \Images\Entity\Photo
     */
    function generatePhoto($class = Photo::class, $active = true)
    {

        $photo = new $class();
        $photo->setCaption($this->lorem->words(2, true));
        $photo->setStatus($active ? Status::ACTIVE : $this->base->numberBetween(-1, 2));
        $photo->setPosition($this->base->numberBetween(1, 200));
        $photo->setFilename('http://placeimg.com/400/400/any.png');

        return $photo;

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

}