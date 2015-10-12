<?php

namespace Member\Auth;

class Adapter implements \Zend_Auth_Adapter_Interface
{
    protected $identityClassname;
    protected $identityColumn;
    protected $credentialColumn;
    protected $objectPath;

    /**
     * Identity value
     *
     * @var string
     */
    protected $identity = null;

    /**
     * Credential values
     *
     * @var string
     */
    protected $credential = null;

    /**
     * Constructor
     *
     * @param  array $config Configuration settings example:
     *    'identityClassname' => '\\Pimcore\\Model\\Object\\Member'
     *    'identityColumn' => 'email'
     *    'credentialColumn' => 'password'
     *    'objectPath' => '/members'
     * @throws \Zend_Auth_Adapter_Exception
     */
    public function __construct(array $config)
    {
        $options = ['identityClassname', 'identityColumn', 'credentialColumn', 'objectPath'];
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return Adapter
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param string $credential
     * @return Adapter
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws \Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return \Zend_Auth_Result
     */
    public function authenticate()
    {
        $code = \Zend_Auth_Result::FAILURE;
        $identity = null;
        $messages = [];

        return new \Zend_Auth_Result($code, $identity, $messages);
    }
}
