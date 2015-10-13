<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Pimcore\Model\Object\Member;

class Member_AuthController extends Action
{
    public function loginAction()
    {
        if ($this->auth->hasIdentity()) {
            // TODO routing configuration
            $this->redirect('/member');
        }

        $this->view->formAction = ($this->document)
            ? $this->document->getFullPath()
            : $this->_helper->url->url();

        if ($this->_request->isPost()) {
            // TODO plugin configuration + management
            $adapter = new Adapter([
                'identityClassname' => '\\Pimcore\\Model\\Object\\Member',
                'identityColumn' => 'email',
                'credentialColumn' => 'password',
                'objectPath' => '/members',
            ]);
            $adapter
                ->setIdentity($this->_getParam('email'))
                ->setCredential($this->_getParam('password'));

            $result = $this->auth->authenticate($adapter);

            if ($result->isValid()) {
                // TODO handle "remember me"
                // TODO routing configuration
                $this->redirect('/member');
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
        // TODO routing configuration
        $this->redirect('/member/login');
    }
}
