# Feeligo SDK for PHP-based social websites

## Key concepts

  * a *Community* is any kind of social website. By _social_ we mean that there is a notion of uniquely-identifiable **Users** *and* of a pairwise Relationship (e.g. Friendship) between Users. That's all we really need.
  
  * at any given time, the logged-in User on a Community is called the *Viewer*. A Viewer corresponds to a single user account that is being used by someone to navigate on the Community.
  
  _On publicly-accessible pages, there may be no defined Viewer because they can be accessed without logged-in._
  
  * when the *Viewer* is viewing the *profile page* of another User, this User is called the *Subject*.
  
  _Note that on some social networking platforms, the concept of "subject" is extended to anything that the Viewer is viewing (such as an Event, a Forum Post, a Message...). For us, the Subject is **always** another User._

## How to adapt the PHP SDK to a given platform

The following assumptions are made:

  * we are developing a plugin/module for a platform called `Riri`
  * the module/plugin being developed is located in `modules/feeligo`
  * the SDK is located in `modules/feeligo/common`


### 1. Adapt the `User` class  

The `FeeligoUser` class represents a User of a Community.

In `modules/feeligo/models/adapter/user.php`

    require ./common/models/adapter/user.php

    class FeeligoRiriUser extends FeeligoUser {
      
      /* constructor stores a reference to the adapted User
       */
      public function __construct($user) {
        // TODO: you need to pass the user's ID to the parent constructor
        // change this to use the GET_ID method of your platform
        parent::__construct($user, $user->GET_ID());
        
        // you can access the adapted $user by calling $this->user() from now on
      }
      
      /* TODO: returns the $user's ID */
      public function id();
      
      /* TODO: returns the $user's name */
      public function name();
      
      /* TODO: returns the $user's username */
      public function username();
      
      /* TODO: returns the URL of the $user's profile page */
      public function link();
      
      /* TODO: returns the URL of the $user's profile picture */
      public function picture_url();
      
      /* TODO: returns a FeeligoUserSelector which
       * allows to access the $user's friends
       * (for the moment, return null)
       */
      public function selector_friends();
  
    }

  
### 2. Adapt the `FeeligoCommunity` class

The `FeeligoCommunity` class represents a Community. It provides accessors to the Users and other objects of the Community.

In `modules/feeligo/models/community.php`

    require ./common/models/community.php

    class FeeligoRiriCommunity extends FeeligoCommunity {
      
      /* TODO: returns a FeeligoRiriAdapterUser
       * adapting your platform's user instance for the Viewer
       */
      public function viewer();
      
      /* TODO: returns a FeeligoRiriAdapterUser
       * adapting your platform's user instance for the Viewer
       */
      public function subject();
  
      /* TODO: returns an instance of FeeligoRiriSelectorUsers
       * which will allow to access the users of the Community
       * (for the moment, return null)
       */
      public function users();
    }


### 3. Adapt the `Api` class

The `Api` class contains all API-specific settings (server URL, API key, secret key). It also provides an accessor for the `Community` object.

In `modules/feeligo/api.php`

    require ./common/api.php
    require ./models/community.php
    
    class FeeligoRiriApi extends FeeligoApi {
      
      /* constructor
       * should store an instance of FeeligoRiriCommunity
       * to be returned when the Community is requested
       */
      public function __construct() {
        parent::__construct();
        $this->_community = new FeeligoRiriCommunity();
      }
      
      /* TODO: returns the FeeligoRiriCommunity
       */
      public function community();
      
      /* TODO: returns whether the Viewer is defined
       */
      public function has_viewer();
      
      /* TODO: returns whether the Subject is defined
       */
      public function has_subject();
      
      /* TODO: include this function as-is
       * (Singleton pattern)
       */
      public static function _() {
        if( is_null(self::$_instance) ) {
          self::$_instance = new self();
        }
        return self::$_instance;
      }
    }
    
The `Api` class implements the Singleton pattern: there is always one single instance of the Api, which can be obtained by doing `$api = FeeligoRiriApi::_();`


### 4. Create a model for the `Giftbar` application

This class just allows to call all Giftbar-specific methods from the same place.

In `modules/feeligo/apps/giftbar.php`

    require ./api.php
    require ./common/apps/giftbar.php
    
    class FeeligoRiriAppGiftbar extends FeeligoAppGiftbar {
      
      /* accessor for the API singleton instance */
      public function api() {
        // TODO: make sure to call the ::_() method of your Feeligo###Api class
        return FeeligoRiriApi::_();
      }
      
    }
    
That's all you need : the following methods are already provided by the extended `FeeligoAppGiftbar` class:

  * `should_be_displayed()` returns `true|false` depending on whether the Giftbar should be displayed
  * `app_loader_js_url()` returns the URL to the Giftbar's JS file
  * `app_stylesheet_url($version = null)` returns the URL to a given CSS file (pass `$version = 'ie7'` for IE7 CSS)
  * `startup_js_code()` returns a string containing the Javascript code that the Giftbar expects to find on a web page when it loads.
  
You can indeed override these methods in your implementation of `FeeligoRiriAppGiftbar` if necessary.


### 5. Inject the Giftbar's code in your HTML views

This heavily depends on your platform's implementation. What we want to achieve is the following:

    <?php
    // get an instance of the FeeligoRiriAppGiftbar class
    $giftbar = new FeeligoRiriAppGiftbar();
    ?>

    <head>
      [...]
  
      <?php if($giftbar->should_be_displayed()): ?>
        <!-- links for the Giftbar's CSS -->
        <link href="<?php echo $giftbar->app_stylesheet_url();?>" media="screen" rel="stylesheet" type="text/css" />
        <!--[if lt IE 7]> 
          <link href="<?php echo $giftbar->app_stylesheet_url('ie7');?>" media="screen" rel="stylesheet" type="text/css" />
        <![endif]-->
  
        <!-- JS code for the Giftbar -->
        <script type="text/javascript">
          <?php echo $giftbar->startup_js_code(); ?>
        </script>
      <?php endif; ?>
    </head>

    <body>
      [...]
  
      <?php if($giftbar->should_be_displayed()): ?>
        <!-- tag which loads the Giftbar's remote JS file -->
        <script type="text/javascript" src="<?php echo $giftbar->app_loader_js_url();?>"></script>
        <!-- div where the Giftbar's HTML will be inserted -->
        <div id="flg_giftbar_container" class="flg"></div>
      <?php endif; ?>
    </body>

It is recommended to keep the `<script>` tag pointing to the `app_loader_js_url` as close to the end of the `<body>` as possible. This ensures that the Giftbar's JS loading is non-blocking and will not begin until the HTML and CSS are fully loaded.


### 6. Adapt the `Users` class

### 7. Adapt the `UserFriends` class

