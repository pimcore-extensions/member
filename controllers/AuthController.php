<?php

use Member\Auth\Adapter;
use Member\Controller\Action;
use Pimcore\Model\Object\Member;

class Member_AuthController extends Action
{
    public function loginAction()
    {
//        $this->view->formAction = ($this->document)
//            ? $this->document->getFullPath()
//            : $this->_helper->url([]);
//
//        // reachable via http://your.domain/plugin/Member/index/index
//        $authAdapter = new Adapter([
//            'identityClassname' => '\\Pimcore\\Model\\Object\\Member',
//            'identityColumn' => 'email',
//            'credentialColumn' => 'password',
//            'objectPath' => '/members',
//        ]);
//        $authAdapter->setIdentity('test@test.pl')->setCredential('test');
//
//        $auth = Zend_Auth::getInstance();
//        $result = $auth->authenticate($authAdapter);
//        var_dump('auth is valid? ', $result->isValid());
//        var_dump($auth->getIdentity());
    }
}
