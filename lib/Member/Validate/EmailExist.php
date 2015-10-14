<?php

namespace Member\Validate;

use Zend_Validate_Exception;

class EmailExist extends \Zend_Validate_Abstract
{
    const EMAIL_EXIST = 'emailExist';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::EMAIL_EXIST => "Given email address is already registered",
    );

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $list = \Member::getByEmail($value);
        if ($list->count() > 0) {
            $this->_error(self::EMAIL_EXIST);
            return false;
        }

        return true;
    }
}

// unfortunately we need this alias here, since ZF plugin loader isn't able to handle namespaces correctly
class_alias("Member\\Validate\\EmailExist", "Member_Validate_EmailExist");
