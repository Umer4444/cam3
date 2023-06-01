<?php

namespace Application\Widgets\TimeLine\Plugins;


interface PluginInterface
{

    /**
     * Returns the name
     *
     * @return string
     */
    function getName();

    /**
     * Will execute the aggregator and register the result of the process
     *
     * @return bool
     */
    function run();

    /**
     * Will execute the flow
     *
     * @return mixed
     */
    function execute();

    /**
     * Will return the results
     *
     * @return mixed
     */
    function getResult();

    /**
     * Will set the result from the run process
     *
     * @param $result
     *
     * @return mixed
     */
    function setResult($result);

    /**
     * @param \Application\Entity\User $user
     * @return mixed
     */
    public function setUser($user);

    /**
     * Returns the user
     *
     * @return \Application\Entity\User
     */
    function getUser();

}