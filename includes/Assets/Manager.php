<?php

namespace Dearvn\BotLite\Assets;

use Dearvn\BotLite\Helpers\Url;

/**
 * Asset Manager class.
 *
 * Responsible for managing all of the assets (CSS, JS, Images, Locales).
 */
class Manager {

    /**
     * Constructor.
     *
     * @since 0.2.0
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_all_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Register all scripts and styles.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register_all_scripts() {
        $this->register_styles( $this->get_styles() );
        $this->register_scripts( $this->get_scripts() );

        $this->localize_script();

        // Register block scripts.
        //$this->register_all_blocks();
    }

    /**
     * Get all styles.
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function get_styles(): array {
        return [
            'botlite-custom-css' => [
                'src'     => BOT_LITE_ASSETS . '/css/style.css',
                'version' => BOT_LITE_VERSION,
                'deps'    => [],
            ],
            'botlite-css' => [
                'src'     => BOT_LITE_BUILD . '/index.css',
                'version' => BOT_LITE_VERSION,
                'deps'    => [],
            ],
        ];
    }

    /**
     * Get all scripts.
     *
     * @since 0.2.0
     *
     * @return array
     */
    public function get_scripts(): array {
        $dependency = require_once BOT_LITE_DIR . '/build/index.asset.php';

        return [
            'botlite-app' => [
                'src'       => BOT_LITE_BUILD . '/index.js',
                'version'   => filemtime( BOT_LITE_DIR . '/build/index.js' ),
                'deps'      => $dependency['dependencies'],
                'in_footer' => true,
            ],
        ];
    }

    /**
     * Register styles.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register_styles( array $styles ) {
        foreach ( $styles as $handle => $style ) {
            wp_register_style( $handle, $style['src'], $style['deps'], $style['version'] );
        }
    }

    /**
     * Register scripts.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register_scripts( array $scripts ) {
        foreach ( $scripts as $handle =>$script ) {
            wp_register_script( $handle, $script['src'], $script['deps'], $script['version'], $script['in_footer'] );
        }
    }

    /**
     * Enqueue admin styles and scripts.
     *
     * @since 0.2.0
     * @since 0.3.0 Loads the JS and CSS only on the Alert Place admin page.
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        
        if ( Url::is_bot_lite_page() || Url::is_new_or_edit_post() ) {
            wp_enqueue_style( 'botlite-css' );
             wp_enqueue_script( 'botlite-app' );
        }
    }

    /**
     * Register blocks.
     *
     * @since 0.6.0
     *
     * @return void
     */
    public function register_all_blocks() {
        $blocks = [
            'header/',
        ];

        foreach( $blocks as $block ) {
            $block_folder = BOT_LITE_PATH . '/build/blocks' . '/' . $block;
            $block_options = [];

            $markup_file_path = $block_folder . '/markup.php';

			if ( file_exists( $markup_file_path ) ) {
				$block_options['render_callback'] = function( $attributes, $content, $block ) use ( $block_folder ) {
					$context = $block->context;
					ob_start();
					include $block_folder . '/markup.php';
					return ob_get_clean();
				};
			};

            register_block_type_from_metadata( $block_folder,  $block_options );
        }
    }

    /**
     * Localize script for both frontend and backed.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function localize_script(): void {
        wp_enqueue_style( 'botlite-custom-css' );
        wp_enqueue_script( 'botlite', BOT_LITE_ASSETS . '/js/main.js', filemtime( BOT_LITE_DIR . '/assets/js/main.js' ), true );

        $settings = wp_bot_lite()->settings->get();

        wp_localize_script( 'botlite',
            'botlite',
            [
                'enableTrading'  => $settings['enable_trading'],
                'apiKey'    => $settings['api_key'],
                'secretKey'    => $settings['secret_key'],
                'urls'      => [
                    'admin'     => admin_url(),
                    'adminPage' => admin_url( 'admin.php' ),
                    'newPost'   => admin_url( 'post-new.php' ),
                ],
                'images'    => [
                    'logoSm' => BOT_LITE_ASSETS . '/images/logo-sm.png',
                    'logo'   => BOT_LITE_ASSETS . '/images/logo.png',
                ]
            ]
        );
    }
}
