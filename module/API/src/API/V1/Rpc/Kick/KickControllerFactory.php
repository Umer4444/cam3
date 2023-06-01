<?php
namespace API\V1\Rpc\Kick;

class KickControllerFactory
{
    public function __invoke($controllers)
    {
        return new KickController();
    }
}
