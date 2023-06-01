<?php

namespace Application\Paginator;

use \Nicovogelaar\Paginator\Paginator;

/**
 * Class FriendsPaginator
 * @package Application\Paginator
 */
class FriendsPaginator extends Paginator
{

    public function init()
    {
        $this->addSorting('id', 'p.id', 'ID')
             ->addSorting('users', 'p.friend_id', 'Friend')
             ->addSorting('userId', 'p.user_id', 'User')
             ->addSorting('status', 'p.status', 'Status')
             ->addFilter('status', 'p.status');
    }

}