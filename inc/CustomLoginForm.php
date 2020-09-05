<?php

namespace AG\CustomLoginForm;

// use AG\CustomLoginForm\Menu\Settings as Settings;

// use AG\CustomLoginForm\Widget\CustomLoginFormWidget as CustomLoginFormWidget;

use AG\CustomLoginForm\Form\Login as Login;

use AG\CustomLoginForm\Form\Register as Register;

use AG\CustomLoginForm\Validate\Validate as Validate;

use AG\CustomLoginForm\Menu\Settings as Settings;

defined('ABSPATH') or die();

/**
 * Class made to create custom login/register forms
 * Author: András Gulácsi 2020
 */
final class CustomLoginForm
{
    private const TEXT_DOMAIN = 'ag-custom-login-form';

    private const OPTION_NAME = 'ag_custom_login_form_version';

    private const FIELD_PREFIX = 'ag_';

    private const OPTION_VERSION = '0.1';

    // class instance
    private static $instance;

    private static $login;

    private static $register;

    private static $validate;

    private static $settings;


    /**
     * Get class instance, if not exists -> instantiate it
     * @return self $instance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self(
                new Login(),
                new Register(),
                new Validate(),
                new Settings()
            );
        }
        return self::$instance;
    }


    // CONSTRUCTOR ------------------------------
    // initialize properties, some defaults added
    private function __construct(
        Login $login,
        Register $register,
        Validate $validate,
        Settings $settings
    ) {
        self::$login = $login;
        self::$register = $register;
        self::$validate = $validate;
        self::$settings = $settings;

        add_action('plugins_loaded', array($this, 'loadTextdomain'));

        add_shortcode('register_form', array(self::$register, 'registrationForm'));
        add_shortcode('login_form', array(self::$login, 'loginForm'));

        add_action('init', array(self::$validate, 'loginMember'));
        add_action('init', array(self::$validate, 'registerMember'));

        // customize wp-login page
        add_action('login_enqueue_scripts', array($this, 'loginLogo'));
        add_filter('login_headerurl', array($this, 'loginLogoUrl'));

        // DEPRECATED filter hook: login_headertitle
        // add_filter('login_headertitle', array($this, 'loginLogoUrlTitle'));
        add_filter('login_headertext', array($this, 'loginLogoUrlTitle'));

        // register Settings
        add_filter('admin_init', array(self::$settings, 'createSettings'));

        // add admin menu and page
        add_action('admin_menu', array($this, 'addAdminMenu'));

        // put the css before end of </body>
        add_action('wp_enqueue_scripts', array($this, 'addCSS'));
    }


    // DESCTRUCTOR -------------------------------
    public function __destruct()
    {
    }


    // METHODS
    public static function loadTextdomain(): void
    {
        // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php

        $domain = self::TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(\WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(dirname(__FILE__, 2)) . '/languages/');
    }


    /**
     * Add custom logo for wp-login page
     */
    public function loginLogo(): void
    {
        $optName = self::FIELD_PREFIX . 'custom_login_options';
        $loginLogoSettings = get_option($optName);

        $url = $loginLogoSettings['login_logo_image'] ? esc_url($loginLogoSettings['login_logo_image']): '';
        $formBackground = $loginLogoSettings['login_form_background'] ? $loginLogoSettings['login_form_background']: '#fff';
        
        $width = '80px';
        $height = '80px';
        
        $backgroundUrl = $loginLogoSettings['login_background_image'] ? esc_url($loginLogoSettings['login_background_image']): '';

        if ($backgroundUrl) {
            $customBackground = 'background: url(' . $backgroundUrl . ') no-repeat center center fixed !important;';
            $customBackground .= '-webkit-background-size: cover;';
            $customBackground .= '-moz-background-size: cover;';
            $customBackground .= '-o-background-size: cover;';
            $customBackground .= 'background-size: cover';
        } else {
            // if no bg image supplied, the default background will be used
            $customBackground = 'background: #f1f1f1';
        }

  

        $addStyle = <<<CUSTOMLOGINLOGO
        <style type="text/css">
            body {
                $customBackground
            }
            #login h1 a, .login h1 a {
                background-image: url($url);
            height: $height;
            width: $width;
            background-size: $width $height;
            background-repeat: no-repeat;
            padding-bottom: 10px;
            }

            // body.login {}
            // body.login div#login {}
            // body.login div#login h1 {}
            // body.login div#login h1 a {}
            body.login div#login form#loginform {
                background: $formBackground !important;
            }
            // body.login div#login form#loginform p {}
            // body.login div#login form#loginform p label {}
            // body.login div#login form#loginform input {}
            // body.login div#login form#loginform input#user_login {}
            // body.login div#login form#loginform input#user_pass {}
            // body.login div#login form#loginform p.forgetmenot {}
            // body.login div#login form#loginform p.forgetmenot input#rememberme {}
            // body.login div#login form#loginform p.submit {}
            // body.login div#login form#loginform p.submit input#wp-submit {}
            body.login div#login p#nav {
                margin: 0;
                padding-top: 12px;
                background: rgba(255,255,255,0.7);
            }
            // body.login div#login p#nav a {}
            body.login div#login p#backtoblog {
                margin: 0 0 16px 0;
                padding-top: 16px;
                padding-bottom: 12px;
                background: rgba(255,255,255,0.7);
            }
            // body.login div#login p#backtoblog a {}
        </style>
CUSTOMLOGINLOGO;

        echo $addStyle;
    }

    public function loginLogoUrl(): string
    {
        return home_url();
    }
    public function loginLogoUrlTitle(): string
    {
        return get_bloginfo('name', 'display') . ' - ' . get_bloginfo('description', 'display');
    }


    /**
     * Register admin menu page and submenu page
     * @return void
     */
    public function addAdminMenu(): void
    {
        add_menu_page(
            __('Custom Login Forms Admin'), // page title
            __('Login Screen Settings'), // menu title
            'manage_options', // capability
            'custom_login_settings', // menu slug
            array(self::$settings, 'settingsForm'), // callback
            'dashicons-migrate' // icon
        );
    }


    /**
     * Add some styling to the plugin's admin and shortcode UI
     * @return void
     */
    public function addCSS(): void
    {
    }


    /**
     * Add add an option with the version when activated
     */
    public static function activatePlugin(): void
    {
        $option = self::OPTION_NAME;
        // check if option exists, then delete
        if (!get_option($option)) {
            add_option($option, self::OPTION_VERSION);
        }
    }


    // This code will only run when plugin is deleted
    // it will drop the custom database table, delete wp_option record (if exists)
    public static function uninstallPlugin()
    {
        // check if option exists, then delete
        if (get_option(self::OPTION_NAME)) {
            delete_option(self::OPTION_NAME);
        }
    }

}
