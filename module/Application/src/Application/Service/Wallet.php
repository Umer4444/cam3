<?php

namespace Application\Service;

use Application\Entity\User;
use Nicovogelaar\Paginator\Exception;
use PerfectWeb\Core\Traits;
use PerfectWeb\Payment\Interfaces\Purchasable;
use PerfectWeb\Payment\Interfaces\Unlockable;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Payment\Entity\PurchasedContent;

/**
 * Class Wallet
 * @package Application\Service
 */
class Wallet implements ServiceLocatorAwareInterface
{

    use Traits\Ensure;

    /**
     * @param $amount integer
     * @param $user \Application\Entity\User
     * @throws \Exception
     * @return bool
     */
    public function sendAmount($amount, $user)
    {

        if (is_numeric($user)) {
            $user = $this->getEntityManager()->find(User::class, $user);
        }

        if (!($user instanceof User)) {
            throw new \Exception('The provided user is not valid !');
        }

        if ($amount <= 0) {
            throw new Exception('You need to send a positive amount !');
        }

        if ($this->getAmount() <= $amount) {
            throw new Exception('You dont have enough money in your wallet !');
        }

        $user->setCredit($user->getCredit() + $amount);
        $this->updateAmount($amount * -1);

        return true;

    }

    /**
     * @param $amount
     * @return bool
     * @throws \Exception
     */
    public function contribute($amount)
    {

        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if(!$auth->hasIdentity()) {
            throw new \Exception('Only the logged in user may contribute');
        }
        elseif ($this->getAmount() < $amount) {
            return false;
        }

        $this->updateAmount($this->getAmount() - $amount);

        return true;
    }

    /**
     * @param null $user
     *
     * @return mixed
     */
    public function getAmount($user = null)
    {

        $identity = $this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();

        if (!is_null($user)) {
            $user = $this->ensureUser($user);
            if ($user->getId() !== $identity->getId()) {
                $showIf = trim($this->getServiceLocator()
                                    ->get($user->getRole().'.cfg.'.$user->getId())
                                    ->getConfigValue('wallet/balance_settings'), '_');
                if ($showIf > 0 && $user->getCredit() < $showIf) {
                    return '[private]';
                }
                else {
                    return $user->getCredit();
                }
            }
        }

        return $identity->getCredit();

    }

    /**
     * @param $amount
     * @return $this
     */
    public function updateAmount($amount)
    {
        $user = $this->getServiceLocator()->get('zfcuser_auth_service')->getIdentity();
        $user->setCredit($user->getCredit() + $amount);
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * @param object|string $object
     * @param null $identity
     *
     * @return bool
     */
    function isPurchased($object, $identity = null)
    {

        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if (!$auth->hasIdentity()) {
            return false;
        }

        if (
            (
                is_object($object) && method_exists($object, 'getId') &&
                ($identity = $object->getId()) &&
                ($object = $this->getEntityManager()->getClassMetadata(get_class($object))->getName())
            ) ||
            (is_string($object) && is_numeric($identity))
        ) {

            /** @var \Doctrine\Orm\QueryBuilder $qb */
            $qb = $this->getEntityManager()
                       ->getRepository(PurchasedContent::class)
                       ->createQueryBuilder('p');

            $qb->select('count(p.id)')
               ->where('p.entityReference=:id')
               ->andWhere('p.entity=:entity')
               ->setParameters([
                   'id' => $identity,
                   'entity' => $object
               ]);

            return boolval($qb->getQuery()->getSingleScalarResult());

        }

        return false;

    }

    /**
     * @param object|string $object
     * @param null $identity
     *
     * @return object|bool
     */
    function purchase($object, $identity = null)
    {

        if (is_string($object)) {
            $object = $this->getEntityManager()->find($object, $identity);
        }

        if (!is_object($object) || !in_array(Purchasable::class, class_implements($object)
            )) {
            throw new \LogicException('The entity you are trying to purchase is not purchasable !');
        }

        // dont buy it again if already bought or free
        if (
            (!$object->getCost() && ($object instanceof Unlockable && !$object->getPassword())) ||
            $this->isPurchased($object, $identity)
        ) {
            return $object;
        }

        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        // not enough amount to complete the purchase
        if (!$auth->hasIdentity() || ($auth->hasIdentity() && $auth->getIdentity()->getCredit() < $object->getCost())) {
            return false;
        }

        $purchase = new PurchasedContent();
        $purchase->setEntity(get_class($object));
        $purchase->setEntityReference($object->getId());
        $purchase->setUser($auth->getIdentity());

        if ($object->getCost()) {
            $purchase->setAmount($object->getCost());
            $this->sendAmount($object->getCost(), $object->getUser());
        }

        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();

        return $object;

    }

}