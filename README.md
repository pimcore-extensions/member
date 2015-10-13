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
```$this->_helper->member() ```

* Force login in controller's action:  
```$this->_helper->member->requireAuth();```  
This will automatically redirect user to configured login page.
