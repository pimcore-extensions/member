<?php

use Website\Controller\Action;

class Member_IndexController extends Action
{
    public function indexAction()
    {
        $member = new Member();
        // reachable via http://your.domain/plugin/Member/index/index
    }
}
