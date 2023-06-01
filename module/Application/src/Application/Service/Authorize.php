<?php
namespace Application\Service;

use BjyAuthorize\Service\Authorize as BjyAuthorize;
use Zend\Permissions\Acl\Acl;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Authorize
 * @package Application\Service
 */
class Authorize extends BjyAuthorize
{
    const TYPE_ALLOW = 'allow';

    const TYPE_DENY = 'deny';

    /**
     * @var Acl
     */
    protected $acl;

    /**
     * @var RoleProvider[]
     */
    protected $roleProviders = array();

    /**
     * @var ResourceProvider[]
     */
    protected $resourceProviders = array();

    /**
     * @var RuleProvider[]
     */
    protected $ruleProviders = array();

    /**
     * @var IdentityProvider
     */
    protected $identityProvider;

    /**
     * @var GuardInterface[]
     */
    protected $guards = array();

    /**
     * @var \Closure|null
     */
    protected $loaded;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct(array $config, ServiceLocatorInterface $serviceLocator)
    {
        $this->config = $config;
        $this->serviceLocator = $serviceLocator;
        $that = $this;
        $this->loaded = function () use ($that) {
            $that->load();
        };
    }

    /**
     * Initializes the service
     *
     * @internal
     *
     * @return void
     */
    public function load()
    {
        if (null === $this->loaded) {
            return;
        }

        $this->loaded = null;

        /** @var $cache StorageInterface */
        $cache = $this->serviceLocator->get('BjyAuthorize\Cache');
        $success = false;
        $this->acl = $cache->getItem($this->config['cache_key'], $success);

        if (!($this->acl instanceof Acl) || !$success) {
            $this->loadAcl();
            $cache->setItem($this->config['cache_key'], $this->acl);
        }

        $this->setIdentityProvider($this->serviceLocator
            ->get('BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider'));

        $parentRoles = $this->getIdentityProvider()->getIdentityRoles();

        $this->acl->addRole($this->getIdentity(), $parentRoles);
    }

    /**
     * Initialize the Acl
     */
    private function loadAcl()
    {
        $this->acl = new Acl();

        foreach ($this->serviceLocator->get('BjyAuthorize\RoleProviders') as $provider) {
            $this->addRoleProvider($provider);
        }

        foreach ($this->serviceLocator->get('BjyAuthorize\ResourceProviders') as $provider) {
            $this->addResourceProvider($provider);
        }

        foreach ($this->serviceLocator->get('BjyAuthorize\RuleProviders') as $provider) {
            $this->addRuleProvider($provider);
        }

        foreach ($this->serviceLocator->get('BjyAuthorize\Guards') as $guard) {
            $this->addGuard($guard);
        }

        foreach ($this->roleProviders as $provider) {
            $this->addRoles($provider->getRoles());
        }

        foreach ($this->resourceProviders as $provider) {
            $this->loadResource($provider->getResources(), null);
        }

        foreach ($this->ruleProviders as $provider) {
            $rules = $provider->getRules();
            if (isset($rules['allow'])) {
                foreach ($rules['allow'] as $rule) {
                    $this->loadRule($rule, static::TYPE_ALLOW);
                }
            }

            if (isset($rules['deny'])) {
                foreach ($rules['deny'] as $rule) {
                    $this->loadRule($rule, static::TYPE_DENY);
                }
            }
        }

        $auth = $this->serviceLocator->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
        }

        if (isset($user)) {
            $entityManager = $this->serviceLocator->get('em');
            $accessRepo = $entityManager->getRepository(\PerfectWeb\Core\Entity\UserAccess::class);

            $userResourceRepo = $entityManager->getRepository(\PerfectWeb\Core\Entity\Resource::class);
            $allResources = $userResourceRepo->findAll();

            foreach ($allResources as $rule) {
                if (!$this->acl->hasResource($rule->getName())) {
                    $this->loadResource(array($rule->getName()));
                }
            }

            $rules = $accessRepo->findBy(array('user' => $user->getId()), array());

            foreach ($rules as $rule) {
                $ruleArray = array(
                    'user', $rule->getResource()->getName(), $rule->getPermission()
                );
                if (!$this->acl->hasResource($rule->getResource()->getName())) {
                    $this->loadResource(array($rule->getResource()->getName()));
                }
                $this->loadRule($ruleArray, static::TYPE_ALLOW);
            }
        }
    }

    /**
     * @deprecated this method will be removed in BjyAuthorize 2.0.x
     *
     * @param mixed $rule
     * @param mixed $type
     *
     * @throws \InvalidArgumentException
     */
    protected function loadRule(array $rule, $type)
    {
        $privileges = $assertion = null;
        $ruleSize = count($rule);

        if (4 === $ruleSize) {
            list($roles, $resources, $privileges, $assertion) = $rule;
            $assertion = $this->serviceLocator->get($assertion);
        } elseif (3 === $ruleSize) {
            list($roles, $resources, $privileges) = $rule;
        } elseif (2 === $ruleSize) {
            list($roles, $resources) = $rule;
        } else {
            throw new \InvalidArgumentException('Invalid rule definition: ' . print_r($rule, true));
        }

        if (is_string($assertion)) {
            $assertion = $this->serviceLocator->get($assertion);
        }

        if (static::TYPE_ALLOW === $type) {
            $this->acl->allow($roles, $resources, $privileges, $assertion);
        } else {
            $this->acl->deny($roles, $resources, $privileges, $assertion);
        }
    }

    /**
     * @return Acl
     */
    public function getAcl()
    {
        $this->loaded && $this->loaded->__invoke();

        return $this->acl;
    }
}