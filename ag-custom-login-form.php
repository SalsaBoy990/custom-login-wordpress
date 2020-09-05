<?php

namespace AG\CustomLoginForm;

defined('ABSPATH') or die();

/*
Plugin Name: AG Custom Login Form
Plugin URI: https://github.com/SalsaBoy990/custom-login-form
Description: Add fully customized login/register forms into any page
Version: 1.0
Author: András Gulácsi
Author URI: https://github.com/SalsaBoy990
License: GPLv2 or later
Text Domain: ag-custom-login-form
Domain Path: /languages
*/

// require all requires once
require_once 'requires.php';

use AG\CustomLoginForm\CustomLoginForm as CustomLoginForm;

use AG\CustomLoginForm\Log\KLogger as Klogger;


$ag_custom_login_form_log_file_path = plugin_dir_path(__FILE__) . '/log';

$ag_custom_login_form_log = new KLogger($ag_custom_login_form_log_file_path, KLogger::INFO);

// main class
CustomLoginForm::getInstance();

// we don't need to do anything when deactivation
// register_deactivation_hook(__FILE__, function () {});

register_activation_hook(__FILE__, '\AG\CustomLoginForm\CustomLoginForm::activatePlugin');

// delete options when uninstalling the plugin
register_uninstall_hook(__FILE__, '\AG\CustomLoginForm\CustomLoginForm::uninstallPlugin');
