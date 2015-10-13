<?php

use Member\Auth\Adapter;
use Pimcore\Model\Object\Member;
use Website\Controller\Action;

class Member_IndexController extends Action
{
    public function indexAction()
    {
        // reachable via http://your.domain/plugin/Member/index/index
        $authAdapter = new Adapter([
            'identityClassname' => '\\Pimcore\\Model\\Object\\Member',
            'identityColumn' => 'email',
            'credentialColumn' => 'password',
            'objectPath' => '/members',
        ]);
        $authAdapter->setIdentity('test@test.pl')->setCredential('test');
        var_dump($authAdapter);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        var_dump('auth is valid? ', $result->isValid());
        var_dump($auth->getIdentity());
    }
}
