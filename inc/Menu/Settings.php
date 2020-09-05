<?php

namespace AG\CustomLoginForm\Menu;

defined('ABSPATH') or die();

use AG\CustomLoginForm\Log\Logger as Logger;

class Settings
{
    use Logger;

    private const FIELD_PREFIX = 'ag_';

    public function __construct()
    {
    }


    public function __destruct()
    {
    }

    public function createSettings()
    {
        $this->logger(AG_CUSTOM_LOGIN_FORM_DEBUG, AG_CUSTOM_LOGIN_FORM_LOGGING);

        register_setting(
            self::FIELD_PREFIX . 'custom_login', // option group
            self::FIELD_PREFIX . 'custom_login_options', // option name (to store in the wp_options table)
            array($this, 'sanitizeOptions') // sanitize_callback
        );

        add_settings_section(
            self::FIELD_PREFIX . 'custom_login_options', // option name
            '', // title
            function () { // callback
                printf(
                    '<p>%s <a href="%s">%s</a></p>',
                    __('Customize your '),
                    wp_login_url(),
                    __('wp-login page')
                );
            },
            'custom_login_opts' // page slug
        );

        // Login page logo image
        add_settings_field(
            'login_logo_image', // id
            'Logo image url (in media folder):', // title
            array($this, 'settingLoginOptions'), // callback
            'custom_login_opts', // page slug
            self::FIELD_PREFIX . 'custom_login_options', // section
            array('login_logo_image') // args
        );

        // Login page full background cover image
        add_settings_field(
            'login_background_image', // id
            'Background image url (in media folder):', // title
            array($this, 'settingLoginOptions'), // callback
            'custom_login_opts', // page slug
            self::FIELD_PREFIX . 'custom_login_options', // section
            array('login_background_image') // args
        );

        // Login form background in RGBA(), hex, hsb, etc.
        add_settings_field(
            'login_form_background', // id
            'Login Form background color:', // title
            array($this, 'settingLoginOptions'), // callback
            'custom_login_opts', // page slug
            self::FIELD_PREFIX . 'custom_login_options', // section
            array('login_form_background') // args
        );

        // Last updated (hidden)
        add_settings_field(
            'last_updated', // id
            '', // title
            array($this, 'settingLoginOptions'), // callback
            'custom_login_opts', // page slug
            self::FIELD_PREFIX . 'custom_login_options', // section
            array('last_updated') // args
        );
    }

    public function sanitizeOptions($opts)
    {
        $clean_opts = array_map('sanitize_text_field', $opts);

        return $clean_opts;
    }


    public static function settingLoginOptions($args)
    {
        $this->logger(AG_CUSTOM_LOGIN_FORM_DEBUG, AG_CUSTOM_LOGIN_FORM_LOGGING);

        // see last arg of add_settings_field like
        // array('login_logo_image') -> login_logo_image will be the property name
        // stored in options record
        $property = (string) array_pop($args);

        $opt_name = self::FIELD_PREFIX . 'custom_login_options';

        $opts = get_option($opt_name);

        if (empty($opts[$property])) {
            $default_value = self::getDefaultOptions()[$property];

            $val = $default_value;
        } else {
            $val = $opts[$property];
        }

        if ($property === 'last_updated') {

            print '<input type="hidden" id="hide-ag_custom_login_last_updated" class="widefat" value="' . esc_attr($val) . '" />';
?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var t = $('#hide-ag_custom_login_last_updated')
                        .parent().parent().hide();
                    console.log(t);

                    $('#hide-ag_custom_login_options[last_updated]')
                        .find('[scope=row]').hide();
                });
            </script>

<?php
        } else {
            print '<input type="text" name="' . $opt_name . '[' . $property . ']" class="widefat" value="' . esc_attr($val) . '" />';
        }
    }


    /**
     * Helper function that sets the default value for api options
     *
     * @return array
     */
    private static function getDefaultOptions()
    {
        return array(
            'login_logo_image'          => '',
            'login_background_image'    => '',
            'login_form_background'     => 'rgba(255, 255, 255, 0.9)', // #fff
            'last_updated'              => null
        );
    }

    /* This is the settings page template to render for admin user */
    public function settingsForm()
    {
        if (current_user_can('manage_options')) {
            $this->logger(AG_CUSTOM_LOGIN_FORM_DEBUG, AG_CUSTOM_LOGIN_FORM_LOGGING);

            date_default_timezone_set('Europe/Budapest');

            $apiOptions = get_option(self::FIELD_PREFIX . 'custom_login_options');
            $apiOptions['last_updated'] = time();
            update_option(self::FIELD_PREFIX . 'custom_login_options', $apiOptions);

            require_once AG_CUSTOM_LOGIN_FORM_PLUGIN_DIR . '/pages/settingsFormTemplate.php';
        }
    }
}
