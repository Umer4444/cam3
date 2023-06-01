<?php
namespace API\V1\Rpc\Resources;

class ResourcesControllerFactory
{
    public function __invoke($controllers)
    {
        return new ResourcesController();
    }
}