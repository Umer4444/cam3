<?php
/**
 * Created by PhpStorm.
 * User: userws5
 * Date: 02.03.2015
 * Time: 14:56
 */

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class TopTippersOrGifters extends AbstractHelper implements ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    const TIPPERS = 'Tippers';
    const GIFTERS = 'Gifters';

    protected $id;
    protected $type;

    public function __invoke($id = null)
    {
        $this->setId($id);
        return is_null($this->getId()) ? false : $this;
    }

    public function getTippers() {

        $this->setType(self::TIPPERS);
        return $this->getString();

    }

    public function getGifters() {

        $this->setType(self::GIFTERS);
        return $this->getString();

    }

    protected function getString() {

        $top = array();
        $entityManager = $this->getServiceLocator()->getServiceLocator()
            ->get('doctrine.entity_manager.orm_default');

        if ($this->getType() == self::TIPPERS) {

            $top = $entityManager->getRepository('Application\Entity\Chips')
                ->findBy(
                    array('idReceiver' => $this->getId()),
                    array('amount'=> 'ASC'),
                    5,
                    0
                );

        } elseif ($this->getType() == self::GIFTERS) {

            $top = $entityManager->getRepository('Application\Entity\PledgeFunder')
                ->findBy(
                    array('referenceId' => $this->getId()),
                    array('amount'=> 'ASC'),
                    5,
                    0
                );

        }
        $payersNo = count($top);

        $string = '<div class="row">';
        $divStart = '<div class="col-md-6">';
        $divEnd = '</div>';

        if ($payersNo > 1) {

            $i = 1;

            foreach($top as $gifter) {
                $string .= $divStart;
                $string .= $i++ . ' - ' . $gifter['sender']->getUsername() . ' - ' . $gifter['total'] .' chips';

                $string .= $divEnd;
            }

        } elseif ($payersNo == 1) {

            $string .= $divStart . $top[0]['sender']->getUsername() . $divEnd;
        } else {
            $string .= $divStart . 'No '. $this->getType() . $divEnd;
        }

        $string .= '</div>';

        return $string;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


} 