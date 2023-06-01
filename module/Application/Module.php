<?php

namespace Application;

use AsseticBundle\View\Helper\Asset;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\Navigation;
use Zend\View\HelperPluginManager;
use ZendDiagnostics\Check;
use ZendDiagnostics\Result\Success;
use ZendDiagnostics\Result\Failure;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Application\Extended\DoctrineDataFixtureModule\Command\ImportCommandCustom;
use Application\Listener\EntityInjectorListener;

/**
 * Class Module
 * @package Application
 */
class Module
{

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
//~r(get_defined_constants(true));
//        die();
        $app = $e->getApplication();
        $eventManager = $app->getEventManager();

        $sm = $app->getServiceManager();

        // use request params to differentiate register type (user/model)
        $routeMatch = $sm->get('Router')->match($sm->get('Request'));

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedManager = $eventManager->getSharedManager();

        // enable doctrine filter for the active records only for frontend
        if ($routeMatch && strpos($routeMatch->getMatchedRouteName(), 'zfcadmin') === false) {
            $sm->get('em')->getFilters()->enable('active');
        }

        // add user state to register form
        $sharedManager->attach(\ZfcUser\Form\Register::class, 'init', function ($e) use ($routeMatch) {

            /* @var $form \ZfcUser\Form\Register */
            $form = $e->getTarget();

            // get an array of the route params and their values
            $routeParams = $routeMatch->getParams();
            if (isset($routeParams['type'])) {
                $form->add(array(
                    'name' => 'type',
                    'type' => 'Zend\Form\Element\Hidden',
                    'options' => array(
                        'label' => ' ',
                    ),
                    'attributes' => array(
                        'value' => $routeParams['type'],
                    ),
                ));
            }

        });

        // add default user role('user', 'performer') on registration
        $e->getApplication()
            ->getServiceManager()
            ->get('zfcuser_user_service')
            ->getEventManager()
            ->attach('register.post', function ($e) use ($sm) {

                /* @var $form \ZfcUser\Form\Register */
                $form = $e->getParam('form');

                /* @var $user \Application\Entity\User */
                $user = $e->getParam('user');

                $user->addRole(
                    $sm->get('em')->getRepository(\Application\Entity\Role::class)->findOneBy(['roleId' => $form->get('type')->getValue()])
                );

                // @todo remove
                $_SESSION['group'] = $user->getRole();

                $sm->get('em')->persist($user);
                $sm->get('em')->flush();

            });

        // add ACL information to the Navigation view helper
        $authorize = $sm->get(\BjyAuthorize\Service\Authorize::class);

        Navigation::setDefaultAcl($authorize->getAcl());
        Navigation::setDefaultRole($authorize->getIdentity());

        // attach  blamable listener to doctrine if loggedin
        $auth = $sm->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $listener = new \Gedmo\Blameable\BlameableListener();
            $listener->setUserValue($auth->getIdentity());
            $sm->get('em')->getEventManager()->addEventSubscriber($listener);
        }

    }

    /**
     * Get the service Config
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Form\Messages' =>  function($sm) {
                    $form = new \Application\Form\Message($sm);
                    $form->setServiceLocator($sm);
                    return $form;
                },
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        foreach (glob(__DIR__ . '/config/*.php') as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Gedmo\Mapping\Annotation' => 'vendor/gedmo/doctrine-extensions/lib'
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getDiagnostics()
    {
        $dirs = [
            'data/cache',
            'data/logs',
            'magento',
            'data/DoctrineORMModule',
            /*'data/Zombaio_Data/.htaccess',
            'data/Zombaio_Data/.htpasswd',*/
            'public/uploads/photos',
            'public/uploads/logos',
            'public/uploads/videos',
            'public/uploads/watermarks',
        ];

        $userFolders = \Application\Entity\Custom\UserMethods::$folders;

        $number = scandir('public/uploads/users')[10];
        array_walk($userFolders, function(&$value) use ($number) {
            $value = 'public/uploads/users/'.$number.'/'.$value;
        });
        $dirs = array_merge($dirs, $userFolders);

        return array(
            'Stream Wrapper Exists' => ['StreamWrapperExists', ['zip']],
            'Directories available & writable' => ['DirWritable', $dirs],
            'Check PHP extensions' => [
	            'ExtensionLoaded', [
                    'json',
                    'pdo',
                    'intl',
                    'dom',
                    'gd',
                    'OAuth',
                    'pcre',
                    'zlib',
                    'redis',
                    'exif',
                    'mbstring',
                    'mcrypt',
                    'Reflection',
                    'imagick',
                    //'Zend OPcache'
                ]
            ],
            'Check nginx is running' => ['ProcessRunning', 'nginx'],
            'Check redis is running' => ['ProcessRunning', 'redis-server'],
	        'Check php-fpm is running' => ['ProcessRunning', 'php-fpm'],
	        'Check MySQL is running' => ['ProcessRunning', 'mysql'],
            'Check PHP Version' => ['PhpVersion', ['5.5.0', '>=']],
            'Check minimum free space' => ['DiskFree', '1GB', getcwd()],
            'Check internet connectivity' => ['HttpService', 'www.google.com'],
            //'Check magento store is up' => ['HttpService', $this->],
            'Security Advisory' => ['SecurityAdvisory', 'composer.lock'],
            'Check PHP enabled flags' => [
	            'PhpFlag',
	            getenv('APPLICATION_ENV') == 'development' ? [] : ['opcache', 'short_open_tag'],
	            true
            ],
	        'Check PHP disabled flags' => [
	            'PhpFlag',
	            getenv('APPLICATION_ENV') != 'development' ? [
                    'display_startup_errors',
                    'allow_url_fopen',
                    'xdebug'
                ] : [],
	            false
            ],
            'Check PHP magento flags' => ['PhpFlag', 'always_populate_raw_post_data', -1], // needs to be -1
            'Check file ownership for nginx' => [
                'Callback',
                function() use ($dirs){

                    foreach($dirs as $f) {

                        if (!file_exists($f)) {
                            return new Failure('Folder ' . $f . ' does not exists');
                        }

                        $perms = substr(sprintf('%o', fileperms($f)), -3);

                        if(posix_getpwuid(fileowner($f)) == 'nginx') {
                            return new Failure('Owner ' . $f . ' is not nginx');
                        }

                        if($perms < 755) {
                            return new Failure('Folder ' . $f . ' does not have write permissions for user nginx');
                        }

                    }

                    return new Success("All folders are ok");

                }
            ],
            'Check binary dependencies' => [
                'Callback',
                function(){

                    $binaries = array(
                        '/usr/bin/node',
                        '/usr/bin/npm',
                        '/usr/local/bin/grunt',
                        '/usr/local/bin/forever'
                    );

                    foreach($binaries as $bin) {

                        if (!file_exists($bin)) {
                            return new Failure('Binary ' . $bin . ' does not exists');
                        }

                    }

                    return new Success("All dependencies installed");

                }
            ],
            'Check node servers are running' => [
                'Callback',
                function(){

                    // @TODO check all individually
                    $output = [];
                    exec('ps auwx | grep -c "bin/nodejs"', $output, $return);
                    if ($output[0] < 6) {
                        return new Failure('You should check that all the nodejs processes are running !');
                    }

                    return new Success("All good");

                }
            ]
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'asset' => function($helpers) {
                    /** @var HelperPluginManager $helpers */
                    return new Asset($helpers->getServiceLocator());
                },
            ),
        );
    }

}
