<?php

namespace Member\Controller;

use Member;
use Website\Controller\Action as WebsiteAction;

class Action extends WebsiteAction
{
    /**
     * @var \Zend_Auth
     */
    protected $auth;

    /**
     * @var \Pimcore\Translate\Website
     */
    protected $translate;

    public function init()
    {
        parent::init();

        $this->enableLayout();

        // allow to override plugin views by website
        $this->view->setScriptPath(
            array_merge(
                $this->view->getScriptPaths(),
                array(
                    PIMCORE_WEBSITE_PATH . '/views/scripts/',
                    PIMCORE_WEBSITE_PATH . '/views/layouts/',
                    PIMCORE_WEBSITE_PATH . '/views/scripts/member/'
                )
            )
        );

        $this->auth = \Zend_Auth::getInstance();
        $this->translate = $this->initTranslation();
    }
}
