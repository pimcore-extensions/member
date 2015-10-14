<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Member\Plugin\Config;
use Pimcore\Model\Object\Member;

class Member_AuthController extends Action
{
    public function loginAction()
    {
        if ($this->auth->hasIdentity()) {
            $this->redirect(Config::get('routes')->profile);
        }

        if ($this->_request->isPost()) {
            $adapter = new Adapter(Config::get('auth')->adapter);
            $adapter
                ->setIdentity($this->_getParam('email'))
                ->setCredential($this->_getParam('password'));

            $result = $this->auth->authenticate($adapter);

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

    public function remindAction()
    {
    }
}
