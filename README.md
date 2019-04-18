# In2it Demo Application

This is a demo [Zend Framework](http://framework.zend.com) application used soley for educational and training purposes. If interested in our [training courses](http://www.in2it.be/php-consulting/php-training-courses), visit our web site for more details.

## Installation

The installation of this demo is straightforward:

### 1. Clone this project from GitHub

First you need to clone this project from GitHub. Ensure you use the `--recursive` parameter so it includes our own libray components used within this demo application.

    $ git clone --recursive https://github.com/in2it/zfdemo.git

### 2. Use composer for other libraries

We've set up [Composer](http://getcomposer.org) to retrieve Zend Framework source code and our generator tool [Faker](https://github.com/fzaninotto/Faker).

If you don't have composer yourself, you can fetch it easily yourself.

**Using cURL**:

    $ curl -sS https://getcomposer.org/installer | php

**Using PHP**:

    php -r "readfile('https://getcomposer.org/installer');" | php

Getting the libraries:

    $ php composer.phar install

This will install the remaining libraries used in this project.

**NOTE:** Since both Zend Framework and In2it's library are in `vendor/` directory, we need to set a symlink to the correct paths:

    $ cd /path/to/zfdemo
    $ cd library
    $ ln -s ../In2it/library/In2it In2it
    $ ln -s ../zendframework/zendframework1/library/Zend Zend

### 3. Set up a virtual host

If you're using PHP 5.4 or higher, you can simple use the build-in PHP server

    $ cd /path/to/zfdemo
    $ APPLICATION_ENV=development php -S 0.0.0.0:1234 -t public public/index.php

If you're using PHP 5.3 or lower, best would be to set up a virtual host for your web server.

Something like the following should be sufficient for [Apache](http://httpd.apache.org).

    <VirtualHost *:80>
       DocumentRoot "/srv/www/zfdemo/public"
       ServerName demo.local
    
       # This should be omitted in the production environment
       SetEnv APPLICATION_ENV development
    
       <Directory "/srv/www/zfdemo/public">
           Options Indexes MultiViews FollowSymLinks
           AllowOverride All
           Order allow,deny
           Allow from all
       </Directory>
    
    </VirtualHost>

### 4. File Permissions

In directory `application/data/` we store our local SQLite database `demo.db`. If you want to have your web server being able to write to this, you need to modify the directory and the file to the group of your web browser.

Example: your web server's group is www-data, than you need to adjust the directory to the following:

    $ cd /path/to/zfdemo
    $ sudo chgrp -R www-data application/data

## Implemented modules

For the example of this application we have created two modules "product" and "user", where the first takes care of all product related things, and the latter handles users.

### Setting up REST access

The goal is to implement a RESTful access to the user and product modules, but we want to configure it through our modules and not through an application configuration.

#### 1. We need to add two "resource" plugins

A resource plugin allows you to define a resource yourself, something that can be loaded at bootstrap and not on call (like a controller action plugin). We want our routes to be loaded at bootstrap, but we want each module to define routing details by itself (so progressive API building can be done).

First "Modulesetup" plugin allows us to define configurations per module by using a `configs/module.ini` per module.
Second "Rest" plugin allows us to load any routing defined in our `configs/module.ini` and add them to the default routing table.

#### 2. We need to add our module routing

In our "user" module, we have created a new `configs/module.ini` configuration file and we add the following in it:

    [production]
        
    resources.rest.modules[] = user
    
    resources.rest.routes.user-collection.route = "#^v1/user$#"
    resources.rest.routes.user-collection.module = "user"
    resources.rest.routes.user-collection.controller = "user-collection"
    
    resources.rest.routes.user-entity.route = "#^v1/user/(?P<id>[0-9]+)$#"
    resources.rest.routes.user-entity.module = "user"
    resources.rest.routes.user-entity.controller = "user-entity"
    
    [staging: production]
    [testing: production]
    [development: production]

#### 3. We need 2 new controllers

Using `Zend_Tool` it's easy to create 2 new controllers for our user module.

    $ zf create controller userCollection 1 user
    $ zf create controller userEntity 1 user

In each controller we need to have "get", "post", "put", "delete" and "head" actions, so again we use `Zend_Tool`:

    $ zf create action get userCollection 1 user
    $ zf create action post userCollection 1 user
    $ zf create action put userCollection 1 user
    $ zf create action delete userCollection 1 user
    $ zf create action head userCollection 1 user
    
    $ zf create action get userEntity 1 user
    $ zf create action post userEntity 1 user
    $ zf create action put userEntity 1 user
    $ zf create action delete userEntity 1 user
    $ zf create action head userEntity 1 user

#### 4. Change controllers to Zend_Rest_Controller

Now our controllers are extending `Zend_Controller_Action` but we need to convert them into `Zend_Rest_Controller`. So we change them.

Also provide functionality in the get action of collection and entity controller to retrieve data from the databases. Don't forget that we've build services to facilitate these operations.

#### 5. Testing it

Point your browser to [zfdemo.local/user](http://zfdemo.local/user) and [zfdemo.local/user/1](http://zfdemo.local/user/1) to see if we can fetch our collections.

#### 6. Your turn

Extend this functionality so you can provide easy REST CRUD functionality on our product and user data.

## Bootstrapping in Apigility

Now that we have tasted of building REST API's, we can continue building REST constructs, but it would make more sense to use proper tools to build REST interfaces using existing code base.

In our example here we're using [Apigility](http://apigility.org) as this is a full REST management tool based on [Zend Framework 2](http://framework.zend.com).

The challenge here is to set up Apigility in such a way that it "bootstraps" this demo application so all models and services build in this application are autoloaded, without actually running this ZF1 application.

To define our goals, we need to address the following challenges:

* Ensure there's no conflict in **APPLICATION_PATH** and **APPLICATION_ENV** between ZF1 and ZF2
* Ensure classes are loaded from ZF1 into ZF2

## Copyright and warranties

[In2it](http://www.in2it.be) provides this code "as-is" and provides no warranties. **THIS IS NOT PRODUCTION CODE!!!**.

This work is licensed under a [Creative Commons Attribution-ShareAlike 4.0 International License](http://creativecommons.org/licenses/by-sa/4.0/).