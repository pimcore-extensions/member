<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Pimcore\Model\Object\Member;

class Member_ProfileController extends Action
{
    public function defaultAction()
    {
        $this->_helper->member->requireAuth();

        $this->view->member = $this->auth->getIdentity();
    }

    public function registerAction()
    {
    }
}
