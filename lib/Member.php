<?php

use Member\Plugin\Config;
use Pimcore\Model\Object\Folder;
use Pimcore\Model\Object\Member as AbstractMember;

class Member extends AbstractMember
{
    /**
     * @param array $data
     * @return \Zend_Filter_Input
     * @throws Exception
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

    public function createHash($algo = 'md5')
    {
        return hash($algo, $this->getId() . $this->getEmail() . mt_rand());
    }
}
