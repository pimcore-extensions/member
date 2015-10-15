# Frontend user extension for [pimcore](http://www.pimcore.org/)

**NOTE:** This extension is under development. **Most of the features are not working yet.**  
If you need frontend user in your project you can give it a try. But don't expect to much for now ;)  
Fork, hack & have fun! **Pull requests are more than welcome!** :)

## Features

* Member accounts based on pimcore's data objects
* Base user functionality - register, login, logout, remind password
* Highly customizable by config and event hooks
* Social sign-in thanks to [HybridAuth](https://github.com/hybridauth/hybridauth)

## Setup

@TODO

## Configuration

@TODO

## Documentation

### Controller helper

* Get current logged member object (null if not logged):  
    ```php
    $this->_helper->member()
    ```

* Force login in controller's action:
    ```php
    $this->_helper->member->requireAuth();
    ```
    This will automatically redirect user to configured login page.

### Event API

Events can be used to hook into many functionalities. For more information about using Event API
please check [pimcore documentation](https://www.pimcore.org/wiki/pages/viewpage.action?pageId=14551652).

* ```member.register.validate``` - allows to override validation of register form data.  
    Your callback must return configured instance of ```\Zend_Filter_Input```.  
    See ```\Member\Listener\Register::validate()``` for default implementation.
* ```member.register.post``` - allows to define what should be done after member object was created. 
    By default member object is published - which means that account is active and member
    is able to login.  
    There is also ```confirm``` callback implemented which sends confirmation link via email.  
    If you remove ```postRegister``` action from ```config.xml``` members must be activated by admin.
* ```member.password.reset``` - allows to override validation of password reset form data.  
    Your callback must return configured instance of ```\Zend_Filter_Input```.  
    See ```\Member\Listener\Password::reset()``` for default implementation.
    
