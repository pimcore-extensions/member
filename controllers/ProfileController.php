<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Pimcore\Model\Object\Member;

class Member_ProfileController extends Action
{
    public function defaultAction()
    {
        if (!\Zend_Auth::getInstance()->hasIdentity()) {
            // TODO routing configuration
            $this->redirect('/member/login');
        }

        // TODO profile page
    }
}
