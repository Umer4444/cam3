<?php

namespace Application\Fixtures;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\Expr;

/**
 * Class DefaultData
 * @package Application\Fixtures
 */
class DefaultData implements FixtureInterface, ServiceLocatorAwareInterface, OrderedFixtureInterface
{

    /**
     * persists and flushes to database
     */
    use ServiceLocatorAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        define('FIXTURES', true);

        $manager->getConnection()->exec('SET foreign_key_checks = 0');

        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/resource.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/newsletter.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/config.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/countries.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/country_city.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/country_codes.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/country_province.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/static_pages.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/payment_method.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/info.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/rules.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/scheduled_media.sql'));
        $manager->getConnection()->exec(file_get_contents(__DIR__ . '/../../../config/fixtures/sounds.sql'));

        $manager->getConnection()->exec('SET foreign_key_checks = 1');

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

}