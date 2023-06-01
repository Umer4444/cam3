<?php
namespace Application\Widgets\TimeLine;

use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;
use Application\Widgets\TimeLine\Plugins;
use Zend\ServiceManager;

class TimeLineAggregator extends ViewModel implements ServiceManager\ServiceLocatorAwareInterface
{
    use Traits\User;
    use Traits\Page;
    use ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @var array $results
     */
    protected $results = array();

    /**
     * @var array
     */
    protected $plugins = array();

    /**
     * @var array
     */
    protected $userPlugins = array();

    public function __construct()
    {
        call_user_func_array('parent::__construct', func_get_args());

        $this->setPlugins([
            new Plugins\Transfers(),
            new Plugins\SentTransfers(),
            new Plugins\Blogs(),
        ]);

    }

    /**
     * @return mixed
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param array $plugins
     */
    public function setPlugins(array $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * @param mixed $plugin
     */
    public function addPlugin(Plugins\PluginInterface $plugin)
    {
        $this->plugins[$plugin->getName()] = $plugin;
    }

    /**
     * @param mixed $plugin
     */
    public function removePlugin(Plugins\PluginInterface $plugin)
    {
        unset($this->plugins[$plugin->getName()]);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        krsort($this->results);
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults(array $results)
    {
        $this->results = $results;
    }

    public function addResult($result)
    {
        $this->results = $this->results + $result;
    }

    public function aggregateData($user = null, $filter = null, $page = null)
    {

        if (!is_null($page)) {
            $this->setPage($page);
        }

        if (!is_null($user)) {
            $this->setUser($user);
        }

        if (!count($this->getPlugins())) {
            throw new \LogicException('No plugins registered');
        }

        if ($filter) {

            $class = 'Application\\Widgets\\TimeLine\\Plugins\\'.$filter;
            $this->setPlugins([
                new $class,

            ]);
        }

        /** @var $plugin \Application\Widgets\TimeLine\Plugins\AbstractPlugin */
        foreach ($this->getPlugins() as $plugin) {

            $plugin->setServiceLocator($this->getServiceLocator());

            $plugin->setUser($this->getUser());
            $plugin->setPage($this->getPage());

            $results = $plugin->run();

            if (!is_null($results)) {
               $this->addResult($results);
            }

        }

        return $this->setVariables(['results' => $this->getResults()])->setTerminal(true);

    }


}