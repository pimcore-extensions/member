<?php

namespace Member\Controller\Action\Helper;

use Member\Plugin\Config;

class Member extends \Zend_Controller_Action_Helper_Abstract
{
    /**
     * @param bool $fromStorage
     * @return \Member|null
     */
    public function direct($fromStorage = true)
    {
        $identity = \Zend_Auth::getInstance()->getIdentity();
        if ($identity && !$fromStorage) {
            // return real object instead of object cached in auth storage
            $identity = \Member::getById($identity->getId());
        }

        return $identity;
    }

    /**
     * Check if user is logged in.
     * Redirect to login page with return URL.
     */
    public function requireAuth()
    {
        if (!\Zend_Auth::getInstance()->hasIdentity()) {
            $this->getActionController()->redirect(sprintf('%s?back=%s',
                Config::get('routes')->login,
                urlencode($this->getRequest()->getRequestUri())
            ));
        }
    }
}

// unfortunately we need this alias here, since ZF plugin loader isn't able to handle namespaces correctly
class_alias("Member\\Controller\\Action\\Helper\\Member", "Member_Controller_Action_Helper_Member");
