<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Member\Plugin\Config;
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
        if ($this->_helper->member()) {
            $this->redirect(Config::get('routes')->profile);
        }

        if ($this->_request->isPost()) {
            $member = new \Member();
            $result = $member->register($this->_request->getPost());
            var_dump($result->isValid(), $result->getUnescaped(), $result->getMessages());

            if ($result->isValid()) {
                var_dump($member);
                //$this->redirect(Config::get('routes')->login);
            }

            $this->view->assign($result->getEscaped());
            $this->view->errors = $result->getMessages();
        }
    }
}
