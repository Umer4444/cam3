<?php

namespace Interactions\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Interactions\InteractionInterface;

class Interaction extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $object;

    protected $options = array();

    protected $template;

    public function __invoke($object, $identity = null, $options = array())
    {

        if (is_string($object) && !is_null($identity)) {
            $object = $this->getServiceLocator()
                           ->getServiceLocator()
                           ->get('Doctrine\ORM\EntityManager')
                           ->find($object, $identity);
        }

        if (!is_object($object) || !in_array(\Interactions\InteractionInterface::class, class_implements($object))) {
            throw new \Exception(
                'The entity `'.(get_class($object) ?: $object).
                '` must implement the `'.InteractionInterface::class.'` interface !'
            );
        }

        $this->setOptions(array_merge($this->getOptions(), $options));
        $this->setObject($object);

        return $this;

    }

    function __toString()
    {
        return $this->getServiceLocator()
                    ->getServiceLocator()
                    ->get('ZfcTwigRenderer')
                    ->render($this->getTemplate(), array_merge($this->getOptions(), ['object' => $this->getObject()]));
    }

    function __call($name, $arguments)
    {

        $plugin = $this->getServiceLocator()->get($name);
        if (strpos(get_class($plugin), __NAMESPACE__) !== false) {

            $plugin->setObject($this->getObject());
            $plugin->setOptions($this->getOptions());

            return $plugin;

        }

    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

}