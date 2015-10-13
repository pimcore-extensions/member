<?php

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

        if ($input->isValid()) {
            $this->setValues($input->getUnescaped());
            $this->setRole($this->getClass()->getFieldDefinition('role')->getDefaultValue());
            $this->setKey(str_replace('@', '_at_', $this->getEmail()));
            $this->setParent(\Pimcore\Model\Object\Folder::getByPath(
                '/' . ltrim(\Member\Plugin\Config::get('auth')->adapter->objectPath, '/')
            ));
            $this->save();
            \Pimcore::getEventManager()->trigger('member.register.post', $this);
        }

        return $input;
    }
}
