<?php

use Member\Auth\Adapter;
use Pimcore\Model\Object\Member;
use Website\Controller\Action;

class Member_IndexController extends Action
{
    public function indexAction()
    {
        $list = new Member\Listing();
        $list->addConditionParam('email = ?', 'test@test.pl');
        $list->addConditionParam('password = ?', sha1('test'));
//        var_dump($list);
        var_dump($list->count());
//        exit;

        // reachable via http://your.domain/plugin/Member/index/index
        $authAdapter = new Adapter([
            'identityClassname' => '\\Pimcore\\Model\\Object\\Member',
            'identityColumn' => 'email',
            'credentialColumn' => 'password',
            'objectPath' => '/members'
        ]);
        $authAdapter->setIdentity('test@test.pl')->setCredential('test');
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        var_dump('auth is valid? ', $result->isValid());
    }
}
