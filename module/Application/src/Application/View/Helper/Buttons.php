<?php

namespace Application\View\Helper;

use PerfectWeb\Core\Traits;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Filter\Word\CamelCaseToDash;

class Buttons extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\User;
    use Traits\Ensure;

    const TYPE_BUTTON = 'BUTTON';
    const TYPE_CONTEXT = 'CONTEXT';

    protected $type = self::TYPE_BUTTON;
    protected $viewModel;

    private $buttons = [
        'follow',
        'favorite',
        'friend',
        'call',
        'tip',
        'message',
        'offer',
        'appointment',
        'special',
        'profile',
        'watch',
        'private',
        'watch-popup',
        'sms',
        'play',
        'buy',
        'kick',
        'repost',
    ];

    public function __construct()
    {
        $this->setViewModel(new ViewModel());
    }

    public function __invoke($user = null)
    {
        if (!is_null($user)) {
            $this->setUser($this->ensureUser($user));
        }
        return $this;
    }

    function __toString()
    {
        try {
            return $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render(
                $this->getViewModel()->setVariables([
                    'user' => $this->getUser(),
                    'type' => $this->getType(),
                    'id' => md5($this->getViewModel()->getTemplate()).$this->getUser()->getId(),
                    'isContext'=> $this->getType() == self::TYPE_CONTEXT ? true : false
                ])
            );
        }
        catch (\Exception $e) {
            return 'Button error !';
        }

    }

    function __call($method, $arguments)
    {

        $method = strtolower((new CamelCaseToDash())->filter($method));

        if (!in_array($method, $this->buttons)) {
            throw new \Exception('Button not defined !');
        }

        if ($this->getServiceLocator()->has($method)) {
            $plugin = $this->getServiceLocator()->get($method);
            if ($plugin && strpos(get_class($plugin), __NAMESPACE__) !== false) {
                return call_user_func_array(array($plugin, '__invoke'), $arguments);
            }
        }

        $this->getViewModel()->setTemplate('buttons/'.$method)->setVariables($arguments);

        return $this;

    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * @param $viewModel
     *
     * @return $this
     */
    public function setViewModel($viewModel)
    {
        $this->viewModel = $viewModel;
        return $this;
    }

}