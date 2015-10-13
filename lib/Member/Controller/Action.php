<?php

namespace Member\Controller;

use Website\Controller\Action as WebsiteAction;

class Action extends WebsiteAction
{
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
    }
}
