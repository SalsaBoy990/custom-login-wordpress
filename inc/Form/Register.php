<?php

namespace AG\CustomLoginForm\Form;

defined('ABSPATH') or die();

use AG\CustomLoginForm\Error\Error as Error;

/**
 * class for registration form
 */
class Register
{
    use Error;

    public function __construct()
    {
    }
    public function __destruct()
    {
    }


    // user registration login form
    public function registrationForm()
    {

        // only show the registration form to non-logged-in members
        if (!is_user_logged_in()) {

            global $ag_custom_login_load_css;

            // set this to true so the CSS is loaded
            $ag_custom_login_load_css = true;

            // check to make sure user registration is enabled
            $registrationEnabled = get_option('users_can_register');

            // only show the registration form if allowed
            if ($registrationEnabled) {
                $output = $this->registrationFormFields();
            } else {
                $output = __('User registration is not enabled');
            }
            return $output;
        }
    }

    // registration form fields
    public function registrationFormFields()
    {
        // use nonce for security
        $agRegisterFormNonce = wp_create_nonce('ag-register-EQzjpycX82yQ3C08Kfe');
        
        ob_start();

        require AG_CUSTOM_LOGIN_FORM_PLUGIN_DIR . '/pages/registerForm.php';

        return ob_get_clean();
    }
}
