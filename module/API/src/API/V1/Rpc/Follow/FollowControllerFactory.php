<?php
namespace API\V1\Rpc\Follow;

class FollowControllerFactory
{
    public function __invoke($controllers)
    {
        return new FollowController();
    }
}
