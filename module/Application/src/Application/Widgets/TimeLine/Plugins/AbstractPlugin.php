<?php

namespace Application\Widgets\TimeLine\Plugins;

use PerfectWeb\Core\Traits;
use Zend\ServiceManager;
use Zend\View\Model\ViewModel;

abstract class AbstractPlugin extends ViewModel implements PluginInterface,  ServiceManager\ServiceLocatorAwareInterface
{

    use Traits\User;
    use Traits\Page;
    use ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this::NAME;
    }

    /**
     * Results
     *
     * @var mixed
     */
    protected $result = null;

    abstract function execute();

    public function __toString() {
        return $this->getName();
    }

    /**
     * {@inheritDoc}
     */
    final function run()
    {

        $results = $this->execute();
        $data = array();

        if (is_null($results)) {
            return false;
        }

        $this->getServiceLocator()->get('ViewHelperManager')->get('cycle')->assign(array('left-aligned',''), 'align');
        foreach ($results as $result) {
            $data[] = $this->getServiceLocator()
                           ->get('ZfcTwigRenderer')
                           ->render(
                               $this->setVariables(['result' => $result])
                                    ->setTemplate($this)
                           );
        }

        $this->setResult($data);

        return $this->getResult();

    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @inheritdoc
     */
    function setResult($result)
    {
        $this->result = $result;
    }

}