<?php

namespace Tests;

// Setup autoloading
chdir(dirname(__DIR__));
require_once 'init_autoloader.php';

use Symfony\Component\EventDispatcher\GenericEvent;
use VDB\Spider\Spider;
use VDB\Spider\Event\SpiderEvents;
use Tests\Extended\VDB\Spider\Discoverer\CssSelectorDiscoverer;
use VDB\Spider\Filter\Prefetch;

class Crawler extends \PHPUnit_Framework_TestCase
{

    private $url = 'http://local.chat.com/';

    /**
     * @var \VDB\Spider\Spider
     */
    private $spider = null;

    protected function setUp()
    {

        $this->spider = new Spider($this->url);
        $this->spider->addDiscoverer(new CssSelectorDiscoverer("a"));
        $this->spider->addDiscoverer(new CssSelectorDiscoverer("link"));
        $this->spider->addDiscoverer(new CssSelectorDiscoverer("img", "src"));
        $this->spider->addDiscoverer(new CssSelectorDiscoverer("script", "src"));

        $this->spider->addPreFetchFilter(new Prefetch\AllowedSchemeFilter(array('http')));
        $this->spider->addPreFetchFilter(new Prefetch\AllowedHostsFilter([$this->url]));

        $this->spider->getDispatcher()->addListener(
            SpiderEvents::SPIDER_CRAWL_PRE_ENQUEUE,
            function (GenericEvent $event) {
                /* @var $subject \VDB\Spider\Spider */
                $subject = $event->getSubject();
                $queued = count($subject->getStatsHandler()->getQueued());
                if (!($queued % 100)) {
                    echo '+'.$queued;
                }
            }
        );

        $test = &$this;

        $this->spider->getDispatcher()->addListener(
            SpiderEvents::SPIDER_CRAWL_FILTER_POSTFETCH,
            function (GenericEvent $event) use ($test) {
                $test->assertEquals(200, $event->getArgument('document')->getResponse()->getStatusCode());
            }
        );

        $this->spider->setMaxDepth(10);

    }

    function tearDown()
    {
        $this->spider = null;
    }

    function __testSpiderNoLogin()
    {
        $this->spider->crawl();
    }

    function testSpiderAdminLogin()
    {

        $this->spider->getRequestHandler()->getClient()->post(
            $this->url.'account/login', null, ['identity' => 'admin@xexposed.com', 'credential' => 'testadmin']
        );

        $this->spider->crawl();

    }

}