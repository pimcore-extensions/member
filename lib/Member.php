<?php

use Member\Auth\Adapter;
use Member\Plugin\Config;
use Pimcore\Mail;
use Pimcore\Model\Document\Email;
use Pimcore\Model\Object\Folder;
use Pimcore\Model\Object\Member as AbstractMember;

class Member extends AbstractMember
{
    /**
     * @param array $data
     * @return \Zend_Filter_Input
     * @throws \Exception
     */
    public function register(array $data)
    {
        $argv = compact('data');
        $results = \Pimcore::getEventManager()->triggerUntil('member.register.validate',
            $this, $argv, function ($v) {
                return ($v instanceof \Zend_Filter_Input);
            });

        $input = $results->last();
        if (!$input instanceof \Zend_Filter_Input) {
            throw new \Exception('No validate listener attached to "member.register.validate" event');
        }

        if (!$input->isValid()) {
            return $input;
        }

        try {
            $this->setValues($input->getUnescaped());
            $this->setRole($this->getClass()->getFieldDefinition('role')->getDefaultValue());
            $this->setKey(str_replace('@', '_at_', $this->getEmail()));
            $this->setParent(Folder::getByPath(
                '/' . ltrim(Config::get('auth')->adapter->objectPath, '/')
            ));
            $this->save();

            \Pimcore::getEventManager()->trigger('member.register.post', $this);
        } catch (\Exception $e) {
            if ($this->getId()) {
                $this->delete();
            }
            throw $e;
        }

        return $input;
    }

    public static function login($identity, $password)
    {
        $adapter = new Adapter(Config::get('auth')->adapter);
        $adapter
            ->setIdentity($identity)
            ->setCredential($password);

        return \Zend_Auth::getInstance()->authenticate($adapter);
    }

    public function createHash($algo = 'md5')
    {
        return hash($algo, $this->getId() . $this->getEmail() . mt_rand());
    }

    /**
     * @return $this
     */
    public function confirm()
    {
        $this->setPublished(true);
        $this->setConfirmHash(null);
        $this->save();

        return $this;
    }

    public function requestPasswordReset()
    {
        $this->setResetHash($this->createHash());
        $this->save();

        $doc = Email::getByPath(Config::get('emails')->passwordReset);
        if (!$doc) {
            throw new \Exception('No password reset email defined');
        }

        /** @var \Zend_Controller_Request_Http $request */
        $request = \Zend_Controller_Front::getInstance()->getRequest();

        $email = new Mail();
        $email->addTo($this->getEmail());
        $email->setDocument($doc);
        $email->setParams([
            'host' => sprintf('%s://%s', $request->getScheme(), $request->getHttpHost()),
            'member_id' => $this->getId(),
        ]);
        $email->send();

        return $this;
    }

    public function resetPassword(array $data)
    {
        $argv = compact('data');
        $results = \Pimcore::getEventManager()->triggerUntil('member.password.reset',
            $this, $argv, function ($v) {
                return ($v instanceof \Zend_Filter_Input);
            });

        $input = $results->last();
        if (!$input instanceof \Zend_Filter_Input) {
            throw new \Exception('No validate listener attached to "member.password.reset" event');
        }

        if (!$input->isValid()) {
            return $input;
        }

        $this->setPassword($input->getUnescaped('password'));
        $this->setResetHash(null);
        $this->save();

        if (!$this->isPublished()) {
            // password reset is confirmed by email so we can activate account as well
            $this->confirm();
        }

        return $input;
    }
}
