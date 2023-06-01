<?php

namespace Tests;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

// Setup autoloading
chdir(dirname(__DIR__));
require_once 'init_autoloader.php';

class Bootstrap extends AbstractHttpControllerTestCase
{

	protected static $serviceManager;

	public static function init()
	{

		$serviceManager = new ServiceManager(new ServiceManagerConfig());
		$serviceManager->setService('ApplicationConfig', include 'config/application.config.php');
		$serviceManager->get('ModuleManager')->loadModules();
		static::$serviceManager = $serviceManager;
	}

	public function setUp()
	{
		$this->setApplicationConfig($this->getServiceManager()->get('ApplicationConfig'));
		parent::setUp();
	}


	public static function getServiceManager()
	{
		return static::$serviceManager;
	}

}

Bootstrap::init();
