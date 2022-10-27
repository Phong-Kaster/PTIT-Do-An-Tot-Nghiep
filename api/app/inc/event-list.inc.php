<?php if (!defined('APP_VERSION')) die("Yo, what's up?"); ?>
<?php 
/**
 * This file is not being used in any place
 * Has been created to take a list of system events
 *
 *
 * @event  plugin.install
 * @desc   Being triggered on new plugin installation
 *         After files has been extracted, DB data has been recorded
 * @param  $Plugin: PluginModel
 *
 * 
 * @event  plugin.activate
 * @desc   Being triggered on plugin activatation
 *         After DB data has been update
 * @param  $Plugin: PluginModel
 *
 * 
 * @event  plugin.deactivate
 * @desc   Being triggered n plugin deactivation
 *         After DB data has been updated
 * @param  $Plugin: PluginModel
 * 
 *
 * @event  plugin.remove
 * @desc   Being triggered on plugin removal
 *         Before files and DB recorded
 * @param  $Plugin: PluginModel
 *
 * 
 * @event  plugin.load
 * @desc   Being triggered on plugin load in App.php
 * @param  $Plugin: PluginModel
 *
 *
 * 
 * @event  navigation.add_menu
 * @desc   Add new regular menu
 * @param \stdClass $Nav Navigation data bject
 * @param \UserModel $$AuthUser Currenntly Authenticated User
 * 
 * @event  navigation.add_special_menu
 * @desc   Add new regular menu
 * @param \stdClass $Nav Navigation data bject
 * @param \UserModel $$AuthUser Currenntly Authenticated User
 *
 * @event  navigation.add_admin_menu
 * @desc   Add new admin menu
 * @param \stdClass $Nav Navigation data bject
 * @param \UserModel $$AuthUser Currenntly Authenticated User
 *
 * 
 *
 * @event  router.map
 * @desc   Being triggered just before route match
 *         Usefull to add custom routes for the plugins
 * @param  string: Name of the global variable for the router 
 *         See: /app/core/App.php:defineController()
 *
 * 
 * @event  cron.add
 * @desc   Add new cron task
 *
 * 
 * @event  package.add_module_option
 * @desc   Adds the module as a package option
 *         
 *         This must be considered Module controllers
 *         to enable-disable user access to the module
 *         
 *         This will add same option to user and package settings
 * @param array $package_modules An array of currently enabled modules of the package (or User)
 *
 *
 *
 * @event  renew.add_custom_payment_gateways
 * @desc   Add a new payment gateway to the renew page
 *
 *
 *
 * @event  user.signup
 * @desc   Event is being fired when new user registers
 * @param  \UserModel $User New User model data
 *
 *
 * @event  user.signin
 * @desc   Event is being fired when someone logs into the system successfully
 * @param  \UserModel $User User model data
 *
 *
 * @event  user.signout
 * @desc   Event is being fired when someone logs out of system successfully
 * @param  \UserModel $User User model data
 *
 *
  @event  theme.load
 * @desc  After active theme loaded
 */

