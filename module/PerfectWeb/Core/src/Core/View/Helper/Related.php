<?php

namespace PerfectWeb\Core\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Helper\AbstractHelper;
use PerfectWeb\Core\Traits;
use Zend\View\Model\ViewModel;

class Related extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use Traits\EntityManager;

    const TYPE_RELATED = 'related';
    const TYPE_MORE = 'more';
    const TYPE_LATEST = 'latest';
    const TYPE_POPULAR = 'popular';
    const TYPE_DATE = 'date';
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_FUN_REQUEST = 'fun request';
    const TYPE_PROJECTS = 'projects';
    const TYPE_BOUNTIES = 'bounties';

    var $rows = [];
    var $type = self::TYPE_LATEST;
    var $limit = 8;
    var $template = 'related';
    var $asObject = 'rows';

    public function __invoke($object, $template = 'related', $type = self::TYPE_RELATED, $startDate = null , $limit = 8, $asObject = 'rows')
    {

        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getEntityManager()->getRepository(get_class($object))->createQueryBuilder('o');

        $qb->select('o')->setMaxResults($limit);

        switch ($type) {

            default:
            case self::TYPE_LATEST:
                $qb->orderBy('o.id', 'DESC');

            break;

            case self::TYPE_MORE:
                $qb->where($qb->expr()->neq('o.id', $object->getId()));
            break;

            case self::TYPE_SUBSCRIPTION:
                $qb->where('o.type = :subscription')
                    ->setParameter('subscription',self::TYPE_SUBSCRIPTION);
            break;

            case self::TYPE_FUN_REQUEST:
                $qb->where('o.type = :funRequest')
                    ->setParameter('funRequest',self::TYPE_FUN_REQUEST);
                break;

            case self::TYPE_PROJECTS:
                $qb->where('o.type = :projects')
                    ->setParameter('projects',self::TYPE_PROJECTS);
                break;

            case self::TYPE_BOUNTIES:
                $qb->where('o.type = :bounties')
                    ->setParameter('bounties',self::TYPE_BOUNTIES);
                break;

            case self::TYPE_DATE:
                $qb->select('o')->setMaxResults(1000);
                $qb->where('o.date BETWEEN :startDate AND :endDate')
                   ->orderBy('o.date', 'ASC')
                   ->setParameter('startDate', $startDate->format('Y-m-d'))
                   ->setParameter('endDate', $startDate->add(new \DateInterval('P7D'))->format('Y-m-d'));
                break;

            case self::TYPE_POPULAR:
                $qb->where('i.entityReference = o.id')
                   ->andWhere('i.entity = :entity')
                   ->join(\Interactions\Entity\Interaction::class, 'i')
                   ->setParameter('entity',get_class($object));

             break;

        }

        $this->setRows($qb->getQuery()->getResult());
        $this->setLimit($limit);
        $this->setType($type);
        $this->setAsObject($asObject);
        $this->setTemplate($template);

        return $this;

    }

    function __toString()
    {

        $view = new ViewModel();
        $view->setTemplate($this->getTemplate());
        $view->setVariables([
            'type' => $this->getType(),
            $this->getAsObject() => $this->getRows(),
            'limit' => $this->getLimit(),
        ]);


        try {
            return (string) $this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($view);

        } catch (\Exception $exception) {
            var_dump($exception);
        }

        return (string)$this->getServiceLocator()->getServiceLocator()->get('ZfcTwigRenderer')->render($view);

    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param $rows
     *
     * @return $this
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return mixed
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
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return $this|string
     */
    public function getAsObject()
    {
        return $this->asObject;
        return $this;
    }

    /**
     * @param string $asObject
     */
    public function setAsObject($asObject)
    {
        $this->asObject = $asObject;
    }

}