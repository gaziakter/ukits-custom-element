<?php
/**
 * Plugin Name: UKITS Custom Element
 * Description: Elementor custom widgets generated from the UKITS HTML template sections.
 * Version: 1.4.2
 * Author: Gazi Akter
 * Author URI: https://gaziakter.com/
 * Text Domain: ukits-custom-element
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Elementor tested up to: 3.29.0
 *
 * @package UKITS_Custom_Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UKITS_CUSTOM_ELEMENT_VERSION', '1.4.2' );
define( 'UKITS_CUSTOM_ELEMENT_FILE', __FILE__ );
define( 'UKITS_CUSTOM_ELEMENT_PATH', plugin_dir_path( __FILE__ ) );
define( 'UKITS_CUSTOM_ELEMENT_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin bootstrap.
 */
final class UKITS_Custom_Element_Plugin {

	const CATEGORY = 'ukits-custom-element';

	/**
	 * Start hooks.
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_nav_menus' ) );
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_category' ) );
		add_action( 'elementor/frontend/after_register_styles', array( __CLASS__, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( __CLASS__, 'enqueue_editor_assets' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( __CLASS__, 'enqueue_editor_assets' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
		add_action( 'admin_notices', array( __CLASS__, 'elementor_missing_notice' ) );
		add_action( 'wp_head', array( __CLASS__, 'print_fallback_site_icon' ), 2 );
		add_filter( 'safe_style_css', array( __CLASS__, 'allow_template_inline_css' ) );
		add_filter( 'get_site_icon_url', array( __CLASS__, 'fallback_site_icon_url' ), 10, 3 );
	}

	/**
	 * Load plugin translations.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'ukits-custom-element',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Register menu locations used by Header and Footer widgets.
	 */
	public static function register_nav_menus() {
		register_nav_menus(
			array(
				'ukits-header'    => esc_html__( 'UKITS Header Menu', 'ukits-custom-element' ),
				'footer-coverage' => esc_html__( 'UKITS Footer Coverage Menu', 'ukits-custom-element' ),
				'footer-info'     => esc_html__( 'UKITS Footer Info Menu', 'ukits-custom-element' ),
			)
		);
	}

	/**
	 * Use the bundled logo as a browser tab icon when no site icon is set.
	 *
	 * @param string $url     Site icon URL.
	 * @param int    $size    Requested size.
	 * @param int    $blog_id Blog ID.
	 * @return string
	 */
	public static function fallback_site_icon_url( $url, $size, $blog_id ) {
		unset( $size, $blog_id );

		if ( $url ) {
			return $url;
		}

		return UKITS_CUSTOM_ELEMENT_URL . 'assets/img/image-UK-industrial-training-services.png';
	}

	/**
	 * Print fallback favicon markup when WordPress has no site icon configured.
	 */
	public static function print_fallback_site_icon() {
		if ( has_site_icon() ) {
			return;
		}

		$icon_url = esc_url( UKITS_CUSTOM_ELEMENT_URL . 'assets/img/image-UK-industrial-training-services.png' );

		printf( '<link rel="icon" href="%1$s" sizes="32x32" />' . "\n", $icon_url );
		printf( '<link rel="apple-touch-icon" href="%1$s" />' . "\n", $icon_url );
	}

	/**
	 * Add Elementor widget category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public static function register_category( $elements_manager ) {
		$elements_manager->add_category(
			self::CATEGORY,
			array(
				'title' => esc_html__( 'UKITS Custom Element', 'ukits-custom-element' ),
				'icon'  => 'eicon-code',
			)
		);
	}

	/**
	 * Register frontend styles.
	 */
	public static function register_styles() {
		wp_register_style(
			'ukits-custom-element-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@500;700&display=swap',
			array(),
			UKITS_CUSTOM_ELEMENT_VERSION
		);

		wp_register_style(
			'ukits-custom-element-globals',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/template/globals.css',
			array( 'ukits-custom-element-fonts' ),
			UKITS_CUSTOM_ELEMENT_VERSION
		);

		wp_register_style(
			'ukits-custom-element-template',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/template/styles.css',
			array( 'ukits-custom-element-globals' ),
			UKITS_CUSTOM_ELEMENT_VERSION
		);
	}

	/**
	 * Register frontend scripts.
	 */
	public static function register_scripts() {
		wp_register_script(
			'ukits-custom-element-tailwind',
			'https://cdn.tailwindcss.com',
			array(),
			UKITS_CUSTOM_ELEMENT_VERSION,
			false
		);

		wp_register_script(
			'ukits-custom-element-frontend',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/js/frontend.js',
			array( 'ukits-custom-element-tailwind' ),
			UKITS_CUSTOM_ELEMENT_VERSION,
			true
		);

		wp_localize_script(
			'ukits-custom-element-frontend',
			'UKITSCustomElement',
			array(
				'assetsUrl' => esc_url( UKITS_CUSTOM_ELEMENT_URL . 'assets/' ),
			)
		);
	}

	/**
	 * Keep the UKITS category at the top of the Elementor widgets panel.
	 */
	public static function enqueue_editor_assets() {
		wp_enqueue_style(
			'ukits-custom-element-editor',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/css/editor.css',
			array(),
			UKITS_CUSTOM_ELEMENT_VERSION
		);

		wp_enqueue_script(
			'ukits-custom-element-editor',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/js/editor.js',
			array(),
			UKITS_CUSTOM_ELEMENT_VERSION,
			true
		);
	}

	/**
	 * Allow the template's editable inline background images through wp_kses.
	 *
	 * @param array $styles Safe CSS properties.
	 * @return array
	 */
	public static function allow_template_inline_css( $styles ) {
		$styles[] = 'background-image';
		$styles[] = 'background-position';
		$styles[] = 'background-size';
		$styles[] = 'background-repeat';

		return array_values( array_unique( $styles ) );
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public static function register_widgets( $widgets_manager ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once UKITS_CUSTOM_ELEMENT_PATH . 'includes/widgets/class-ukits-template-widget.php';
		require_once UKITS_CUSTOM_ELEMENT_PATH . 'includes/widgets/class-ukits-section-widgets.php';

		foreach ( UKITS_Custom_Element_Section_Widgets::get_widgets() as $widget_class ) {
			if ( class_exists( $widget_class ) ) {
				$widgets_manager->register( new $widget_class() );
			}
		}
	}

	/**
	 * Show an admin notice when Elementor is unavailable.
	 */
	public static function elementor_missing_notice() {
		if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'UKITS Custom Element requires Elementor to register its custom widgets.', 'ukits-custom-element' )
		);
	}
}

UKITS_Custom_Element_Plugin::init();
