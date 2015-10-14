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
            $post = $this->_request->getPost();
            $member = new \Member();
            $result = $member->register($post);

            if ($result->isValid()) {
                $translationKey = 'member_register_success';
                if (Config::get('actions')->postRegister) {
                    $translationKey .= '_' . Config::get('actions')->postRegister;
                }
                $this->_helper->flashMessenger($this->translate->_($translationKey));
                $this->redirect(Config::get('routes')->login);
            }

            $this->view->assign(array_merge($post, $result->getEscaped()));
            $this->view->errors = $result->getMessages();
        }
    }
}
