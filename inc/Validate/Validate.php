<?php

namespace AG\CustomLoginForm\Validate;

use AG\CustomLoginForm\Error\Error as Error;

defined('ABSPATH') or die();

/**
 * class for validating form
 */
class Validate
{
    use Error;

    public function __construct()
    {
    }
    public function __destruct()
    {
    }



    /**
     * logs a member in after submitting a form
     * Adopted and modernized from
     * @link: https://pippinsplugins.com/creating-custom-front-end-registration-and-login-forms/
     */

    public function loginMember(): void
    {
        if (
            isset($_POST['ag_user_login']) && $_POST['ag_user_login'] != '' &&
            wp_verify_nonce($_POST['ag_login_nonce'], 'ag-login-3dBshdEsgwywE9a5y')
        ) {
            // this returns the user ID and other info from the user name
            // DEPRECATED: $user = get_userdatabylogin($_POST['ag_user_login']);
            $user = get_user_by('login', $_POST['ag_user_login']);

            if (!$user) {
                // if the user name doesn't exist
                $this->formErrors()->add('empty_username', __('Invalid username'));
            }

            if (!isset($_POST['ag_user_pass']) || $_POST['ag_user_pass'] == '') {
                // if no password was entered
                $this->formErrors()->add('empty_password', __('Please enter a password'));
            }

            // check the user's login with their password
            if (!wp_check_password($_POST['ag_user_pass'], $user->user_pass, $user->ID)) {
                // if the password is incorrect for the specified user
                $this->formErrors()->add('empty_password', __('Incorrect password'));
            }

            // retrieve all error messages
            $errors = $this->formErrors()->get_error_messages();

            // only log the user in if there are no errors
            if (!is_wp_error($user) && empty($errors)) {

                // check if remember me option is checked
                if (checked($_POST['ag_remember_me'], 'forever')) {
                    $rememberMe = 1;
                } else {
                    $rememberMe = 0;
                }

                // DEPRECATED: wp_setcookie($_POST['ag_user_login'], $_POST['ag_user_pass'], true);
                wp_set_auth_cookie($user->ID, $rememberMe, is_ssl());

                wp_set_current_user($user->ID, $_POST['ag_user_login']);
                do_action('wp_login', $_POST['ag_user_login']);

                // echo '<pre>';
                // print_r($_POST);
                // echo '</pre>';

                wp_redirect(home_url());
                exit;
            }
        }
    }

    // register a new user
    public function registerMember()
    {
        if (isset($_POST["ag_user_register"]) && wp_verify_nonce($_POST['ag_register_nonce'], 'ag-register-EQzjpycX82yQ3C08Kfe')) {
            
            echo '<pre>';
            print_r($_POST);
            echo '</pre>';
            $user_login        = $_POST["ag_user_register"];
            $user_email        = $_POST["ag_user_email"];
            $user_first         = $_POST["ag_user_first"];
            $user_last         = $_POST["ag_user_last"];
            $user_pass        = $_POST["ag_user_pass"];
            $pass_confirm     = $_POST["ag_user_pass_confirm"];

            // this is required for username checks
            require_once(ABSPATH . WPINC . '/registration.php');

            if (username_exists($user_login)) {
                // Username already registered
                $this->formErrors()->add('username_unavailable', __('Username already taken'));
            }
            if (!validate_username($user_login)) {
                // invalid username
                $this->formErrors()->add('username_invalid', __('Invalid username'));
            }
            if ($user_login == '') {
                // empty username
                $this->formErrors()->add('username_empty', __('Please enter a username'));
            }
            if (!is_email($user_email)) {
                //invalid email
                $this->formErrors()->add('email_invalid', __('Invalid email'));
            }
            if (email_exists($user_email)) {
                //Email address already registered
                $this->formErrors()->add('email_used', __('Email already registered'));
            }
            if ($user_pass == '') {
                // passwords do not match
                $this->formErrors()->add('password_empty', __('Please enter a password'));
            }
            if ($user_pass != $pass_confirm) {
                // passwords do not match
                $this->formErrors()->add('password_mismatch', __('Passwords do not match'));
            }

            $errors = $this->formErrors()->get_error_messages();

            // only create the user in if there are no errors
            if (empty($errors)) {

                $new_user_id = wp_insert_user(
                    array(
                        'user_login'        => $user_login,
                        'user_pass'             => $user_pass,
                        'user_email'        => $user_email,
                        'first_name'        => $user_first,
                        'last_name'            => $user_last,
                        'user_registered'    => date('Y-m-d H:i:s'),
                        'role'                => 'subscriber'
                    )
                );
                if ($new_user_id) {

                    // send an email to the admin alerting them of the registration
                    wp_new_user_notification($new_user_id);

                    // log the new user in
                    wp_set_auth_cookie($new_user_id, false, is_ssl());

                    wp_set_current_user($new_user_id, $user_login);
                    do_action('wp_login', $user_login);



                    // send the newly created user to the home page after logging them in
                    wp_redirect(home_url());
                    exit;
                }
            }
        }
    }
}
