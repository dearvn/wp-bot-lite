<?php

/**
 * Plugin Name:       WP Bot Lite
 * Description:       A plugin to manage real estate to work in WordPress plugin development using WordPress Rest API, WP-script and many more...
 * Requires at least: 5.8
 * Requires PHP:      7.3
 * Version:           0.0.1
 * Author:            donaldit
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       botlite
 */

defined( 'ABSPATH' ) || exit;

/**
 * Wp_Bot_lite class.
 *
 * @class Wp_Bot_lite The class that holds the entire Wp_Bot_lite plugin
 */
final class Wp_Bot_lite {
    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * Plugin slug.
     *
     * @var string
     *
     * @since 0.2.0
     */
    const SLUG = 'botlite';

    /**
     * Holds various class instances.
     *
     * @var array
     *
     * @since 0.2.0
     */
    private $container = [];

    /**
     * Constructor for the BotLite class.
     *
     * Sets up all the appropriate hooks and actions within our plugin.
     *
     * @since 0.2.0
     */
    private function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';

        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

        add_action( 'wp_loaded', [ $this, 'flush_rewrite_rules' ] );
        $this->init_plugin();
    }

    /**
     * Initializes the Wp_Bot_lite() class.
     *
     * Checks for an existing Wp_Bot_lite() instance
     * and if it doesn't find one, creates it.
     *
     * @since 0.2.0
     *
     * @return Wp_Bot_lite|bool
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Wp_Bot_lite();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @since 0.2.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @since 0.2.0
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function define_constants() {
        define( 'BOT_LITE_VERSION', self::VERSION );
        define( 'BOT_LITE_SLUG', self::SLUG );
        define( 'BOT_LITE_FILE', __FILE__ );
        define( 'BOT_LITE_DIR', __DIR__ );
        define( 'BOT_LITE_PATH', dirname( BOT_LITE_FILE ) );
        define( 'BOT_LITE_INCLUDES', BOT_LITE_PATH . '/includes' );
        define( 'BOT_LITE_TEMPLATE_PATH', BOT_LITE_PATH . '/templates' );
        define( 'BOT_LITE_URL', plugins_url( '', BOT_LITE_FILE ) );
        define( 'BOT_LITE_BUILD', BOT_LITE_URL . '/build' );
        define( 'BOT_LITE_ASSETS', BOT_LITE_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugins are loaded.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_hooks();

        /**
         * Fires after the plugin is loaded.
         *
         * @since 0.2.0
         */
        do_action( 'bot_lite_loaded' );
    }

    /**
     * Activating the plugin.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function activate() {
        // Run the installer to create necessary migrations and seeders.
        $this->install();
    }

    /**
     * Placeholder for deactivation function.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function deactivate() {
        //
    }

    /**
     * Flush rewrite rules after plugin is activated.
     *
     * Nothing being added here yet.
     *
     * @since 0.2.0
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
    }

    /**
     * Run the installer to create necessary migrations and seeders.
     *
     * @since 0.3.0
     *
     * @return void
     */
    private function install() {
        $installer = new \Dearvn\BotLite\Setup\Installer();
        $installer->run();
    }

    /**
     * Include the required files.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function includes() {
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin_menu'] = new Dearvn\BotLite\Admin\Menu();
        }
    }

    /**
     * Initialize the hooks.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_hooks() {
        // Init classes
        add_action( 'init', [ $this, 'init_classes' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );

        // Add the plugin page links
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
    }

    /**
     * Instantiate the required classes.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function init_classes() {
        // Init necessary hooks
        new Dearvn\BotLite\User\Hooks();

        // Common classes
        $this->container['assets']   = new Dearvn\BotLite\Assets\Manager();
        $this->container['rest_api'] = new Dearvn\BotLite\REST\Api();
        $this->container['alerts']     = new Dearvn\BotLite\Alerts\Manager();
        $this->container['orders']     = new Dearvn\BotLite\Orders\Manager();
    }

    /**
     * Initialize plugin for localization.
     *
     * @uses load_plugin_textdomain()
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function localization_setup() {
        load_plugin_textdomain( 'botlite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        // Load the React-pages translations.
        if ( is_admin() ) {
            // Check if handle is registered in wp-script
            $this->container['assets']->register_all_scripts();

            // Load wp-script translation for bot-lite-app
            wp_set_script_translations( 'bot-lite-app', 'botlite', plugin_dir_path( __FILE__ ) . 'languages/' );
        }
    }

    /**
     * What type of request is this.
     *
     * @since 0.2.0
     *
     * @param string $type admin, ajax, cron or frontend
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined( 'DOING_AJAX' );

            case 'rest':
                return defined( 'REST_REQUEST' );

            case 'cron':
                return defined( 'DOING_CRON' );

            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    /**
     * Plugin action links
     *
     * @param array $links
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=botlite#/settings' ) . '">' . __( 'Settings', 'botlite' ) . '</a>';
        $links[] = '<a href="https://github.com/dearvn/bot-lite#quick-start" target="_blank">' . __( 'Documentation', 'botlite' ) . '</a>';

        return $links;
    }
}

/**
 * Initialize the main plugin.
 *
 * @since 0.2.0
 *
 * @return \Wp_Bot_lite|bool
 */
function wp_bot_lite() {
    return Wp_Bot_lite::init();
}

/*
 * Kick-off the plugin.
 *
 * @since 0.2.0
 */
wp_bot_lite();
