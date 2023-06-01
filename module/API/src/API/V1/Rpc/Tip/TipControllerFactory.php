<?php
namespace API\V1\Rpc\Tip;

class TipControllerFactory
{
    public function __invoke($controllers)
    {
        return new TipController();
    }
}
