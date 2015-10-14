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

    public function confirmAction()
    {
        $hash = trim($this->_getParam('hash'));
        if (empty($hash)) {
            $this->_helper->flashMessenger($this->translate->_('member_confirm_link_invalid'));
            $this->redirect(Config::get('routes')->login);
        }

        $list = new Member\Listing();
        $list->setUnpublished(true);
        $list->setCondition('confirmHash = ?', $hash);
        if (count($list) == 0) {
            $this->_helper->flashMessenger($this->translate->_('member_confirm_link_invalid'));
            $this->redirect(Config::get('routes')->login);
        }

        $member = $list->current();
        $member->setPublished(true);
        $member->setConfirmHash(null);
        $member->save();

        $this->_helper->flashMessenger($this->translate->_('member_confirm_success'));
        $this->redirect(Config::get('routes')->login);
    }
}
