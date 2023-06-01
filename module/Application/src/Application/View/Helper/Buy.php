<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;
use PerfectWeb\Payment\Interfaces;

class Buy extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use Traits\Entity;
    use Traits\EntityManager;

    /**
     * @var bool
     */
    protected $isPurchased = false;

    private $buttonView;

    public function __invoke($entity, $url = null, $identity = null)
    {

        $view = new ViewModel();
        $view->setTemplate('buttons/buy');

        $wallet = $this->getServiceLocator()->getServiceLocator()->get('wallet');
        $this->setEntity(
            is_object($entity) ? $entity : $this->getEntityManager()->find($entity, $identity)
        );

        if (!$this->isPurchasable() && !$this->isUnlockable()) {
            return $this;
        }

        $this->setIsPurchased($wallet->isPurchased($this->getEntity()));

        $view->setVariables([
            'object' => $this->getEntity(),
            'isPurchased' => $this->isPurchased(),
            'url' => is_null($url) ? $this->getServiceLocator()->get('object')->__invoke($entity)->toUrl() : $url,
            'hash' => $this->getServiceLocator()
                           ->get('crypt')
                           ->encrypt(get_class($this->getEntity()) . '::' . $this->getEntity()->getId())
        ]);

        $this->setButtonView($view);

        return $this;

    }

    function __toString()
    {

        if (!$this->isPurchasable() && !$this->isUnlockable()) {
            return '';
        }

        return $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($this->getButtonView());

    }

    /**
     * @return bool
     */
    public function isPurchased()
    {
        return $this->isPurchased;
    }

    /**
     * @param bool $isPurchased
     */
    public function setIsPurchased($isPurchased)
    {
        $this->isPurchased = boolval($isPurchased);
    }

    function isPurchasable()
    {
        return in_array(Interfaces\Purchasable::class, class_implements($this->getEntity())) &&
            $this->getEntity()->getCost() && $this->objectOwnerIsLoggedIn();
    }

    function isUnlockable()
    {
        return in_array(Interfaces\Unlockable::class, class_implements($this->getEntity())) &&
            $this->getEntity()->getPassword() && $this->objectOwnerIsLoggedIn();
    }

    /**
     * @return mixed
     */
    public function getButtonView()
    {
        return $this->buttonView;
    }

    /**
     * @param $buttonView
     *
     * @return $this
     */
    public function setButtonView($buttonView)
    {
        $this->buttonView = $buttonView;
        return $this;
    }

    private function objectOwnerIsLoggedIn()
    {
        return
            (
                $this->getView()->zfcUserIdentity() &&
                $this->getEntity()->getUser()->getId() != $this->getView()->zfcUserIdentity()->getId()
            ) ||
            !$this->getView()->zfcUserIdentity();

    }

}