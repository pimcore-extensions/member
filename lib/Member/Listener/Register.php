<?php

namespace Member\Listener;

use Member\Plugin\Config;
use Pimcore\Mail;
use Pimcore\Model\Document\Email;

class Register
{
    /**
     * Default register validation.
     *
     * You can provide your own validation by attaching callback to
     * 'member.register.validate' event.
     *
     * @param \Zend_EventManager_Event $event
     * @return \Zend_Filter_Input
     */
    public static function validate(\Zend_EventManager_Event $event)
    {
        $data = $event->getParam('data');
        $input = new \Zend_Filter_Input([
            '*' => ['StringTrim', 'StripTags']
        ], [
            'firstname' => [
                'NotEmpty',
                'presence' => 'required',
            ],
            'lastname' => [
                'NotEmpty',
                'presence' => 'required',
            ],
            'email' => [
                'EmailAddress',
                'EmailExist',
                'presence' => 'required',
            ],
            'agree' => [
                new \Zend_Validate_Identical('1'),
                'presence' => 'required',
            ],
            'password' => [
                new \Zend_Validate_StringLength(6),
                'presence' => 'required',
            ],
            'password_confirm' => [
                new \Zend_Validate_Callback(function ($v) use ($data) {
                    return $v === $data['password'];
                }),
                'presence' => 'required',
                'messages' => 'Password do not match'
            ],
        ], $data, [
            \Zend_Filter_Input::VALIDATOR_NAMESPACE => 'Member_Validate',
        ]);

        return $input;
    }

    /**
     * Callback for 'member.register.post' event.
     * It simply activates member account after registration.
     *
     * @param \Zend_EventManager_Event $event
     * @return \Member
     * @throws \Exception
     */
    public static function activate(\Zend_EventManager_Event $event)
    {
        /** @var \Member $member */
        $member = $event->getTarget();
        $member->setPublished(true);
        $member->save();

        return $member;
    }

    public static function confirm(\Zend_EventManager_Event $event)
    {
        /** @var \Member $member */
        $member = $event->getTarget();
        $member->setConfirmHash($member->createHash());
        $member->save();

        $doc = Email::getByPath(Config::get('emails')->registerConfirm);
        if (!$doc) {
            throw new \Exception('No confirmation email defined');
        }

        $email = new Mail();
        $email->addTo($member->getEmail());
        $email->setDocument($doc);
        $email->setParams([
            'member_id' => $member->getId(),
        ]);
        $email->send();

        return $member;
    }
}
