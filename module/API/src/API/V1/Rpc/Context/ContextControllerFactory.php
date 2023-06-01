<?php
namespace API\V1\Rpc\Context;

class ContextControllerFactory
{
    public function __invoke($controllers)
    {
        return new ContextController();
    }
}
