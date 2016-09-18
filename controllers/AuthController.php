<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Member\Plugin\Config;
use Pimcore\Model\Object\Member;

class Member_AuthController extends Action
{
    public function loginAction()
    {
        if ($this->_helper->member()) {
            $this->redirect(Config::get('routes')->profile);
        }

        if ($this->_request->isPost()) {
            $identity = trim($this->_getParam('email'));
            $password = $this->_getParam('password');
            if (empty($identity) || empty($password)) {
                $this->view->error = $this->translate->_('Wrong email or password');
                return;
            }

            $result = \Member::login($identity, $password);

            if ($result->isValid()) {
                // TODO handle "remember me"

                if ($this->_getParam('back')) {
                    $this->redirect($this->_getParam('back'));
                }
                $this->redirect(Config::get('routes')->profile);
            }

            switch ($result->getCode()) {
                case \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                case \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    $error = $this->translate->_('Wrong email or password');
                    break;
                default:
                    $error = $this->translate->_('Unexpected error occurred');
                    break;
            }
            $this->view->error = $error;
        }
    }

    public function logoutAction()
    {
        $this->auth->clearIdentity();
        $this->redirect(Config::get('routes')->login);
    }

    public function passwordRequestAction()
    {
        if ($this->_helper->member()) {
            $this->redirect(Config::get('routes')->profile);
        }

        if ($this->_request->isPost()) {
            $email = trim($this->_request->getPost('email'));

            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->view->error = $this->translate->_('member_password_request_email_invalid');
                return;
            }

            // TODO resend confirmation email if account is not active

            $list = \Member::getByEmail($email);
            if (count($list) == 0) {
                $this->view->error = $this->translate->_('member_password_request_email_not_exist');
                return;
            }

            /** @var \Member $member */
            $member = $list->current();
            $member->requestPasswordReset();

            $this->_helper->flashMessenger([
                'type' => 'success',
                'text' => $this->translate->_('member_password_request_success'),
            ]);
            $this->redirect(Config::get('routes')->login);
        }
    }

    public function passwordResetAction()
    {
        if ($this->_helper->member()) {
            $this->redirect(Config::get('routes')->profile);
        }

        $hash = trim($this->_getParam('hash'));
        if (empty($hash)) {
            $this->_helper->flashMessenger([
                'type' => 'danger',
                'text' => $this->translate->_('member_password_reset_link_invalid'),
            ]);
            $this->redirect(Config::get('routes')->login);
        }

        $list = new Member\Listing();
        $list->setUnpublished(true);
        $list->setCondition('resetHash = ?', $hash);
        if (count($list) == 0) {
            $this->_helper->flashMessenger([
                'type' => 'danger',
                'text' => $this->translate->_('member_password_reset_link_invalid')
            ]);
            $this->redirect(Config::get('routes')->login);
        }

        if ($this->_request->isPost()) {
            $post = $this->_request->getPost();
            /** @var \Member $member */
            $member = $list->current();
            $result = $member->resetPassword($post);

            if ($result->isValid()) {
                $this->_helper->flashMessenger([
                    'type' => 'success',
                    'text' => $this->translate->_('member_password_reset_success')
                ]);
                $this->redirect(Config::get('routes')->login);
            }

            $this->view->errors = $result->getMessages();
        }
    }

    public function snippetAction()
    {
        if ($this->editmode) {
            $this->disableLayout();
        }
    }
}
