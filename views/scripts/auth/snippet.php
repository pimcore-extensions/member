<?php if (\Zend_Auth::getInstance()->hasIdentity()): ?>
    <?php $member = \Zend_Auth::getInstance()->getIdentity() ?>
    <p>
        <?= $this->translate('you_are_logged_in_as') ?><br>
        <a href="<?= \Member\Plugin\Config::get('routes')->profile ?>">
            <?= $member->getFirstname() ?> <?= $member->getLastname() ?>
        </a>
    </p>
    <p>
        <a href="<?= \Member\Plugin\Config::get('routes')->logout ?>"
           class="btn btn-danger">
            <?= $this->translate('logout') ?>
        </a>
    </p>
<?php else: ?>
    <p><?= $this->translate('do_you_have_an_account?') ?></p>
    <p>
        <a href="<?= \Member\Plugin\Config::get('routes')->login ?>"
           class="btn btn-block btn-success"><?= $this->translate('login') ?></a>
    </p>
    <p><?= $this->translate('not_a_member_yet?') ?></p>
    <p>
        <a href="<?= \Member\Plugin\Config::get('routes')->register ?>"
           class="btn btn-block btn-success"><?= $this->translate('sign_up') ?></a>
    </p>
<?php endif; ?>
