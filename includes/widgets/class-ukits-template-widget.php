<?php
/**
 * Shared Elementor widget for rendering one UKITS template section.
 *
 * @package UKITS_Custom_Element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Base template section widget.
 */
abstract class UKITS_Custom_Element_Template_Widget extends Widget_Base {

	/**
	 * Template section id.
	 *
	 * @var string
	 */
	protected $section_id = '';

	/**
	 * Widget title.
	 *
	 * @var string
	 */
	protected $widget_title = '';

	/**
	 * Get widget category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( UKITS_Custom_Element_Plugin::CATEGORY );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-section';
	}

	/**
	 * Styles used by this widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ukits-custom-element-template' );
	}

	/**
	 * Scripts used by this widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ukits-custom-element-frontend' );
	}

	/**
	 * Widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ukits_' . sanitize_key( $this->section_id );
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->widget_title;
	}

	/**
	 * Register editable controls.
	 */
	protected function register_controls() {
		$default_html = $this->get_default_html();

		$this->register_text_controls( $default_html );
		$this->register_link_controls( $default_html );
		$this->register_button_controls( $default_html );
		$this->register_asset_controls( $default_html );
		$this->register_global_style_controls();
	}

	/**
	 * Register text controls.
	 *
	 * @param string $html Default section HTML.
	 */
	protected function register_text_controls( $html ) {
		$text_nodes = $this->extract_text_nodes( $html );

		if ( empty( $text_nodes ) ) {
			return;
		}

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Text Content', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $text_nodes as $index => $text ) {
			$this->add_control(
				$this->text_control_id( $index ),
				array(
					'label'   => esc_html( $this->control_label_from_text( $text, $index + 1 ) ),
					'type'    => strlen( $text ) > 70 ? Controls_Manager::TEXTAREA : Controls_Manager::TEXT,
					'rows'    => 3,
					'default' => $text,
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register controls for existing anchors.
	 *
	 * @param string $html Default section HTML.
	 */
	protected function register_link_controls( $html ) {
		$links = $this->extract_links( $html );

		if ( empty( $links ) ) {
			return;
		}

		$this->start_controls_section(
			'section_links',
			array(
				'label' => esc_html__( 'Links', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $links as $index => $link ) {
			$this->add_control(
				$this->link_control_id( $index ),
				array(
					'label'       => sprintf(
						/* translators: %d: link number. */
						esc_html__( 'Link %d', 'ukits-custom-element' ),
						$index + 1
					),
					'type'        => Controls_Manager::URL,
					'placeholder' => home_url( '/' ),
					'default'     => array(
						'url' => $link,
					),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register controls for button URLs.
	 *
	 * @param string $html Default section HTML.
	 */
	protected function register_button_controls( $html ) {
		$buttons = $this->extract_buttons( $html );

		if ( empty( $buttons ) ) {
			return;
		}

		$this->start_controls_section(
			'section_buttons',
			array(
				'label' => esc_html__( 'Buttons', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $buttons as $index => $label ) {
			$this->add_control(
				$this->button_text_control_id( $index ),
				array(
					'label'   => sprintf(
						/* translators: %d: button number. */
						esc_html__( 'Button %d Text', 'ukits-custom-element' ),
						$index + 1
					),
					'type'    => Controls_Manager::TEXT,
					'default' => $label,
				)
			);

			$this->add_control(
				$this->button_control_id( $index ),
				array(
					'label'       => esc_html( $this->control_label_from_text( $label ? $label : __( 'Button', 'ukits-custom-element' ), $index + 1 ) ),
					'type'        => Controls_Manager::URL,
					'placeholder' => home_url( '/' ),
					'default'     => array(
						'url' => '#',
					),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register one media control for each image/icon used in the default section.
	 *
	 * @param string $html Default section HTML.
	 */
	protected function register_asset_controls( $html ) {
		$assets = $this->extract_assets( $html );

		if ( empty( $assets ) ) {
			return;
		}

		$this->start_controls_section(
			'section_assets',
			array(
				'label' => esc_html__( 'Images & Icons', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		foreach ( $assets as $asset ) {
			$this->add_control(
				$this->asset_control_id( $asset ),
				array(
					'label'   => esc_html( basename( $asset ) ),
					'type'    => Controls_Manager::MEDIA,
					'default' => array(
						'url' => esc_url( UKITS_CUSTOM_ELEMENT_URL . 'assets/' . $asset ),
					),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Register shared style controls.
	 */
	protected function register_global_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Section Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'section_background',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .ukits-custom-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'section_border',
				'selector' => '{{WRAPPER}} .ukits-custom-element',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'section_box_shadow',
				'selector' => '{{WRAPPER}} .ukits-custom-element',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'text_style',
			array(
				'label' => esc_html__( 'Text Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element, {{WRAPPER}} .ukits-custom-element p, {{WRAPPER}} .ukits-custom-element div, {{WRAPPER}} .ukits-custom-element span, {{WRAPPER}} .ukits-custom-element h1, {{WRAPPER}} .ukits-custom-element h2, {{WRAPPER}} .ukits-custom-element h3, {{WRAPPER}} .ukits-custom-element h4' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .ukits-custom-element',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_style',
			array(
				'label' => esc_html__( 'Image Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_css_filters',
				'selector' => '{{WRAPPER}} .ukits-custom-element img',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .ukits-custom-element img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ukits-custom-element img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			array(
				'label' => esc_html__( 'Button Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button',
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => esc_html__( 'Normal', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			array(
				'label' => esc_html__( 'Hover', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element button:hover, {{WRAPPER}} .ukits-custom-element .ukits-converted-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ukits-custom-element button:hover, {{WRAPPER}} .ukits-custom-element .ukits-converted-button:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .ukits-custom-element button, {{WRAPPER}} .ukits-custom-element .ukits-converted-button',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$html     = $this->get_default_html();
		$html     = $this->apply_text_settings( $html, $settings );
		$html     = $this->apply_asset_settings( $html, $settings );
		$html     = $this->apply_link_settings( $html, $settings );
		$html     = $this->apply_button_settings( $html, $settings );
		$html     = $this->add_wrapper_class( $html );

		/*
		 * The rendered markup starts from bundled plugin templates only. Dynamic
		 * text and URLs are escaped while being applied above, so outputting the
		 * final markup preserves template background-image styles for Elementor.
		 */
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get default section template.
	 *
	 * @return string
	 */
	protected function get_default_html() {
		$file = UKITS_CUSTOM_ELEMENT_PATH . 'templates/sections/' . sanitize_file_name( $this->section_id ) . '.html';

		if ( ! is_readable( $file ) ) {
			return '';
		}

		return (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Extract editable text nodes.
	 *
	 * @param string $html Section HTML.
	 * @return array
	 */
	protected function extract_text_nodes( $html ) {
		preg_match_all( '#>([^<>]+)<#', $html, $matches );
		$text_nodes = array();

		foreach ( $matches[1] as $text ) {
			$text = trim( html_entity_decode( $text, ENT_QUOTES, get_bloginfo( 'charset' ) ) );
			$text = preg_replace( '/\s+/', ' ', $text );

			if ( '' !== $text ) {
				$text_nodes[] = $text;
			}
		}

		return $text_nodes;
	}

	/**
	 * Extract template assets referenced as img/file.ext.
	 *
	 * @param string $html Section HTML.
	 * @return array
	 */
	protected function extract_assets( $html ) {
		preg_match_all( '#img/([A-Za-z0-9._-]+\.(?:svg|png|jpe?g|webp|gif))#i', $html, $matches );

		if ( empty( $matches[1] ) ) {
			return array();
		}

		$assets = array_map(
			static function ( $file ) {
				return 'img/' . sanitize_file_name( $file );
			},
			$matches[1]
		);

		return array_values( array_unique( $assets ) );
	}

	/**
	 * Extract anchor hrefs.
	 *
	 * @param string $html Section HTML.
	 * @return array
	 */
	protected function extract_links( $html ) {
		preg_match_all( '#<a\b[^>]*href=["\']([^"\']*)["\'][^>]*>#i', $html, $matches );

		return ! empty( $matches[1] ) ? array_values( $matches[1] ) : array();
	}

	/**
	 * Extract button labels.
	 *
	 * @param string $html Section HTML.
	 * @return array
	 */
	protected function extract_buttons( $html ) {
		preg_match_all( '#<button\b[^>]*>(.*?)</button>#is', $html, $matches );

		if ( empty( $matches[1] ) ) {
			return array();
		}

		return array_map(
			static function ( $button_html ) {
				$text = trim( wp_strip_all_tags( $button_html ) );
				return preg_replace( '/\s+/', ' ', $text );
			},
			$matches[1]
		);
	}

	/**
	 * Make a compact Elementor control label from text.
	 *
	 * @param string $text  Default text.
	 * @param int    $index Text index.
	 * @return string
	 */
	protected function control_label_from_text( $text, $index ) {
		$text = trim( $text );

		if ( '' === $text ) {
			return sprintf(
				/* translators: %d: field number. */
				__( 'Text %d', 'ukits-custom-element' ),
				$index
			);
		}

		if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
			return mb_strlen( $text ) > 42 ? mb_substr( $text, 0, 42 ) . '...' : $text;
		}

		return strlen( $text ) > 42 ? substr( $text, 0, 42 ) . '...' : $text;
	}

	/**
	 * Text control id.
	 *
	 * @param int $index Text index.
	 * @return string
	 */
	protected function text_control_id( $index ) {
		return 'text_' . absint( $index );
	}

	/**
	 * Asset control id.
	 *
	 * @param string $asset Asset path relative to assets/.
	 * @return string
	 */
	protected function asset_control_id( $asset ) {
		return 'asset_' . md5( $asset );
	}

	/**
	 * Link control id.
	 *
	 * @param int $index Link index.
	 * @return string
	 */
	protected function link_control_id( $index ) {
		return 'link_' . absint( $index );
	}

	/**
	 * Button control id.
	 *
	 * @param int $index Button index.
	 * @return string
	 */
	protected function button_control_id( $index ) {
		return 'button_link_' . absint( $index );
	}

	/**
	 * Button text control id.
	 *
	 * @param int $index Button index.
	 * @return string
	 */
	protected function button_text_control_id( $index ) {
		return 'button_text_' . absint( $index );
	}

	/**
	 * Apply text control values.
	 *
	 * @param string $html     Section HTML.
	 * @param array  $settings Elementor settings.
	 * @return string
	 */
	protected function apply_text_settings( $html, $settings ) {
		$index = 0;

		return preg_replace_callback(
			'#>([^<>]+)<#',
			function ( $matches ) use ( $settings, &$index ) {
				$original_text = trim( html_entity_decode( $matches[1], ENT_QUOTES, get_bloginfo( 'charset' ) ) );

				if ( '' === $original_text ) {
					return $matches[0];
				}

				$control_id = $this->text_control_id( $index );
				$value      = isset( $settings[ $control_id ] ) ? $settings[ $control_id ] : $original_text;
				$index++;

				return '>' . esc_html( $value ) . '<';
			},
			$html
		);
	}

	/**
	 * Replace default asset paths with media control values.
	 *
	 * @param string $html     Section HTML.
	 * @param array  $settings Elementor settings.
	 * @return string
	 */
	protected function apply_asset_settings( $html, $settings ) {
		foreach ( $this->extract_assets( $this->get_default_html() ) as $asset ) {
			$control_id = $this->asset_control_id( $asset );
			$url        = isset( $settings[ $control_id ]['url'] ) ? $settings[ $control_id ]['url'] : '';
			$url        = $url ? esc_url_raw( $url ) : UKITS_CUSTOM_ELEMENT_URL . 'assets/' . $asset;
			$file       = preg_quote( basename( $asset ), '#' );

			$html = preg_replace_callback(
				'#<([a-z0-9]+)([^>]*class="[^"]*bg-\[url\(\'img/' . $file . '\'[^"]*"[^>]*)>#i',
				static function ( $matches ) use ( $url ) {
					$tag = '<' . $matches[1] . $matches[2];

					if ( false !== stripos( $tag, 'style="' ) ) {
						$tag = preg_replace( '#style="([^"]*)"#i', 'style="$1 background-image: url(' . esc_url( $url ) . ');"', $tag );
					} else {
						$tag .= ' style="background-image: url(' . esc_url( $url ) . ');"';
					}

					return $tag . '>';
				},
				$html
			);

			$html = str_replace( 'img/' . basename( $asset ), esc_url( $url ), $html );
		}

		return $html;
	}

	/**
	 * Apply URL settings to anchors.
	 *
	 * @param string $html     Section HTML.
	 * @param array  $settings Elementor settings.
	 * @return string
	 */
	protected function apply_link_settings( $html, $settings ) {
		$index = 0;

		return preg_replace_callback(
			'#<a\b([^>]*)href=["\']([^"\']*)["\']([^>]*)>#i',
			function ( $matches ) use ( $settings, &$index ) {
				$control_id = $this->link_control_id( $index );
				$link       = isset( $settings[ $control_id ] ) && is_array( $settings[ $control_id ] ) ? $settings[ $control_id ] : array();
				$url        = ! empty( $link['url'] ) ? $link['url'] : $matches[2];
				$target     = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
				$nofollow   = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
				$index++;

				return '<a' . $matches[1] . 'href="' . esc_url( $url ) . '"' . $target . $nofollow . $matches[3] . '>';
			},
			$html
		);
	}

	/**
	 * Convert template buttons to linked controls.
	 *
	 * @param string $html     Section HTML.
	 * @param array  $settings Elementor settings.
	 * @return string
	 */
	protected function apply_button_settings( $html, $settings ) {
		$index          = 0;
		$default_labels = $this->extract_buttons( $this->get_default_html() );

		return preg_replace_callback(
			'#<button\b([^>]*)>(.*?)</button>#is',
			function ( $matches ) use ( $settings, $default_labels, &$index ) {
				$control_id = $this->button_control_id( $index );
				$text_id    = $this->button_text_control_id( $index );
				$link       = isset( $settings[ $control_id ] ) && is_array( $settings[ $control_id ] ) ? $settings[ $control_id ] : array();
				$url        = ! empty( $link['url'] ) ? $link['url'] : '#';
				$target     = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
				$nofollow   = ! empty( $link['nofollow'] ) ? ' rel="nofollow"' : '';
				$attrs      = preg_replace( '#\s*type=["\'][^"\']*["\']#i', '', $matches[1] );
				$inner      = $matches[2];
				$label      = isset( $settings[ $text_id ] ) ? (string) $settings[ $text_id ] : '';
				$default    = isset( $default_labels[ $index ] ) ? (string) $default_labels[ $index ] : '';

				if ( false !== stripos( $attrs, 'class="' ) ) {
					$attrs = preg_replace( '#class="([^"]*)"#i', 'class="$1 ukits-converted-button"', $attrs, 1 );
				} else {
					$attrs .= ' class="ukits-converted-button"';
				}

				if ( '' !== $label && $label !== $default ) {
					$inner = preg_replace( '#>([^<>]+)<#', '>' . esc_html( $label ) . '<', $inner, 1 );
				}

				$index++;

				return '<a href="' . esc_url( $url ) . '"' . $target . $nofollow . $attrs . ' role="button">' . $inner . '</a>';
			},
			$html
		);
	}

	/**
	 * Add plugin wrapper class to the section tag.
	 *
	 * @param string $html Rendered HTML.
	 * @return string
	 */
	protected function add_wrapper_class( $html ) {
		return preg_replace_callback(
			'#<section([^>]*)class="([^"]*)"#i',
			static function ( $matches ) {
				if ( false !== strpos( $matches[2], 'ukits-custom-element' ) ) {
					return $matches[0];
				}

				return '<section' . $matches[1] . 'class="ukits-custom-element ' . $matches[2] . '"';
			},
			$html,
			1
		);
	}

	/**
	 * Allowed HTML for template output.
	 *
	 * @return array
	 */
	protected function allowed_html() {
		$allowed = wp_kses_allowed_html( 'post' );
		$tags    = array( 'section', 'div', 'span', 'p', 'br', 'button', 'img', 'a', 'h1', 'h2', 'h3', 'h4', 'ul', 'ol', 'li' );

		foreach ( $tags as $tag ) {
			if ( ! isset( $allowed[ $tag ] ) ) {
				$allowed[ $tag ] = array();
			}
		}

		$global_attrs = array(
			'id'            => true,
			'class'         => true,
			'style'         => true,
			'role'          => true,
			'aria-label'    => true,
			'aria-expanded' => true,
			'aria-controls' => true,
			'type'          => true,
			'href'          => true,
			'target'        => true,
			'rel'           => true,
			'src'           => true,
			'alt'           => true,
			'width'         => true,
			'height'        => true,
			'loading'       => true,
			'decoding'      => true,
			'data-*'        => true,
		);

		foreach ( $allowed as $tag => $attrs ) {
			$allowed[ $tag ] = array_merge( $attrs, $global_attrs );
		}

		return $allowed;
	}
}
