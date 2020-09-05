<?php

namespace AG\CustomLoginForm\Form;

defined('ABSPATH') or die();


use AG\CustomLoginForm\Validate\Validate as Validate;

/**
 * class for login form
 */
class Login
{
    private $validate;

    public function __construct()
    {
        $this->validate = new Validate();
    }
    public function __destruct()
    {
    }


    // user registration login form
    public function loginForm()
    {

        // only show the login form to non-logged-in members
        if (!is_user_logged_in()) {

            global $ag_custom_login_load_css;

            // set this to true so the CSS is loaded
            $ag_custom_login_load_css = true;

            $output = $this->loginFormFields();
        } else {

            $currUser = wp_get_current_user();
            // could show some logged in user info here

            $output = 'Helló, ' . esc_html($currUser->user_firstname) . '!<br / >';
            $output .= '<a href="' . wp_logout_url( home_url() ) . '">Kijelentkezés</a>';
        }

        return $output;
    }

    // login form fields
    public function loginFormFields()
    {
        // use nonce for security
        $agLoginFormNonce = wp_create_nonce('ag-login-3dBshdEsgwywE9a5y');

        ob_start();

        require AG_CUSTOM_LOGIN_FORM_PLUGIN_DIR . '/pages/loginForm.php';

        return ob_get_clean();
    }
}
