<?php

namespace AG\CustomLoginForm\Error;

defined('ABSPATH') or die();

/**
 * trait for Error msgs
 */
trait Error
{

    public function __construct()
    {
    }
    public function __destruct()
    {
    }


    // used for tracking error messages
    public function formErrors()
    {
        static $wp_error; // Will hold global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new \WP_Error(null, null, null));
    }


    // displays error messages from form submissions
    public function showErrorMessages()
    {
        if ($codes = $this->formErrors()->get_error_codes()) {
            echo '<div class="ag-errors">';
            // Loop error codes and display errors
            foreach ($codes as $code) {
                $message = $this->formErrors()->get_error_message($code);
                echo '<span class="ag-error-item"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
            echo '</div>';
        }
    }
}
