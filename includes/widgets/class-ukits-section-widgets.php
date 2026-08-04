<?php
/**
 * Concrete UKITS section widgets.
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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class UKITS_Custom_Element_Header_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'header';
	protected $widget_title = 'Header Section';

	/**
	 * Register Header-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'logo_section',
			array(
				'label' => esc_html__( 'Logo Section', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'logo_source',
			array(
				'label'   => esc_html__( 'Logo Source', 'ukits-custom-element' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Auto / Site Logo', 'ukits-custom-element' ),
					'custom' => esc_html__( 'Custom Logo', 'ukits-custom-element' ),
					'plugin' => esc_html__( 'Plugin Default Logo', 'ukits-custom-element' ),
				),
			)
		);

		$this->add_control(
			'logo',
			array(
				'label'       => esc_html__( 'Custom Logo', 'ukits-custom-element' ),
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => $this->get_default_logo_url(),
				),
				'description' => esc_html__( 'If the site logo is set, it is used first. If not, this plugin logo is shown. Recommended default logo size: 145px wide by 92px high.', 'ukits-custom-element' ),
				'condition'   => array(
					'logo_source!' => 'plugin',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'logo_size',
				'default'   => 'full',
				'separator' => 'none',
			)
		);

		$this->add_control(
			'logo_link',
			array(
				'label'       => esc_html__( 'Logo Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menu_section',
			array(
				'label' => esc_html__( 'Menu Section', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'       => esc_html__( 'Select Menu', 'ukits-custom-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => $this->get_menu_options(),
				'description' => esc_html__( 'Auto uses your site menu when available. If no WordPress menu exists, the plugin default menu is used.', 'ukits-custom-element' ),
			)
		);

		$this->add_responsive_control(
			'menu_gap',
			array(
				'label'      => esc_html__( 'Menu Gap', 'ukits-custom-element' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 32,
				),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-nav' => 'gap: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => esc_html__( 'Button Section', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'BOOK NOW', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#courses' ),
				),
			)
		);

		$this->end_controls_section();

		$this->register_logo_style_controls();
		$this->register_menu_style_controls();
		$this->register_button_style_controls();
	}

	/**
	 * Register logo style controls.
	 */
	private function register_logo_style_controls() {
		$this->start_controls_section(
			'logo_style_section',
			array(
				'label' => esc_html__( 'Logo Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_width',
			array(
				'label'      => esc_html__( 'Width', 'ukits-custom-element' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 500,
					),
					'%'  => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 145,
				),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-logo, {{WRAPPER}} #header .header-logo-link' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'      => esc_html__( 'Height', 'ukits-custom-element' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 300,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 92,
				),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-logo, {{WRAPPER}} #header .header-logo-link' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'logo_object_fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'contain',
				'options'   => array(
					'contain' => esc_html__( 'Contain', 'ukits-custom-element' ),
					'cover'   => esc_html__( 'Cover', 'ukits-custom-element' ),
					'fill'    => esc_html__( 'Fill', 'ukits-custom-element' ),
				),
				'selectors' => array(
					'{{WRAPPER}} #header .header-logo' => 'object-fit: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'logo_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} #header .header-logo' => 'opacity: {{SIZE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'logo_css_filters',
				'selector' => '{{WRAPPER}} #header .header-logo',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'logo_border',
				'selector' => '{{WRAPPER}} #header .header-logo',
			)
		);

		$this->add_responsive_control(
			'logo_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'logo_box_shadow',
				'selector' => '{{WRAPPER}} #header .header-logo',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register menu style controls.
	 */
	private function register_menu_style_controls() {
		$this->start_controls_section(
			'menu_style_section',
			array(
				'label' => esc_html__( 'Menu Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'selector' => '{{WRAPPER}} #header .ukits-header-menu-link',
			)
		);

		$this->add_responsive_control(
			'menu_align',
			array(
				'label'     => esc_html__( 'Alignment', 'ukits-custom-element' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'ukits-custom-element' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'ukits-custom-element' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'ukits-custom-element' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} #header .header-nav' => 'justify-content: {{VALUE}} !important;',
				),
			)
		);

		$this->start_controls_tabs( 'menu_style_tabs' );

		$this->start_controls_tab(
			'menu_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'menu_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #header .ukits-header-menu-link' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'menu_underline_color',
			array(
				'label'     => esc_html__( 'Underline Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #header .header-menu-line' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'menu_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'menu_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #header .header-menu-item:hover .ukits-header-menu-link' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'menu_item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} #header .ukits-header-menu-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register button style controls.
	 */
	private function register_button_style_controls() {
		$this->start_controls_section(
			'button_style_section',
			array(
				'label' => esc_html__( 'Button Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} #header .header-cta-button',
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #header .header-cta-button' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #header .header-cta-button' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} #header .header-cta-button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-cta-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} #header .header-cta-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'button_text_shadow',
				'selector' => '{{WRAPPER}} #header .header-cta-button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'top'      => 14,
					'right'    => 33,
					'bottom'   => 14,
					'left'     => 33,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} #header .header-cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the dynamic header.
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$logo_url    = $this->get_render_logo_url( $settings );
		$logo_link   = ! empty( $settings['logo_link']['url'] ) ? $settings['logo_link']['url'] : home_url( '/' );
		$button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : esc_html__( 'BOOK NOW', 'ukits-custom-element' );
		$button_url  = ! empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '#';
		$menu_items  = $this->get_render_menu_items( isset( $settings['menu_id'] ) ? $settings['menu_id'] : 'auto' );

		$this->add_render_attribute( 'logo_link', 'href', esc_url( $this->normalize_header_url( $logo_link ) ) );
		if ( ! empty( $settings['logo_link']['is_external'] ) ) {
			$this->add_render_attribute( 'logo_link', 'target', '_blank' );
		}
		if ( ! empty( $settings['logo_link']['nofollow'] ) ) {
			$this->add_render_attribute( 'logo_link', 'rel', 'nofollow' );
		}
		$this->add_render_attribute( 'button_link', 'href', esc_url( $this->normalize_header_url( $button_url ) ) );
		if ( ! empty( $settings['button_link']['is_external'] ) ) {
			$this->add_render_attribute( 'button_link', 'target', '_blank' );
		}
		if ( ! empty( $settings['button_link']['nofollow'] ) ) {
			$this->add_render_attribute( 'button_link', 'rel', 'nofollow' );
		}
		$this->add_render_attribute( 'button_link', 'class', 'header-cta-button' );
		?>
		<section id="header" class="ukits-custom-element ukits-header-widget">
			<div class="header-shell">
				<div class="header-inner">
					<div class="header-layout">
						<a class="header-logo-link" <?php $this->print_render_attribute_string( 'logo_link' ); ?>>
							<img class="header-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
						</a>

						<nav class="header-nav" aria-label="<?php echo esc_attr__( 'Header menu', 'ukits-custom-element' ); ?>">
							<?php foreach ( $menu_items as $item ) : ?>
								<div class="header-menu-item flex flex-col gap-[1.5px]">
									<a class="ukits-header-menu-link [font-family:'Inter-SemiBold',Helvetica] font-semibold text-black text-sm tracking-[0.70px] leading-5 whitespace-nowrap" href="<?php echo esc_url( $item['url'] ); ?>">
										<?php echo esc_html( $item['title'] ); ?>
									</a>
									<div class="header-menu-line h-0.5 bg-[#48842b]"></div>
								</div>
							<?php endforeach; ?>
						</nav>

						<div class="header-cta">
							<a <?php $this->print_render_attribute_string( 'button_link' ); ?>>
								<?php echo esc_html( $button_text ); ?>
							</a>
						</div>

						<button class="header-menu-toggle" type="button" aria-label="<?php echo esc_attr__( 'Toggle menu', 'ukits-custom-element' ); ?>" aria-expanded="false">
							<img src="<?php echo esc_url( UKITS_CUSTOM_ELEMENT_URL . 'assets/img/menu-icon.svg' ); ?>" alt="" />
						</button>
					</div>
				</div>
			</div>
			<div class="absolute bottom-0 left-0 z-20 h-px w-full bg-[#E5E7EB]"></div>
		</section>
		<?php
	}

	/**
	 * Default plugin logo URL.
	 *
	 * @return string
	 */
	private function get_default_logo_url() {
		return UKITS_CUSTOM_ELEMENT_URL . 'assets/img/image-UK-industrial-training-services.png';
	}

	/**
	 * Normalize a Header URL control value.
	 *
	 * @param string $url URL value.
	 * @return string
	 */
	private function normalize_header_url( $url ) {
		$url = trim( (string) $url );

		if ( false !== strpos( $url, ' ' ) ) {
			$parts = preg_split( '/\s+/', $url );
			$url   = end( $parts );
		}

		return $url ? $url : home_url( '/' );
	}

	/**
	 * Resolve render logo URL.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_render_logo_url( $settings ) {
		$logo_source = isset( $settings['logo_source'] ) ? $settings['logo_source'] : 'auto';

		if ( 'plugin' === $logo_source ) {
			return $this->get_default_logo_url();
		}

		if ( 'custom' === $logo_source && ! empty( $settings['logo']['url'] ) ) {
			return $settings['logo']['url'];
		}

		$site_logo_id = (int) get_theme_mod( 'custom_logo' );

		if ( $site_logo_id ) {
			$site_logo = wp_get_attachment_image_url( $site_logo_id, 'full' );

			if ( $site_logo ) {
				return $site_logo;
			}
		}

		if ( ! empty( $settings['logo']['url'] ) ) {
			return $settings['logo']['url'];
		}

		return $this->get_default_logo_url();
	}

	/**
	 * Get Elementor select menu options.
	 *
	 * @return array
	 */
	private function get_menu_options() {
		$options = array(
			'auto' => esc_html__( 'Auto / Site Menu', 'ukits-custom-element' ),
		);

		foreach ( wp_get_nav_menus() as $menu ) {
			$options[ (string) $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Get menu items for render.
	 *
	 * @param string $menu_id Selected menu id.
	 * @return array
	 */
	private function get_render_menu_items( $menu_id ) {
		$resolved_menu_id = 'auto' === $menu_id ? $this->get_default_menu_id() : absint( $menu_id );
		$menu_items       = $resolved_menu_id ? wp_get_nav_menu_items( $resolved_menu_id ) : array();

		if ( ! empty( $menu_items ) && ! is_wp_error( $menu_items ) ) {
			return array_map(
				function ( $item ) {
					return array(
						'title' => $item->title,
						'url'   => $this->normalize_header_url( $item->url ),
					);
				},
				$menu_items
			);
		}

		return array(
			array(
				'title' => 'HOME',
				'url'   => home_url( '/#hero' ),
			),
			array(
				'title' => 'COURSES',
				'url'   => home_url( '/#courses' ),
			),
			array(
				'title' => 'ACCREDITATIONS',
				'url'   => home_url( '/#benefits' ),
			),
			array(
				'title' => 'RECRUITMENT',
				'url'   => home_url( '/#footer' ),
			),
			array(
				'title' => 'ABOUT',
				'url'   => home_url( '/#whychooseus' ),
			),
		);
	}

	/**
	 * Resolve a likely primary site menu.
	 *
	 * @return int
	 */
	private function get_default_menu_id() {
		$locations = get_nav_menu_locations();

		foreach ( array( 'ukits-header', 'primary', 'menu-1', 'header', 'main', 'top' ) as $location ) {
			if ( ! empty( $locations[ $location ] ) ) {
				return (int) $locations[ $location ];
			}
		}

		$menus = wp_get_nav_menus();

		return ! empty( $menus ) ? (int) $menus[0]->term_id : 0;
	}
}

class UKITS_Custom_Element_Hero_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'hero';
	protected $widget_title = 'Hero Section';

	/**
	 * Register Hero-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'hero_content_section',
			array(
				'label' => esc_html__( 'Hero Content', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( "UK'S LEADING TRAINING PROVIDER", 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'THE #1', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FORKLIFT', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_3',
			array(
				'label'   => esc_html__( 'Title Line 3', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => "TRAINING\nIN THE UK",
			)
		);

		$this->add_control(
			'description_before',
			array(
				'label'   => esc_html__( 'Description Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'Get certified in 1-3 days. Nationally recognised qualifications.', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'description_highlight',
			array(
				'label'   => esc_html__( 'Description Highlight', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'High pass rates.', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'description_after',
			array(
				'label'   => esc_html__( 'Description End Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'Fast-track options available.', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'hero_image_section',
			array(
				'label' => esc_html__( 'Hero Image', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'hero_image',
			array(
				'label'   => esc_html__( 'Background Image', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/hero-bg.jpg',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'hero_buttons_section',
			array(
				'label' => esc_html__( 'Buttons', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'primary_button_text',
			array(
				'label'   => esc_html__( 'Primary Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'START YOUR TRAINING', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'primary_button_link',
			array(
				'label'       => esc_html__( 'Primary Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#pricing' ),
				),
			)
		);

		$this->add_control(
			'show_primary_icon',
			array(
				'label'        => esc_html__( 'Show Primary Icon', 'ukits-custom-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'ukits-custom-element' ),
				'label_off'    => esc_html__( 'Hide', 'ukits-custom-element' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'primary_icon',
			array(
				'label'     => esc_html__( 'Primary Icon', 'ukits-custom-element' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg',
				),
				'condition' => array(
					'show_primary_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'secondary_button_text',
			array(
				'label'   => esc_html__( 'Secondary Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FREE QUOTE', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'secondary_button_link',
			array(
				'label'       => esc_html__( 'Secondary Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#courses' ),
				),
			)
		);

		$this->end_controls_section();

		$this->register_hero_text_style_controls();
		$this->register_hero_image_style_controls();
		$this->register_hero_button_style_controls();
	}

	/**
	 * Register Hero text style controls.
	 */
	private function register_hero_text_style_controls() {
		$this->start_controls_section(
			'hero_text_style_section',
			array(
				'label' => esc_html__( 'Text Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_style_heading',
			array(
				'label' => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-eyebrow-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eyebrow_typography',
				'selector' => '{{WRAPPER}} #hero .hero-eyebrow-text',
			)
		);

		$this->add_control(
			'title_style_heading',
			array(
				'label'     => esc_html__( 'Title', 'ukits-custom-element' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0a0a0a',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} #hero .hero-title',
			)
		);

		$this->add_control(
			'description_style_heading',
			array(
				'label'     => esc_html__( 'Description', 'ukits-custom-element' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4a5565',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-copy-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_highlight_color',
			array(
				'label'     => esc_html__( 'Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-copy-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} #hero .hero-copy',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Hero image style controls.
	 */
	private function register_hero_image_style_controls() {
		$this->start_controls_section(
			'hero_image_style_section',
			array(
				'label' => esc_html__( 'Image Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_opacity',
			array(
				'label'     => esc_html__( 'Image Opacity', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'default'   => array(
					'size' => 0.7,
				),
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-media-image' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'     => esc_html__( 'Position', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '50% 50%',
				'options'   => array(
					'center center' => esc_html__( 'Center Center', 'ukits-custom-element' ),
					'center top'    => esc_html__( 'Center Top', 'ukits-custom-element' ),
					'center bottom' => esc_html__( 'Center Bottom', 'ukits-custom-element' ),
					'left center'   => esc_html__( 'Left Center', 'ukits-custom-element' ),
					'right center'  => esc_html__( 'Right Center', 'ukits-custom-element' ),
				),
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-media-image' => 'background-position: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Size', 'ukits-custom-element' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => esc_html__( 'Cover', 'ukits-custom-element' ),
					'contain' => esc_html__( 'Contain', 'ukits-custom-element' ),
					'auto'    => esc_html__( 'Auto', 'ukits-custom-element' ),
				),
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-media-image' => 'background-size: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => esc_html__( 'Overlay Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.6)',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-media-overlay' => 'background: linear-gradient(0deg, {{VALUE}} 0%, rgba(0,0,0,0) 50%, rgba(0,0,0,0) 100%);',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'hero_image_css_filters',
				'selector' => '{{WRAPPER}} #hero .hero-media-image',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Hero button style controls.
	 */
	private function register_hero_button_style_controls() {
		$this->start_controls_section(
			'hero_button_style_section',
			array(
				'label' => esc_html__( 'Button Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hero_button_typography',
				'selector' => '{{WRAPPER}} #hero .hero-button',
			)
		);

		$this->start_controls_tabs( 'hero_primary_button_tabs' );

		$this->start_controls_tab(
			'hero_primary_button_normal',
			array(
				'label' => esc_html__( 'Primary', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'primary_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-primary-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'primary_button_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-primary-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hero_secondary_button_normal',
			array(
				'label' => esc_html__( 'Secondary', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'secondary_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-secondary-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'secondary_button_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-secondary-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'secondary_button_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #hero .hero-secondary-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'hero_primary_border',
				'label'    => esc_html__( 'Primary Border', 'ukits-custom-element' ),
				'selector' => '{{WRAPPER}} #hero .hero-primary-button',
			)
		);

		$this->add_responsive_control(
			'hero_button_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #hero .hero-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hero_button_shadow',
				'selector' => '{{WRAPPER}} #hero .hero-button',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render dynamic Hero section.
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$hero_image_url = ! empty( $settings['hero_image']['url'] ) ? $settings['hero_image']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/hero-bg.jpg';
		$primary_icon   = ! empty( $settings['primary_icon']['url'] ) ? $settings['primary_icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg';
		$title_line_3   = isset( $settings['title_line_3'] ) ? (string) $settings['title_line_3'] : "TRAINING\nIN THE UK";

		$primary_link = ! empty( $settings['primary_button_link'] ) && is_array( $settings['primary_button_link'] ) ? $settings['primary_button_link'] : array();
		$primary_link['url'] = ! empty( $primary_link['url'] ) && '#' !== $primary_link['url'] ? $primary_link['url'] : home_url( '/#courses' );
		$this->add_link_attributes( 'hero_primary_button', $primary_link );
		$this->add_render_attribute( 'hero_primary_button', 'class', 'hero-button hero-primary-button' );

		$secondary_link = ! empty( $settings['secondary_button_link'] ) && is_array( $settings['secondary_button_link'] ) ? $settings['secondary_button_link'] : array();
		$secondary_link['url'] = ! empty( $secondary_link['url'] ) && '#' !== $secondary_link['url'] ? $secondary_link['url'] : home_url( '/#pricing' );
		$this->add_link_attributes( 'hero_secondary_button', $secondary_link );
		$this->add_render_attribute( 'hero_secondary_button', 'class', 'hero-button hero-secondary-button' );
		?>
		<section id="hero" class="ukits-custom-element w-full">
			<div class="hero-frame relative w-full h-[862px]">
				<div class="hero-media absolute top-0 left-[578px] w-[578px] h-[862px]">
					<div class="hero-media-shell absolute top-0 left-0 w-[578px] h-[862px] [background:linear-gradient(117deg,rgba(16,24,40,1)_0%,rgba(0,0,0,1)_100%)]">
						<div class="hero-media-image absolute top-0 left-0 w-[578px] h-[862px] opacity-70 bg-cover bg-[50%_50%]" style="background-image:url('<?php echo esc_url( $hero_image_url ); ?>');"></div>
						<div class="hero-media-overlay absolute top-0 left-0 w-[578px] h-[862px] [background:linear-gradient(0deg,rgba(0,0,0,0.6)_0%,rgba(0,0,0,0)_50%,rgba(0,0,0,0)_100%)]"></div>
					</div>
					<div class="absolute top-[558px] -left-20 w-96 h-96 bg-[#48842b] rotate-45 opacity-10"></div>
				</div>
				<div class="hero-slant absolute -top-px left-[427px] w-32 h-[863px] bg-white"></div>
				<div class="hero-content flex w-[578px] h-[862px] items-center px-12 py-0 absolute top-0 left-0 bg-white">
					<div class="hero-content-inner relative flex-1 grow h-[851.59px]">
						<div class="hero-eyebrow absolute top-[102px] left-0 w-[372px] h-6 flex">
							<div class="hero-eyebrow-text mt-1 w-[365px] h-5 [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm tracking-[4.20px] leading-5 whitespace-nowrap">
								<?php echo esc_html( $settings['eyebrow'] ); ?>
							</div>
						</div>
						<div class="hero-title-wrap absolute top-[137px] left-0 w-[418px] h-[274px] flex">
							<h1 class="hero-title w-[363px] h-[274px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl tracking-[-1.80px] leading-[68.4px]">
								<span class="hero-title-main text-neutral-950 tracking-[-1.30px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
								<span class="hero-title-highlight text-[#48842b] tracking-[-1.30px]"><?php echo esc_html( $settings['title_line_2'] ); ?><br /></span>
								<span class="hero-title-main text-neutral-950 tracking-[-1.30px]"><?php echo nl2br( esc_html( $title_line_3 ) ); ?></span>
							</h1>
						</div>
						<div class="hero-copy-wrap absolute top-[420px] left-0 w-[418px] h-[117px] flex">
							<p class="hero-copy mt-[-0.5px] w-[418px] h-[117px] [font-family:'Inter-Regular',Helvetica] font-normal text-2xl tracking-[0] leading-[39px]">
								<span class="hero-copy-text text-[#4a5565]"><?php echo esc_html( $settings['description_before'] ); ?></span>
								<span class="hero-copy-highlight [font-family:'Inter-SemiBold',Helvetica] font-semibold text-black"> <?php echo esc_html( $settings['description_highlight'] ); ?></span>
								<span class="hero-copy-text text-[#4a5565]"> <?php echo esc_html( $settings['description_after'] ); ?></span>
							</p>
						</div>
						<div class="hero-actions flex w-[418px] h-[100px] items-start gap-6 absolute top-[561px] left-0">
							<a <?php $this->print_render_attribute_string( 'hero_primary_button' ); ?>>
								<span><?php echo esc_html( $settings['primary_button_text'] ); ?></span>
								<?php if ( 'yes' === $settings['show_primary_icon'] ) : ?>
									<img class="hero-primary-icon h-[18px] w-[18px]" src="<?php echo esc_url( $primary_icon ); ?>" alt="" />
								<?php endif; ?>
							</a>
							<a <?php $this->print_render_attribute_string( 'hero_secondary_button' ); ?>>
								<span><?php echo esc_html( $settings['secondary_button_text'] ); ?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

class UKITS_Custom_Element_Benefits_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'benefits';
	protected $widget_title = 'Benefits Section';

	/**
	 * Register Benefits-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'stats_section',
			array(
				'label' => esc_html__( 'Stats Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$stats = new Repeater();

		$stats->add_control(
			'icon',
			array(
				'label' => esc_html__( 'Icon', 'ukits-custom-element' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$stats->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Fully Accredited', 'ukits-custom-element' ),
			)
		);

		$stats->add_control(
			'subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Certified Instructors', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => esc_html__( 'Stats', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $stats->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/container.svg' ),
						'title'    => '4.9/5',
						'subtitle' => 'Average Rating',
					),
					array(
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/accredited-icon.svg' ),
						'title'    => 'Fully Accredited',
						'subtitle' => 'Certified Instructors',
					),
					array(
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/coverage-icon.svg' ),
						'title'    => 'UK-Wide Coverage',
						'subtitle' => 'On-Site & Training Centre',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'collage_section',
			array(
				'label' => esc_html__( 'Image Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$images = new Repeater();

		$images->add_control(
			'image',
			array(
				'label' => esc_html__( 'Image', 'ukits-custom-element' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$images->add_control(
			'tall',
			array(
				'label'        => esc_html__( 'Tall Image', 'ukits-custom-element' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'ukits-custom-element' ),
				'label_off'    => esc_html__( 'No', 'ukits-custom-element' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'collage_images',
			array(
				'label'       => esc_html__( 'Collage Images', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $images->get_controls(),
				'title_field' => esc_html__( 'Image', 'ukits-custom-element' ),
				'default'     => array(
					array(
						'image' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-warehouse-floor.jpg' ),
						'tall'  => '',
					),
					array(
						'image' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-welding.jpg' ),
						'tall'  => 'yes',
					),
					array(
						'image' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-warehouse-aisle.jpg' ),
						'tall'  => 'yes',
					),
				),
			)
		);

		$this->add_control(
			'pass_rate',
			array(
				'label'   => esc_html__( 'Pass Rate', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '98%',
			)
		);

		$this->add_control(
			'pass_rate_label',
			array(
				'label'   => esc_html__( 'Pass Rate Label', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'PASS RATE', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'WHY CHOOSE US', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'PROFESSIONAL', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'CERTIFICATION', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_3',
			array(
				'label'   => esc_html__( 'Title Line 3', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FAST TRACK', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'Everything you need to get certified quickly and confidently. Industry-leading training with nationally recognised qualifications.', 'ukits-custom-element' ),
			)
		);

		$benefits = new Repeater();

		$benefits->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Nationally recognised certification', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'benefit_items',
			array(
				'label'       => esc_html__( 'Benefits List', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $benefits->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'Nationally recognised certification' ),
					array( 'text' => 'All experience levels welcome' ),
					array( 'text' => 'Counterbalance & Reach Truck training' ),
					array( 'text' => 'Small groups for better learning' ),
					array( 'text' => 'Same-week availability' ),
					array( 'text' => '1-3 day completion' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_section',
			array(
				'label' => esc_html__( 'Button', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'CHECK AVAILABILITY', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#pricing' ),
				),
			)
		);

		$this->end_controls_section();

		$this->register_benefits_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_benefits_style_controls() {
		$this->start_controls_section(
			'benefits_text_style',
			array(
				'label' => esc_html__( 'Text Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #benefits .benefits-eyebrow-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0a0a0a',
				'selectors' => array(
					'{{WRAPPER}} #benefits .benefits-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #benefits .benefits-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} #benefits .benefits-heading',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'body_typography',
				'selector' => '{{WRAPPER}} #benefits .benefits-description, {{WRAPPER}} #benefits .benefits-list-text',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'benefits_image_style',
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
					'{{WRAPPER}} #benefits .benefits-bg-image' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_filters',
				'selector' => '{{WRAPPER}} #benefits .benefits-bg-image',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'benefits_button_style',
			array(
				'label' => esc_html__( 'Button Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} #benefits .benefits-button',
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #benefits .benefits-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #benefits .benefits-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'top'      => 15,
					'right'    => 40,
					'bottom'   => 15,
					'left'     => 40,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} #footer .footer-book' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Benefits section.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$images   = ! empty( $settings['collage_images'] ) && is_array( $settings['collage_images'] ) ? $settings['collage_images'] : array();
		$stats    = ! empty( $settings['stats'] ) && is_array( $settings['stats'] ) ? $settings['stats'] : array();
		$items    = ! empty( $settings['benefit_items'] ) && is_array( $settings['benefit_items'] ) ? $settings['benefit_items'] : array();

		$benefits_link = ! empty( $settings['button_link'] ) && is_array( $settings['button_link'] ) ? $settings['button_link'] : array();
		$benefits_link['url'] = ! empty( $benefits_link['url'] ) && '#' !== $benefits_link['url'] ? $benefits_link['url'] : home_url( '/#courses' );
		$this->add_link_attributes( 'benefits_button', $benefits_link );
		$this->add_render_attribute( 'benefits_button', 'class', 'benefits-button' );
		?>
		<section id="benefits" class="ukits-custom-element w-full">
			<div class="benefits-frame w-full h-[1020px] flex flex-col bg-white overflow-hidden">
				<div class="benefits-slant ml-[-24px] w-full h-32 mt-[-84.2px] bg-black rotate-[-2.00deg]"></div>
				<div class="benefits-stats flex h-[117px] w-full self-center relative mt-[14.2px] flex-col items-start pt-8 pb-px px-0 bg-white border-b border-[#E5E7EB] [border-bottom-style:solid]">
					<div class="benefits-stats-pad flex flex-col h-[52px] items-start px-8 py-0 relative self-stretch w-full">
						<div class="benefits-stats-row relative self-stretch w-full h-[52px] flex items-center justify-between">
							<?php foreach ( $stats as $stat ) : ?>
								<div class="benefits-stat flex items-center justify-center gap-3">
									<?php if ( ! empty( $stat['icon']['url'] ) ) : ?>
										<img class="max-h-12 w-auto" src="<?php echo esc_url( $stat['icon']['url'] ); ?>" alt="" />
									<?php endif; ?>
									<div class="flex flex-col items-start">
										<div class="[font-family:'Inter-Bold',Helvetica] font-bold text-neutral-950 text-xl tracking-[0] leading-7 whitespace-nowrap"><?php echo esc_html( $stat['title'] ); ?></div>
										<div class="[font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-sm tracking-[0] leading-5 whitespace-nowrap"><?php echo esc_html( $stat['subtitle'] ); ?></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="benefits-main ml-12 w-[1060px] mt-3.5 flex gap-20">
					<div class="benefits-collage mt-[116.7px] w-[490px] flex gap-4">
						<div class="benefits-collage-col flex w-[237px] h-[597.25px] relative flex-col items-start gap-4">
							<?php echo $this->render_benefits_image_card( $images, 0, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo $this->render_benefits_image_card( $images, 1, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<div class="benefits-collage-col benefits-collage-col-offset flex w-[237px] h-[597.25px] relative flex-col items-start gap-4 pt-12 pb-0 px-0">
							<?php echo $this->render_benefits_image_card( $images, 2, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<div class="benefits-rate-card flex h-[237px] items-center justify-center pl-[49.01px] pr-[49.02px] py-8 relative self-stretch w-full bg-[#48842b]">
								<div class="flex flex-col w-[138.98px] h-[88px] items-center gap-2 relative">
									<div class="[font-family:'Inter-Bold',Helvetica] font-bold text-white text-6xl text-center tracking-[0] leading-[60px] whitespace-nowrap"><?php echo esc_html( $settings['pass_rate'] ); ?></div>
									<div class="[font-family:'Inter-Regular',Helvetica] font-normal text-white text-sm text-center tracking-[1.40px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['pass_rate_label'] ); ?></div>
								</div>
							</div>
						</div>
					</div>
					<div class="benefits-copy w-[490px] h-[830.7px] relative">
						<div class="benefits-eyebrow top-0 w-[490px] h-5 flex absolute left-0">
							<div class="benefits-eyebrow-text mt-[0.5px] w-[172px] h-5 [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="benefits-heading-wrap absolute top-11 left-0 w-[490px] h-[205px] flex">
							<h2 class="benefits-heading w-[538px] h-[185px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-[64.9px] tracking-[-1.30px] leading-[61.6px]">
								<span class="benefits-title-main text-neutral-950 tracking-[-0.84px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
								<span class="benefits-title-highlight text-[#48842b] tracking-[-0.84px]"><?php echo esc_html( $settings['title_line_2'] ); ?><br /></span>
								<span class="benefits-title-main text-neutral-950 tracking-[-0.84px]"><?php echo esc_html( $settings['title_line_3'] ); ?></span>
							</h2>
						</div>
						<div class="benefits-description-wrap top-[249px] left-0 w-[490px] h-[98px] absolute flex">
							<p class="benefits-description -mt-px w-[490px] h-[98px] [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-xl tracking-[0] leading-[32.5px]"><?php echo esc_html( $settings['description'] ); ?></p>
						</div>
						<div class="benefits-list flex flex-col w-[490px] h-72 items-start gap-6 absolute top-[394px] left-0">
							<?php foreach ( $items as $item ) : ?>
								<div class="relative self-stretch w-full h-7">
									<div class="absolute top-3 left-0 w-2 h-2 bg-[#48842b]"></div>
									<div class="top-0 left-6 absolute flex">
										<div class="benefits-list-text mt-[-0.5px] [font-family:'Inter-Regular',Helvetica] font-normal text-[#1e2939] text-xl tracking-[0] leading-7 whitespace-nowrap"><?php echo esc_html( $item['text'] ); ?></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<a <?php $this->print_render_attribute_string( 'benefits_button' ); ?>>
							<?php echo esc_html( $settings['button_text'] ); ?>
						</a>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Render a collage image card.
	 *
	 * @param array $images Image repeater data.
	 * @param int   $index  Image index.
	 * @param bool  $tall   Whether card is tall.
	 * @return string
	 */
	private function render_benefits_image_card( $images, $index, $tall ) {
		$defaults = array(
			UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-warehouse-floor.jpg',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-welding.jpg',
			UKITS_CUSTOM_ELEMENT_URL . 'assets/img/benefits-warehouse-aisle.jpg',
		);
		$url      = ! empty( $images[ $index ]['image']['url'] ) ? $images[ $index ]['image']['url'] : $defaults[ $index ];
		$height   = $tall ? 'h-[296.25px]' : 'h-[237px]';

		return sprintf(
			'<div class="benefits-image-card %1$s flex flex-col items-start relative self-stretch w-full bg-[#101828]"><div class="benefits-bg-image %1$s relative self-stretch w-full bg-cover bg-[50%%_50%%]" style="background-image: url(%2$s);"></div></div>',
			esc_attr( $height ),
			esc_url( $url )
		);
	}
}

class UKITS_Custom_Element_HowItWorks_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'howitworks';
	protected $widget_title = 'HowItWorks Section';

	/**
	 * Register HowItWorks-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'SIMPLE PROCESS', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'HOW IT', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'WORKS', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'process_section',
			array(
				'label' => esc_html__( 'Process Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$process = new Repeater();

		$process->add_control(
			'number',
			array(
				'label'   => esc_html__( 'Number', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '01',
			)
		);

		$process->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'BOOK', 'ukits-custom-element' ),
			)
		);

		$process->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'CHOOSE YOUR COURSE & DATE', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'process_items',
			array(
				'label'       => esc_html__( 'Process Items', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $process->get_controls(),
				'title_field' => '{{{ number }}} - {{{ title }}}',
				'default'     => array(
					array(
						'number'      => '01',
						'title'       => 'BOOK',
						'description' => 'CHOOSE YOUR COURSE & DATE',
					),
					array(
						'number'      => '02',
						'title'       => 'TRAIN',
						'description' => 'HANDS-ON PRACTICAL LEARNING',
					),
					array(
						'number'      => '03',
						'title'       => 'ASSESS',
						'description' => 'DEMONSTRATE YOUR SKILLS',
					),
					array(
						'number'      => '04',
						'title'       => 'CERTIFIED',
						'description' => 'RECEIVE QUALIFICATION',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cta_section',
			array(
				'label' => esc_html__( 'Button', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'START YOUR JOURNEY', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#courses' ),
				),
			)
		);

		$this->end_controls_section();

		$this->register_howitworks_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_howitworks_style_controls() {
		$this->start_controls_section(
			'heading_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .how-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0a0a0a',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .how-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .how-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} #howitworks .how-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'process_style',
			array(
				'label' => esc_html__( 'Repeater Card Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_background',
			array(
				'label'     => esc_html__( 'Card Background', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #howitworks .process-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_hover_background',
			array(
				'label'     => esc_html__( 'Card Hover Background', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .process-card:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Number Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f4f6',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .process-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'process_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0a0a0a',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .process-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'process_description_color',
			array(
				'label'     => esc_html__( 'Description Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4a5565',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .process-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'process_title_typography',
				'selector' => '{{WRAPPER}} #howitworks .process-title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'process_description_typography',
				'selector' => '{{WRAPPER}} #howitworks .process-description',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} #howitworks .process-card',
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
				'selector' => '{{WRAPPER}} #howitworks .how-cta',
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .how-cta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #howitworks .how-cta' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render HowItWorks section.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['process_items'] ) && is_array( $settings['process_items'] ) ? $settings['process_items'] : array();

		$how_cta_link = ! empty( $settings['button_link'] ) && is_array( $settings['button_link'] ) ? $settings['button_link'] : array();
		$how_cta_link['url'] = ! empty( $how_cta_link['url'] ) && '#' !== $how_cta_link['url'] ? $how_cta_link['url'] : home_url( '/#courses' );
		$this->add_link_attributes( 'how_cta', $how_cta_link );
		$this->add_render_attribute( 'how_cta', 'class', 'how-cta' );
		?>
		<section id="howitworks" class="ukits-custom-element w-[1156px]">
			<div class="how-frame relative w-[1156px] h-[1061px] bg-white overflow-hidden">
				<div class="how-content absolute top-32 left-0 w-[1156px] h-[707px] flex flex-col">
					<div class="how-heading-block flex ml-12 w-[1060px] h-[180.8px] relative flex-col items-start gap-6">
						<div class="how-eyebrow-wrap relative self-stretch w-full h-5">
							<div class="how-eyebrow absolute top-px left-[442px] [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm text-center tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="how-title-wrap relative self-stretch w-full h-[136.8px]">
							<h2 class="how-title absolute top-0 left-[389px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl text-center tracking-[-1.44px] leading-[68.4px]">
								<span class="how-title-main text-neutral-950 tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
								<span class="how-title-highlight text-[#48842b] tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_2'] ); ?></span>
							</h2>
						</div>
					</div>
					<div class="how-cards ml-12 w-[1060px] h-[306px] mt-20 flex border-t border-l border-[#E5E7EB] [border-top-style:solid] [border-left-style:solid]">
						<?php foreach ( $items as $index => $item ) : ?>
							<div class="process-card mt-px w-[264.75px] h-[305px] <?php echo 0 === $index ? 'ml-px' : ''; ?> flex flex-col border-r border-b border-[#E5E7EB] [border-right-style:solid] [border-bottom-style:solid]">
								<div class="ml-12 w-[167.75px] mt-12 flex">
									<div class="process-number w-[132px] h-24 [font-family:'Inter-Bold',Helvetica] font-bold text-gray-100 text-8xl tracking-[0] leading-[96px] whitespace-nowrap"><?php echo esc_html( $item['number'] ); ?></div>
								</div>
								<div class="ml-12 w-[167.75px] mt-6 flex">
									<div class="process-title mt-[-0.5px] w-[162px] h-9 [font-family:'Montserrat-Bold',Helvetica] font-bold text-neutral-950 text-3xl tracking-[-0.60px] leading-9 whitespace-nowrap"><?php echo esc_html( $item['title'] ); ?></div>
								</div>
								<div class="ml-12 w-[167.75px] mt-3 flex">
									<div class="process-description mt-[0.5px] w-[168px] h-10 [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-sm tracking-[0.70px] leading-5"><?php echo esc_html( $item['description'] ); ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="how-cta-wrap flex ml-12 w-[1060px] h-[76px] relative mt-16 flex-col items-start px-[371.25px] py-0">
						<a <?php $this->print_render_attribute_string( 'how_cta' ); ?>>
							<span class="how-cta-label [font-family:'Inter-SemiBold',Helvetica] font-semibold text-white text-lg text-center tracking-[0.90px] leading-7 whitespace-nowrap"><?php echo esc_html( $settings['button_text'] ); ?></span>
						</a>
					</div>
				</div>
				<div class="how-decor absolute top-[387px] left-[964px] w-96 h-96 bg-[#48842b] rounded-[16777200px] opacity-5"></div>
			</div>
		</section>
		<?php
	}
}

class UKITS_Custom_Element_WhyChooseUs_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'whychooseus';
	protected $widget_title = 'WhyChooseUs Section';

	/**
	 * Register WhyChooseUs-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'EXCELLENCE & TRUST', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '15+ YEARS', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Line 2', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'OF INDUSTRY', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_highlight',
			array(
				'label'   => esc_html__( 'Title Highlight', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'LEADERSHIP', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'stats_section',
			array(
				'label' => esc_html__( 'Stats Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$stats = new Repeater();

		$stats->add_control(
			'value',
			array(
				'label'   => esc_html__( 'Value', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '5000+',
			)
		);

		$stats->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'CERTIFIED OPERATORS', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => esc_html__( 'Stats', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $stats->get_controls(),
				'title_field' => '{{{ value }}} - {{{ label }}}',
				'default'     => array(
					array(
						'value' => '5000+',
						'label' => 'CERTIFIED OPERATORS',
					),
					array(
						'value' => '98%',
						'label' => 'FIRST-TIME PASS RATE',
					),
					array(
						'value' => '100%',
						'label' => 'ACCREDITED TRAINING',
					),
					array(
						'value' => '24/7',
						'label' => 'SUPPORT AVAILABLE',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'checklist_section',
			array(
				'label' => esc_html__( 'Checklist Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$checks = new Repeater();

		$checks->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'EXPERIENCED ACCREDITED INSTRUCTORS', 'ukits-custom-element' ),
			)
		);

		$checks->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg',
				),
			)
		);

		$this->add_control(
			'check_items',
			array(
				'label'       => esc_html__( 'Checklist Items', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $checks->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'text' => 'EXPERIENCED ACCREDITED INSTRUCTORS',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
					array(
						'text' => 'HIGH FIRST-TIME PASS RATES',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
					array(
						'text' => 'FLEXIBLE DATES INCLUDING WEEKENDS',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
					array(
						'text' => 'TRANSPARENT PRICING, NO HIDDEN COSTS',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
					array(
						'text' => 'ON-SITE BUSINESS TRAINING AVAILABLE',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
					array(
						'text' => 'COVERAGE ACROSS THE ENTIRE UK',
						'icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->register_whychooseus_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_whychooseus_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Section Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-frame' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffffcc',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} #whychooseus .why-heading',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'stats_style',
			array(
				'label' => esc_html__( 'Stats Repeater Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'stat_value_color',
			array(
				'label'     => esc_html__( 'Value Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-stat-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stat_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffffe6',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-stat-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stat_value_typography',
				'selector' => '{{WRAPPER}} #whychooseus .why-stat-value',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'checklist_style',
			array(
				'label' => esc_html__( 'Checklist Repeater Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'check_card_background',
			array(
				'label'     => esc_html__( 'Card Background', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000033',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-check-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'check_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #whychooseus .why-check-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'check_typography',
				'selector' => '{{WRAPPER}} #whychooseus .why-check-text',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'check_card_border',
				'selector' => '{{WRAPPER}} #whychooseus .why-check-card',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render WhyChooseUs section.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$stats    = ! empty( $settings['stats'] ) && is_array( $settings['stats'] ) ? $settings['stats'] : array();
		$checks   = ! empty( $settings['check_items'] ) && is_array( $settings['check_items'] ) ? $settings['check_items'] : array();
		?>
		<section id="whychooseus" class="ukits-custom-element w-[1156px]">
			<div class="why-frame relative w-[1156px] h-[910px] bg-[#48842b] overflow-hidden">
				<div class="why-top-slant absolute -top-11 -left-8 w-[1220px] h-32 bg-white rotate-[-2.00deg]"></div>
				<div class="why-content absolute top-32 left-12 w-[1060px] h-[696px] flex gap-20">
					<div class="why-left mt-[83.4px] w-[490px] h-[529.2px] flex flex-col">
						<div class="why-eyebrow-wrap w-[490px] flex">
							<div class="why-eyebrow mt-[0.5px] w-[226px] h-5 [font-family:'Inter-Bold',Helvetica] font-bold text-[#ffffffcc] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="why-heading-wrap w-[490px] h-[205.2px] mt-6 flex">
							<h2 class="why-heading w-[493px] h-[206px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl tracking-[-1.44px] leading-[68.4px]">
								<span class="why-title-main text-white tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /><?php echo esc_html( $settings['title_line_2'] ); ?><br /></span>
								<span class="why-title-highlight text-black tracking-[-1.04px]"><?php echo esc_html( $settings['title_highlight'] ); ?></span>
							</h2>
						</div>
						<div class="why-stats h-[184px] relative mt-12">
							<?php foreach ( $stats as $index => $stat ) : ?>
								<?php $position_class = $this->get_why_stat_position_class( $index ); ?>
								<div class="why-stat flex flex-col w-[229px] h-[76px] items-start gap-2 pl-7 pr-0 py-0 absolute <?php echo esc_attr( $position_class ); ?> border-l-4 [border-left-style:solid] border-black">
									<div class="why-stat-value [font-family:'Inter-Bold',Helvetica] font-bold text-white text-5xl tracking-[0] leading-[48px] whitespace-nowrap"><?php echo esc_html( $stat['value'] ); ?></div>
									<div class="why-stat-label [font-family:'Inter-Regular',Helvetica] font-normal text-[#ffffffe6] text-sm tracking-[0.70px] leading-5 whitespace-nowrap"><?php echo esc_html( $stat['label'] ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="why-list flex w-[490px] h-[696px] relative flex-col items-start gap-6">
						<?php foreach ( $checks as $check ) : ?>
							<div class="why-check-card flex flex-col min-h-20 items-start pl-7 pr-6 pt-6 pb-6 relative self-stretch w-full bg-[#00000033] border-l-4 [border-left-style:solid] border-white">
								<div class="flex items-center justify-between gap-6 relative self-stretch w-full">
									<div class="why-check-text flex-1 [font-family:'Inter-Regular',Helvetica] font-normal text-white text-xl tracking-[0.50px] leading-7">
										<?php echo esc_html( $check['text'] ); ?>
									</div>
									<div class="flex w-8 h-8 shrink-0 items-center justify-center border-2 border-solid border-white">
										<img class="h-4 w-4" src="<?php echo esc_url( ! empty( $check['icon']['url'] ) ? $check['icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/check-white.svg' ); ?>" alt="" />
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="why-decor absolute top-[624px] left-10 w-72 h-72 border-4 border-solid border-[#ffffff33] rotate-[12.00deg]"></div>
				<div class="why-bottom-slant absolute top-[868px] left-0 w-[1157px] h-32 bg-white rotate-[-2.00deg]"></div>
			</div>
		</section>
		<?php
	}

	/**
	 * Get desktop absolute position class for a stat.
	 *
	 * @param int $index Stat index.
	 * @return string
	 */
	private function get_why_stat_position_class( $index ) {
		$positions = array(
			'top-0 left-0',
			'top-0 left-[261px]',
			'top-[108px] left-0',
			'top-[108px] left-[261px]',
		);

		return isset( $positions[ $index ] ) ? $positions[ $index ] : 'relative';
	}
}

class UKITS_Custom_Element_Courses_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'courses';
	protected $widget_title = 'Courses Section';

	/**
	 * Register Courses-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'OUR PROGRAMS', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'TRAINING', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'COURSES', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'courses_repeater_section',
			array(
				'label' => esc_html__( 'Courses Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$courses = new Repeater();

		$courses->add_control(
			'image',
			array(
				'label' => esc_html__( 'Image', 'ukits-custom-element' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$courses->add_control(
			'duration',
			array(
				'label'   => esc_html__( 'Duration', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '1-3 DAYS', 'ukits-custom-element' ),
			)
		);

		$courses->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FROM £299', 'ukits-custom-element' ),
			)
		);

		$courses->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'COUNTERBALANCE', 'ukits-custom-element' ),
			)
		);

		$courses->add_control(
			'subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'TRAINING', 'ukits-custom-element' ),
			)
		);

		$courses->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#courses' ),
				),
			)
		);

		$courses->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg',
				),
			)
		);

		$this->add_control(
			'courses',
			array(
				'label'       => esc_html__( 'Course Cards', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $courses->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'image'    => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/course-logistics-yard.jpg' ),
						'duration' => '1-3 DAYS',
						'price'    => 'FROM £299',
						'title'    => 'COUNTERBALANCE',
						'subtitle' => 'TRAINING',
						'link'     => array( 'url' => home_url( '/#pricing' ) ),
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg' ),
					),
					array(
						'image'    => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/course-training-floor.jpg' ),
						'duration' => '2-3 DAYS',
						'price'    => 'FROM £349',
						'title'    => 'REACH TRUCK',
						'subtitle' => 'TRAINING',
						'link'     => array( 'url' => home_url( '/#pricing' ) ),
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg' ),
					),
					array(
						'image'    => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/course-training-floor.jpg' ),
						'duration' => '1 DAY',
						'price'    => 'FROM £199',
						'title'    => 'REFRESHER',
						'subtitle' => 'COURSES',
						'link'     => array( 'url' => home_url( '/#pricing' ) ),
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg' ),
					),
					array(
						'image'    => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/course-industrial.jpg' ),
						'duration' => 'FLEXIBLE',
						'price'    => 'CUSTOM QUOTE',
						'title'    => 'ON-SITE',
						'subtitle' => 'BUSINESS TRAINING',
						'link'     => array( 'url' => home_url( '/#pricing' ) ),
						'icon'     => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->register_courses_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_courses_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Section Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #courses .courses-frame' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #courses .courses-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #courses .courses-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #courses .courses-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} #courses .courses-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style',
			array(
				'label' => esc_html__( 'Course Repeater Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_overlay',
			array(
				'label'     => esc_html__( 'Overlay Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.6)',
				'selectors' => array(
					'{{WRAPPER}} #courses .course-overlay' => 'background: linear-gradient(0deg, rgba(0,0,0,1) 0%, {{VALUE}} 50%, rgba(0,0,0,0.2) 100%);',
				),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Meta Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffffcc',
				'selectors' => array(
					'{{WRAPPER}} #courses .course-duration' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #courses .course-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'vat_typography',
				'label'          => esc_html__( 'VAT Typography', 'ukits-custom-element' ),
				'selector'       => '{{WRAPPER}} #courses .course-vat',
				'fields_options' => array(
					'font_size'   => array(
						'default' => array(
							'size' => 16,
							'unit' => 'px',
						),
					),
					'font_style'  => array(
						'default' => 'italic',
					),
					'font_weight' => array(
						'default' => '600',
					),
				),
			)
		);

		$this->add_control(
			'course_title_color',
			array(
				'label'     => esc_html__( 'Course Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #courses .course-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'course_subtitle_color',
			array(
				'label'     => esc_html__( 'Course Subtitle Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffffe6',
				'selectors' => array(
					'{{WRAPPER}} #courses .course-subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'course_title_typography',
				'selector' => '{{WRAPPER}} #courses .course-title',
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'course_image_filters',
				'selector' => '{{WRAPPER}} #courses .course-bg',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Courses section.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$courses  = ! empty( $settings['courses'] ) && is_array( $settings['courses'] ) ? $settings['courses'] : array();
		?>
		<section id="courses" class="ukits-custom-element w-[1156px]">
			<div class="courses-frame relative w-[1156px] h-[1253px] bg-black">
				<div class="courses-content flex flex-col w-[1156px] h-[997px] items-start gap-20 px-12 py-0 absolute top-32 left-0">
					<div class="courses-heading-block flex flex-col h-[180.8px] items-start gap-6 relative self-stretch w-full">
						<div class="courses-eyebrow-wrap relative self-stretch w-full h-5">
							<div class="courses-eyebrow absolute top-px left-[449px] [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm text-center tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="courses-title-wrap h-[136.8px] relative self-stretch w-full">
							<h2 class="courses-title absolute top-0 left-[353px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl text-center tracking-[-1.44px] leading-[68.4px]">
								<span class="courses-title-main text-white tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
								<span class="courses-title-highlight text-[#48842b] tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_2'] ); ?></span>
							</h2>
						</div>
					</div>
					<div class="courses-grid relative self-stretch w-full h-[736.25px]">
						<?php foreach ( $courses as $index => $course ) : ?>
							<?php
							$link = ! empty( $course['link']['url'] ) && '#' !== $course['link']['url'] ? $course['link']['url'] : home_url( '/#pricing' );
							?>
							<a href="<?php echo esc_url( $link ); ?>" class="course-card <?php echo esc_attr( $this->get_course_position_class( $index ) ); ?> w-[518px] h-[356px] bg-white">
								<div class="course-bg absolute top-0 left-0 w-[518px] h-[356px] flex bg-cover bg-[50%_50%]" style="background-image: url(<?php echo esc_url( ! empty( $course['image']['url'] ) ? $course['image']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/course-logistics-yard.jpg' ); ?>);">
									<div class="course-overlay w-[518px] h-[356.12px] [background:linear-gradient(0deg,rgba(0,0,0,1)_0%,rgba(0,0,0,0.6)_50%,rgba(0,0,0,0.2)_100%)]"></div>
								</div>
								<div class="course-content flex flex-col w-[518px] h-[356px] items-start justify-between p-12 absolute top-0 left-0">
									<div class="flex w-[422px] h-16 items-start justify-between relative">
										<div class="flex flex-col h-16 items-start gap-2 relative">
											<div class="course-duration relative self-stretch w-full h-5 [font-family:'Inter-Regular',Helvetica] font-normal text-[#ffffffcc] text-sm tracking-[2.80px] leading-5 whitespace-nowrap"><?php echo esc_html( $course['duration'] ); ?></div>
											<div class="course-price relative w-fit mt-[-1.00px] [font-family:'Inter-Bold',Helvetica] font-bold text-white text-3xl tracking-[0] leading-9 whitespace-nowrap"><?php echo $this->render_course_price( $course['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
										</div>
										<div class="course-icon flex w-12 h-12 items-center justify-center relative border-2 border-solid border-white">
											<img class="h-5 w-5" src="<?php echo esc_url( ! empty( $course['icon']['url'] ) ? $course['icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/arrow-right-white.svg' ); ?>" alt="" />
										</div>
									</div>
									<div class="flex flex-col w-[422px] items-start gap-2 relative">
										<div class="course-title [font-family:'Montserrat-Bold',Helvetica] font-bold text-white text-6xl tracking-[-1.20px] leading-[60px]"><?php echo nl2br( esc_html( $course['title'] ) ); ?></div>
										<div class="course-subtitle [font-family:'Inter-Regular',Helvetica] font-normal text-[#ffffffe6] text-2xl tracking-[1.20px] leading-8 whitespace-nowrap"><?php echo esc_html( $course['subtitle'] ); ?></div>
									</div>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="courses-decor absolute top-20 left-[860px] w-64 h-64 border border-solid border-[#48842b33] rotate-45"></div>
			</div>
		</section>
		<?php
	}

	/**
	 * Get desktop absolute position class for a course.
	 *
	 * @param int $index Course index.
	 * @return string
	 */
	private function get_course_position_class( $index ) {
		$positions = array(
			'absolute top-0 left-0',
			'absolute top-0 left-[542px]',
			'absolute top-[380px] left-0',
			'absolute top-[380px] left-[542px]',
		);

		return isset( $positions[ $index ] ) ? $positions[ $index ] : 'relative';
	}

	/**
	 * Render the VAT suffix separately so it can have its own typography.
	 *
	 * @param string $price Course price text.
	 * @return string
	 */
	private function render_course_price( $price ) {
		$price = trim( (string) $price );

		if ( preg_match( '/^(.*?)(\s*\+VAT)$/i', $price, $matches ) ) {
			return '<span class="course-price-main">' . esc_html( trim( $matches[1] ) ) . '</span><span class="course-vat">+VAT</span>';
		}

		return '<span class="course-price-main">' . esc_html( $price ) . '</span>';
	}
}

class UKITS_Custom_Element_Testimonials_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'testimonials';
	protected $widget_title = 'Testimonials Section';

	/**
	 * Register Testimonials-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'SUCCESS STORIES', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'WHAT OUR', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'STUDENTS', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_3',
			array(
				'label'   => esc_html__( 'Title Line 3', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'SAY', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'testimonials_section',
			array(
				'label' => esc_html__( 'Testimonials Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$testimonials = new Repeater();

		$testimonials->add_control(
			'quote_icon',
			array(
				'label'   => esc_html__( 'Quote Icon', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/quote-green.svg',
				),
			)
		);

		$testimonials->add_control(
			'quote',
			array(
				'label'   => esc_html__( 'Quote Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'From zero experience to certified in 2 days. Outstanding instructors.', 'ukits-custom-element' ),
			)
		);

		$testimonials->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'JAMES MITCHELL', 'ukits-custom-element' ),
			)
		);

		$testimonials->add_control(
			'role',
			array(
				'label'   => esc_html__( 'Role', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'WAREHOUSE OPERATOR', 'ukits-custom-element' ),
			)
		);

		$testimonials->add_control(
			'location',
			array(
				'label'   => esc_html__( 'Location', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'MANCHESTER', 'ukits-custom-element' ),
			)
		);

		$testimonials->add_control(
			'stars_count',
			array(
				'label'   => esc_html__( 'Stars Count', 'ukits-custom-element' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 5,
				'step'    => 1,
				'default' => 5,
			)
		);

		$testimonials->add_control(
			'rating_text',
			array(
				'label'   => esc_html__( 'Rating Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '4.9/5', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'testimonials',
			array(
				'label'       => esc_html__( 'Testimonials', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $testimonials->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'quote_icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/quote-green.svg' ),
						'quote'      => 'From zero experience to certified in 2 days. Outstanding instructors.',
						'name'       => 'JAMES MITCHELL',
						'role'       => 'WAREHOUSE OPERATOR',
						'location'   => 'MANCHESTER',
						'stars_count' => 5,
						'rating_text' => '4.9/5',
					),
					array(
						'quote_icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/quote-green.svg' ),
						'quote'      => 'Trained 20+ staff. Everyone passed first time. Exceptional service.',
						'name'       => 'SARAH THOMPSON',
						'role'       => 'LOGISTICS MANAGER',
						'location'   => 'BIRMINGHAM',
						'stars_count' => 5,
						'rating_text' => '4.9/5',
					),
					array(
						'quote_icon' => array( 'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/quote-green.svg' ),
						'quote'      => 'Professional, efficient, great value. Same-week availability sealed it.',
						'name'       => 'DAVID CHEN',
						'role'       => 'CERTIFIED OPERATOR',
						'location'   => 'LONDON',
						'stars_count' => 5,
						'rating_text' => '4.9/5',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->register_testimonials_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_testimonials_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Section Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonials-frame' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonials-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonials-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonials-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} #testimonials .testimonials-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'card_style',
			array(
				'label' => esc_html__( 'Testimonials Repeater Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_background',
			array(
				'label'     => esc_html__( 'Card Background', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff0d',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonial-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'quote_color',
			array(
				'label'     => esc_html__( 'Quote Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonial-quote' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Name Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonial-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'location_color',
			array(
				'label'     => esc_html__( 'Location Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #testimonials .testimonial-location' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} #testimonials .testimonial-card',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Remove accidental duplicated testimonial quote text.
	 *
	 * @param string $text Quote text.
	 * @return string
	 */
	private function normalize_testimonial_quote( $text ) {
		$text       = trim( wp_strip_all_tags( (string) $text ) );
		$normalized = preg_replace( '/\s+/', ' ', $text );
		$length     = strlen( $normalized );

		if ( preg_match( '/^(.+?[.!?])\s+\1$/u', $normalized, $matches ) ) {
			return trim( $matches[1] );
		}

		$sentences = preg_split( '/(?<=[.!?])\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY );

		if ( is_array( $sentences ) && count( $sentences ) > 1 && 0 === count( $sentences ) % 2 ) {
			$half_sentences = (int) ( count( $sentences ) / 2 );
			$first_half     = array_slice( $sentences, 0, $half_sentences );
			$second_half    = array_slice( $sentences, $half_sentences );

			if ( $first_half === $second_half ) {
				return trim( implode( ' ', $first_half ) );
			}
		}

		if ( 0 === $length || 0 !== $length % 2 ) {
			return $text;
		}

		$half = (int) ( $length / 2 );

		if ( substr( $normalized, 0, $half ) === substr( $normalized, $half ) ) {
			return trim( substr( $normalized, 0, $half ) );
		}

		return $text;
	}

	/**
	 * Render Testimonials section.
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$testimonials = ! empty( $settings['testimonials'] ) && is_array( $settings['testimonials'] ) ? $settings['testimonials'] : array();
		?>
		<section id="testimonials" class="ukits-custom-element w-[1156px]">
			<div class="testimonials-frame relative w-[1156px] h-[1002px] bg-black">
				<div class="testimonials-content flex flex-col w-[1156px] h-[746px] items-start gap-20 px-12 py-0 absolute top-32 left-0">
					<div class="testimonials-heading-block flex flex-col h-[249.2px] items-start gap-6 relative self-stretch w-full">
						<div class="testimonials-eyebrow-wrap relative self-stretch w-full h-5">
							<div class="testimonials-eyebrow absolute top-px left-0 [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="testimonials-top relative self-stretch w-full h-[205.2px]">
							<div class="testimonials-title-wrap absolute top-0 left-0 w-[506px] h-[205px] flex">
								<h2 class="testimonials-title w-[410px] h-[231px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl tracking-[-1.44px] leading-[77px]">
									<span class="testimonials-title-main text-white tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
									<span class="testimonials-title-highlight text-[#48842b] tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_2'] ); ?><br /></span>
									<span class="testimonials-title-main text-white tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_3'] ); ?></span>
								</h2>
							</div>
						</div>
					</div>
					<div class="testimonials-carousel-shell">
						<?php if ( count( $testimonials ) > 1 ) : ?>
							<button class="testimonial-arrow testimonial-arrow-prev" type="button" aria-label="<?php echo esc_attr__( 'Previous testimonial', 'ukits-custom-element' ); ?>"><span aria-hidden="true">&#8592;</span></button>
						<?php endif; ?>
						<div class="testimonials-grid relative self-stretch w-full h-[416.5px]">
							<?php foreach ( $testimonials as $index => $testimonial ) : ?>
								<?php
								$quote       = $this->normalize_testimonial_quote( ! empty( $testimonial['quote'] ) ? $testimonial['quote'] : '' );
								$stars_count = isset( $testimonial['stars_count'] ) ? absint( $testimonial['stars_count'] ) : 5;
								$stars_count = min( 5, max( 0, $stars_count ) );
								$rating_text = ! empty( $testimonial['rating_text'] ) ? $testimonial['rating_text'] : '4.9/5';
								$stars       = str_repeat( '&#9733;', $stars_count );
								?>
								<div class="testimonial-card w-[337px] h-[416px] bg-[#ffffff0d] border border-solid border-[#ffffff1a]">
									<img class="absolute top-[43px] left-[33px] h-6 w-7" src="<?php echo esc_url( ! empty( $testimonial['quote_icon']['url'] ) ? $testimonial['quote_icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/quote-green.svg' ); ?>" alt="" />
									<div class="testimonial-quote-wrap absolute top-[153px] left-[33px] w-[271px] h-[98px] flex">
										<p class="testimonial-quote -mt-px w-[272px] h-[98px] [font-family:'Inter-Regular',Helvetica] font-normal text-white text-xl tracking-[0] leading-[32.5px]"><?php echo esc_html( $quote ); ?></p>
									</div>
									<div class="testimonial-author flex flex-col w-[271px] h-[101px] items-start gap-1 pt-[25px] pb-0 px-0 absolute top-[282px] left-[33px] border-t [border-top-style:solid] border-[#ffffff33]">
										<div class="testimonial-name [font-family:'Inter-Bold',Helvetica] font-bold text-white text-lg tracking-[0.90px] leading-7 whitespace-nowrap"><?php echo esc_html( $testimonial['name'] ); ?></div>
										<div class="testimonial-role [font-family:'Inter-Regular',Helvetica] font-normal text-[#99a1af] text-sm tracking-[0.35px] leading-5 whitespace-nowrap"><?php echo esc_html( $testimonial['role'] ); ?></div>
										<div class="testimonial-location [font-family:'Inter-Regular',Helvetica] font-normal text-[#48842b] text-sm tracking-[0.35px] leading-5 whitespace-nowrap"><?php echo esc_html( $testimonial['location'] ); ?></div>
									</div>
									<div class="testimonial-rating" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: testimonial rating. */ __( 'Rated %s', 'ukits-custom-element' ), $rating_text ) ); ?>">
										<span class="testimonial-stars" aria-hidden="true"><?php echo wp_kses_post( $stars ); ?></span>
										<span class="testimonial-rating-value"><?php echo esc_html( $rating_text ); ?></span>
									</div>
									<div class="testimonial-corner absolute top-px left-px w-16 h-16 border-t-4 [border-top-style:solid] border-l-4 [border-left-style:solid] border-[#48842b]"></div>
								</div>
							<?php endforeach; ?>
						</div>
						<?php if ( count( $testimonials ) > 1 ) : ?>
							<button class="testimonial-arrow testimonial-arrow-next" type="button" aria-label="<?php echo esc_attr__( 'Next testimonial', 'ukits-custom-element' ); ?>"><span aria-hidden="true">&#8594;</span></button>
						<?php endif; ?>
					</div>
					<?php if ( count( $testimonials ) > 1 ) : ?>
						<div class="testimonials-dots" aria-label="<?php echo esc_attr__( 'Testimonials carousel navigation', 'ukits-custom-element' ); ?>">
							<?php foreach ( $testimonials as $index => $testimonial ) : ?>
								<button class="testimonial-dot" type="button" data-testimonial-index="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number. */ __( 'Show testimonial %d', 'ukits-custom-element' ), $index + 1 ) ); ?>"></button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="testimonials-decor absolute top-0 left-[578px] w-[578px] h-[1002px] [background:linear-gradient(63deg,rgba(72,132,43,1)_25%,rgba(0,0,0,0)_25%),linear-gradient(297deg,rgba(72,132,43,1)_25%,rgba(0,0,0,0)_25%)] opacity-5"></div>
			</div>
		</section>
		<?php
	}

	/**
	 * Get desktop absolute position class for testimonial.
	 *
	 * @param int $index Testimonial index.
	 * @return string
	 */
	private function get_testimonial_position_class( $index ) {
		$positions = array(
			'absolute top-0 left-0',
			'absolute top-0 left-[361px]',
			'absolute top-0 left-[723px]',
		);

		return isset( $positions[ $index ] ) ? $positions[ $index ] : 'relative';
	}
}

class UKITS_Custom_Element_Pricing_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'pricing';
	protected $widget_title = 'Pricing Section';

	/**
	 * Register Pricing-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'pricing_cards_section',
			array(
				'label' => esc_html__( 'Pricing Cards', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$cards = new Repeater();

		$cards->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'COUNTERBALANCE', 'ukits-custom-element' ),
			)
		);

		$cards->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '£299', 'ukits-custom-element' ),
			)
		);

		$cards->add_control(
			'note',
			array(
				'label'   => esc_html__( 'Note', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'STARTING PRICE', 'ukits-custom-element' ),
			)
		);

		$cards->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Card Style', 'ukits-custom-element' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => array(
					'dark'    => esc_html__( 'Black', 'ukits-custom-element' ),
					'green'   => esc_html__( 'Green', 'ukits-custom-element' ),
					'outline' => esc_html__( 'Outline', 'ukits-custom-element' ),
				),
			)
		);

		$this->add_control(
			'pricing_cards',
			array(
				'label'       => esc_html__( 'Cards', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $cards->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'label' => 'COUNTERBALANCE',
						'price' => '£299',
						'note'  => 'STARTING PRICE',
						'style' => 'dark',
					),
					array(
						'label' => 'REFRESHER COURSE',
						'price' => '£199',
						'note'  => 'STARTING PRICE',
						'style' => 'green',
					),
					array(
						'label' => 'BUSINESS TRAINING',
						'price' => 'CUSTOM',
						'note'  => 'TAILORED QUOTE',
						'style' => 'outline',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pricing_heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'GET STARTED', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'fast_text',
			array(
				'label'   => esc_html__( 'Fast Box Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FAST', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Line 2', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'NO-OBLIGATION', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_highlight',
			array(
				'label'   => esc_html__( 'Title Highlight', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'QUOTE', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'Transparent pricing with no hidden costs. Get your personalised quote today and start your certification journey.', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pricing_cta_section',
			array(
				'label' => esc_html__( 'Contact Form', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'contact_form_id',
			array(
				'label'       => esc_html__( 'Contact Form 7 Form', 'ukits-custom-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '107',
				'options'     => $this->get_contact_form_options(),
				'description' => esc_html__( 'Create or edit forms under Contact > Contact Forms.', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->register_pricing_style_controls();
	}

	/**
	 * Register Pricing style controls.
	 */
	private function register_pricing_style_controls() {
		$this->start_controls_section(
			'fast_box_style',
			array(
				'label' => esc_html__( 'Fast Box Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'fast_box_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #pricing .pricing-fast-box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fast_box_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #pricing .pricing-fast-box' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'fast_box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 4,
					'right'    => 18,
					'bottom'   => 6,
					'left'     => 18,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} #pricing .pricing-fast-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'fast_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'ukits-custom-element' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} #pricing .pricing-fast-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fast_box_typography',
				'selector' => '{{WRAPPER}} #pricing .pricing-fast-box',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'pricing_title_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0a0a0a',
				'selectors' => array(
					'{{WRAPPER}} #pricing .pricing-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #pricing .pricing-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pricing_heading_typography',
				'selector' => '{{WRAPPER}} #pricing .pricing-heading',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the Pricing widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$cards    = ! empty( $settings['pricing_cards'] ) && is_array( $settings['pricing_cards'] ) ? $settings['pricing_cards'] : array();
		$form_id  = ! empty( $settings['contact_form_id'] ) ? absint( $settings['contact_form_id'] ) : 107;
		?>
		<section id="pricing" class="ukits-custom-element w-[1156px]">
			<div class="pricing-frame relative w-[1156px] h-[983px] bg-white">
				<div class="pricing-content absolute top-32 left-12 w-[1060px] h-[727px] flex gap-20">
					<div class="pricing-cards flex mt-[17.5px] w-[490px] h-[692px] relative flex-col items-start gap-6">
						<?php foreach ( $cards as $card ) : ?>
							<?php $this->render_pricing_card( $card ); ?>
						<?php endforeach; ?>
					</div>
					<div class="pricing-copy w-[490px] h-[727.09px] flex flex-col">
						<div class="pricing-eyebrow-wrap w-[490px] flex">
							<div class="mt-[0.5px] w-[139px] h-5 [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="pricing-heading-wrap w-[490px] h-[273.59px] mt-6 flex">
							<h2 class="pricing-heading w-[490px] h-[274px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl tracking-[-1.44px] leading-[68.4px]">
								<span class="pricing-fast-box"><?php echo esc_html( $settings['fast_text'] ); ?></span><br />
								<span class="pricing-title-main pricing-title-nowrap tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_2'] ); ?><br /></span>
								<span class="pricing-title-highlight text-[#48842b] tracking-[-1.04px]"><?php echo esc_html( $settings['title_highlight'] ); ?></span>
							</h2>
						</div>
						<div class="pricing-description-wrap h-[97.5px] w-[490px] mt-8 flex">
							<p class="pricing-description -mt-px w-[490px] h-[98px] [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-xl tracking-[0] leading-[32.5px]"><?php echo esc_html( $settings['description'] ); ?></p>
						</div>
						<div class="pricing-contact-form">
							<?php if ( $form_id && shortcode_exists( 'contact-form-7' ) ) : ?>
								<?php echo do_shortcode( '[contact-form-7 id="' . $form_id . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php else : ?>
								<p class="pricing-form-notice"><?php echo esc_html__( 'Please install Contact Form 7 and select a form.', 'ukits-custom-element' ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="pricing-decor absolute top-20 left-20 w-64 h-64 border-4 border-solid border-[#f3f4f61a] rotate-[-12.00deg]"></div>
			</div>
		</section>
		<?php
	}

	/**
	 * Get Contact Form 7 forms for the Elementor selector.
	 *
	 * @return array
	 */
	private function get_contact_form_options() {
		$options = array();
		$forms   = get_posts(
			array(
				'post_type'      => 'wpcf7_contact_form',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $forms as $form ) {
			$options[ (string) $form->ID ] = $form->post_title;
		}

		if ( empty( $options ) ) {
			$options[''] = esc_html__( 'No Contact Form 7 forms found', 'ukits-custom-element' );
		}

		return $options;
	}

	/**
	 * Render a pricing card.
	 *
	 * @param array $card Pricing card settings.
	 */
	private function render_pricing_card( $card ) {
		$style = isset( $card['style'] ) ? $card['style'] : 'dark';

		if ( 'outline' === $style ) {
			?>
			<div class="pricing-card pricing-custom relative self-stretch w-full h-[220px] border-4 border-solid border-black">
				<div class="pricing-custom-eyebrow absolute top-11 left-11 w-[402px] h-5 flex">
					<div class="mt-[0.5px] w-[207px] h-5 [font-family:'Inter-Regular',Helvetica] font-normal text-[#6a7282] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $card['label'] ); ?></div>
				</div>
				<div class="pricing-custom-title absolute top-[76px] left-11 w-[402px] h-[60px] flex">
					<div class="mt-[-0.5px] w-[269px] h-[60px] [font-family:'Inter-Bold',Helvetica] font-bold text-black text-6xl tracking-[0] leading-[60px] whitespace-nowrap"><?php echo esc_html( $card['price'] ); ?></div>
				</div>
				<div class="pricing-custom-note absolute top-[152px] left-11 w-[402px] h-6 flex">
					<div class="-mt-px w-[147px] h-6 [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-base tracking-[0.80px] leading-6 whitespace-nowrap"><?php echo esc_html( $card['note'] ); ?></div>
				</div>
			</div>
			<?php
			return;
		}

		$bg_class = 'green' === $style ? 'bg-[#48842b]' : 'bg-black';
		?>
		<div class="pricing-card relative self-stretch w-full h-[212px] <?php echo esc_attr( $bg_class ); ?> overflow-hidden">
			<div class="absolute -top-16 left-[426px] w-32 h-32 bg-[#ffffff0d] rounded-[16777200px]"></div>
			<div class="pricing-card-content absolute top-10 left-10 w-[410px] h-[132px] flex flex-col">
				<div class="w-[410px] flex">
					<div class="mt-[0.5px] w-[204px] h-5 [font-family:'Inter-Regular',Helvetica] font-normal text-[#ffffffb2] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $card['label'] ); ?></div>
				</div>
				<div class="w-[410px] mt-3 flex">
					<div class="w-[158px] mt-[-0.5px] h-[60px] [font-family:'Inter-Bold',Helvetica] font-bold text-white text-6xl tracking-[0] leading-[60px] whitespace-nowrap"><?php echo esc_html( $card['price'] ); ?></div>
				</div>
				<div class="w-[410px] mt-4 flex">
					<div class="-mt-px w-[140px] h-6 [font-family:'Inter-Regular',Helvetica] font-normal text-[#ffffffcc] text-base tracking-[0.80px] leading-6 whitespace-nowrap"><?php echo esc_html( $card['note'] ); ?></div>
				</div>
			</div>
		</div>
		<?php
	}
}

class UKITS_Custom_Element_FAQ_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'faq';
	protected $widget_title = 'FAQ Section';

	/**
	 * Register FAQ-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'heading_section',
			array(
				'label' => esc_html__( 'Heading', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => esc_html__( 'Eyebrow', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'FAQ', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => esc_html__( 'Title Line 1', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'COMMON', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'   => esc_html__( 'Title Highlight Line', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'QUESTIONS', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_repeater_section',
			array(
				'label' => esc_html__( 'FAQ Repeater', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$faqs = new Repeater();

		$faqs->add_control(
			'question',
			array(
				'label'   => esc_html__( 'Question', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => esc_html__( 'DO I NEED PRIOR EXPERIENCE?', 'ukits-custom-element' ),
			)
		);

		$faqs->add_control(
			'answer',
			array(
				'label'   => esc_html__( 'Answer', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'No prior experience is needed. We offer beginner, refresher, and experienced operator courses.', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'faq_items',
			array(
				'label'       => esc_html__( 'FAQ Items', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $faqs->get_controls(),
				'title_field' => '{{{ question }}}',
				'default'     => array(
					array(
						'question' => 'DO I NEED PRIOR EXPERIENCE?',
						'answer'   => 'No prior experience is needed. We offer beginner, refresher, and experienced operator courses.',
					),
					array(
						'question' => 'HOW LONG DOES TRAINING TAKE?',
						'answer'   => 'Most courses take 1-3 days depending on your experience level and the equipment type.',
					),
					array(
						'question' => 'IS CERTIFICATION NATIONALLY RECOGNISED?',
						'answer'   => 'Yes. Our forklift training certificates are nationally recognised and suitable for workplace compliance.',
					),
					array(
						'question' => "WHAT IF I DON'T PASS?",
						'answer'   => 'Your instructor will explain the result and advise the best next step. Additional practice or reassessment can be arranged.',
					),
					array(
						'question' => 'DO YOU OFFER ON-SITE TRAINING?',
						'answer'   => 'Yes. We can deliver on-site training for businesses across the UK, subject to suitable equipment and training space.',
					),
					array(
						'question' => 'HOW QUICKLY CAN I BOOK?',
						'answer'   => 'Same-week places are often available. Contact us with your preferred course and location for the quickest slot.',
					),
				),
			)
		);

		$this->add_control(
			'plus_icon',
			array(
				'label'   => esc_html__( 'Plus Icon', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/plus-white.svg',
				),
			)
		);

		$this->add_control(
			'minus_icon',
			array(
				'label'   => esc_html__( 'Minus Icon', 'ukits-custom-element' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/minus-white.svg',
				),
			)
		);

		$this->end_controls_section();

		$this->register_faq_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_faq_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Section Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-frame' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slant_color',
			array(
				'label'     => esc_html__( 'Top Slant Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-top-slant' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_style',
			array(
				'label' => esc_html__( 'Heading Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => esc_html__( 'Eyebrow Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-eyebrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-title-main' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_highlight_color',
			array(
				'label'     => esc_html__( 'Title Highlight Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-title-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} #faq .faq-title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_item_style',
			array(
				'label' => esc_html__( 'FAQ Repeater Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'question_color',
			array(
				'label'     => esc_html__( 'Question Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-question' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'answer_color',
			array(
				'label'     => esc_html__( 'Answer Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1d5dc',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-answer' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff33',
				'selectors' => array(
					'{{WRAPPER}} #faq .faq-list, {{WRAPPER}} #faq .faq-item' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'question_typography',
				'selector' => '{{WRAPPER}} #faq .faq-question',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'answer_typography',
				'selector' => '{{WRAPPER}} #faq .faq-answer',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render FAQ section.
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$faq_items  = ! empty( $settings['faq_items'] ) && is_array( $settings['faq_items'] ) ? $settings['faq_items'] : array();
		$plus_icon  = ! empty( $settings['plus_icon']['url'] ) ? $settings['plus_icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/plus-white.svg';
		$minus_icon = ! empty( $settings['minus_icon']['url'] ) ? $settings['minus_icon']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/minus-white.svg';
		?>
		<section id="faq" class="ukits-custom-element w-[1156px]" data-plus-icon="<?php echo esc_url( $plus_icon ); ?>" data-minus-icon="<?php echo esc_url( $minus_icon ); ?>">
			<div class="faq-frame relative w-[1156px] h-[951px] bg-black overflow-hidden">
				<div class="faq-top-slant absolute top-[-84px] left-[-24px] w-[1204px] h-32 bg-[#48842b] rotate-[-2.00deg]"></div>
				<div class="faq-content absolute top-32 left-12 w-[1060px] h-[695px] flex gap-20">
					<div class="faq-heading-col w-[376px] h-[695px] flex flex-col gap-[108px]">
						<div class="faq-eyebrow-wrap w-[376px] flex">
							<div class="faq-eyebrow mt-[0.5px] w-[37px] h-5 [font-family:'Inter-Bold',Helvetica] font-bold text-[#48842b] text-sm tracking-[4.20px] leading-5 whitespace-nowrap"><?php echo esc_html( $settings['eyebrow'] ); ?></div>
						</div>
						<div class="faq-title-wrap w-[376px] h-[136.8px] flex">
							<h2 class="faq-title w-[427px] h-[168px] [font-family:'Montserrat-Medium',Helvetica] font-medium text-7xl tracking-[-1.44px] leading-[84px]">
								<span class="faq-title-main text-white tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_1'] ); ?><br /></span>
								<span class="faq-title-highlight text-[#48842b] tracking-[-1.04px]"><?php echo esc_html( $settings['title_line_2'] ); ?></span>
							</h2>
						</div>
					</div>
					<div class="faq-list relative z-10 flex w-[604px] h-[695px] flex-col items-start pt-px pb-0 px-0 border-t [border-top-style:solid] border-[#ffffff33]">
						<?php foreach ( $faq_items as $item ) : ?>
							<div class="faq-item flex flex-col self-stretch w-full border-b [border-bottom-style:solid] border-[#ffffff33]">
								<div class="flex min-h-28 items-start justify-between gap-6 py-8">
									<h3 class="faq-question [font-family:'Inter-Medium',Helvetica] font-medium text-white text-2xl tracking-[0.60px] leading-8">
										<?php echo esc_html( $item['question'] ); ?>
									</h3>
									<button class="faq-toggle all-[unset] box-border flex h-12 w-12 shrink-0 cursor-pointer items-center justify-center border-2 border-solid border-white" type="button" aria-expanded="false">
										<img class="h-[18px] w-[18px]" src="<?php echo esc_url( $plus_icon ); ?>" alt="" />
									</button>
								</div>
								<p class="faq-answer hidden max-w-[500px] pb-8 pr-8 [font-family:'Inter-Regular',Helvetica] font-normal text-[#d1d5dc] text-base tracking-[0] leading-7">
									<?php echo esc_html( $item['answer'] ); ?>
								</p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="pointer-events-none absolute top-[476px] left-[771px] w-[385px] h-[476px] [background:linear-gradient(180deg,rgba(255,255,255,1)_0%,rgba(0,0,0,0)_0%),linear-gradient(90deg,rgba(255,255,255,1)_0%,rgba(0,0,0,0)_0%)] opacity-5"></div>
			</div>
		</section>
		<?php
	}
}

class UKITS_Custom_Element_FinalCTA_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'finalcta';
	protected $widget_title = 'FinalCTA Section';

	/**
	 * Register the warehouse media control in addition to the template controls.
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'warehouse_media_section',
			array(
				'label' => esc_html__( 'Warehouse Media', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'warehouse_media_source',
			array(
				'label'   => esc_html__( 'Media Source', 'ukits-custom-element' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => array(
					'youtube' => esc_html__( 'YouTube', 'ukits-custom-element' ),
					'upload'  => esc_html__( 'Uploaded Video or GIF', 'ukits-custom-element' ),
				),
			)
		);

		$this->add_control(
			'warehouse_youtube_url',
			array(
				'label'       => esc_html__( 'YouTube URL', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.youtube.com/watch?v=...',
				'default'     => array(
					'url' => 'https://www.youtube.com/watch?v=oyJj7dJJW3g',
				),
				'condition'   => array(
					'warehouse_media_source' => 'youtube',
				),
			)
		);

		$this->add_control(
			'warehouse_media',
			array(
				'label'       => esc_html__( 'Video or GIF', 'ukits-custom-element' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => array( 'video', 'image' ),
				'default'     => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/final-cta-bg.jpg',
				),
				'description' => esc_html__( 'Upload an MP4, WebM, OGG, or animated GIF. Videos autoplay muted and loop continuously.', 'ukits-custom-element' ),
				'condition'   => array(
					'warehouse_media_source' => 'upload',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render warehouse video/GIF inside the template media area.
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$source    = ! empty( $settings['warehouse_media_source'] ) ? $settings['warehouse_media_source'] : 'youtube';
		$media_url = ! empty( $settings['warehouse_media']['url'] ) ? $settings['warehouse_media']['url'] : UKITS_CUSTOM_ELEMENT_URL . 'assets/img/final-cta-bg.jpg';
		$extension = strtolower( pathinfo( wp_parse_url( $media_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
		$is_video  = in_array( $extension, array( 'mp4', 'webm', 'ogg' ), true );
		$html      = $this->get_default_html();

		if ( 'youtube' === $source ) {
			$youtube_url = ! empty( $settings['warehouse_youtube_url']['url'] ) ? $settings['warehouse_youtube_url']['url'] : 'https://www.youtube.com/watch?v=oyJj7dJJW3g';
			$youtube_id  = $this->get_youtube_video_id( $youtube_url );
			$embed_url   = 'https://www.youtube-nocookie.com/embed/' . rawurlencode( $youtube_id ) . '?autoplay=1&mute=1&loop=1&playlist=' . rawurlencode( $youtube_id ) . '&controls=0&rel=0&playsinline=1&modestbranding=1&cc_load_policy=0&iv_load_policy=3';
			$media_html  = sprintf(
				'<iframe class="final-media-asset final-media-asset--youtube" src="%1$s" title="%2$s" allow="autoplay; encrypted-media; picture-in-picture" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
				esc_url( $embed_url ),
				esc_attr__( 'Warehouse operations video', 'ukits-custom-element' )
			);
		} elseif ( $is_video ) {
			$media_html = sprintf(
				'<video class="final-media-asset" autoplay muted loop playsinline preload="metadata" aria-label="%1$s"><source src="%2$s" type="%3$s"></video>',
				esc_attr__( 'Warehouse operations video', 'ukits-custom-element' ),
				esc_url( $media_url ),
				esc_attr( 'video/' . $extension )
			);
		} else {
			$media_html = sprintf(
				'<img class="final-media-asset" src="%1$s" alt="%2$s" />',
				esc_url( $media_url ),
				esc_attr__( 'Warehouse operations', 'ukits-custom-element' )
			);
		}

		$html = $this->apply_text_settings( $html, $settings );
		$html = $this->apply_asset_settings( $html, $settings );
		$html = $this->apply_link_settings( $html, $settings );
		$html = $this->apply_button_settings( $html, $settings );
		$html = str_replace(
			'<div class="final-media"></div>',
			'<div class="final-media"><div class="final-media-viewport">' . $media_html . '</div><span class="final-media-corner final-media-corner--top" aria-hidden="true"></span><span class="final-media-corner final-media-corner--bottom" aria-hidden="true"></span><span class="final-media-label">' . esc_html__( 'WAREHOUSE OPERATIONS', 'ukits-custom-element' ) . '</span></div>',
			$html
		);
		$html = $this->add_wrapper_class( $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Extract a YouTube video ID from common YouTube URL formats.
	 *
	 * @param string $url YouTube URL or video ID.
	 * @return string
	 */
	private function get_youtube_video_id( $url ) {
		$url = trim( (string) $url );

		if ( preg_match( '/^[A-Za-z0-9_-]{11}$/', $url ) ) {
			return $url;
		}

		if ( preg_match( '#(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?.*v=|embed/|shorts/))([A-Za-z0-9_-]{11})#i', $url, $matches ) ) {
			return $matches[1];
		}

		return 'oyJj7dJJW3g';
	}
}

class UKITS_Custom_Element_Footer_Section extends UKITS_Custom_Element_Template_Widget {
	protected $section_id = 'footer';
	protected $widget_title = 'Footer Section';

	/**
	 * Register Footer-specific Elementor controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'brand_section',
			array(
				'label' => esc_html__( 'Brand Section', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'logo_source',
			array(
				'label'   => esc_html__( 'Logo Source', 'ukits-custom-element' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Auto / Site Logo', 'ukits-custom-element' ),
					'custom' => esc_html__( 'Custom Logo', 'ukits-custom-element' ),
					'plugin' => esc_html__( 'Plugin Default Logo', 'ukits-custom-element' ),
				),
			)
		);

		$this->add_control(
			'logo',
			array(
				'label'     => esc_html__( 'Custom Logo', 'ukits-custom-element' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => UKITS_CUSTOM_ELEMENT_URL . 'assets/img/image-UK-industrial-training-services.png',
				),
				'condition' => array(
					'logo_source!' => 'plugin',
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'Professional forklift training across the UK. Get certified in 1-3 days.', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'BOOK NOW', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/#courses' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'contact_section',
			array(
				'label' => esc_html__( 'Contact Section', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'contact_heading',
			array(
				'label'   => esc_html__( 'Heading', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'CONTACT', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'phone',
			array(
				'label'   => esc_html__( 'Phone', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '+44 07397377376', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'email',
			array(
				'label'   => esc_html__( 'Email', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Bookings@ukindustrialtraining.co.uk', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'hours',
			array(
				'label'   => esc_html__( 'Opening Hours', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => "MON-FRI: 7AM-6PM\nSAT: 8AM-2PM",
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'menus_section',
			array(
				'label' => esc_html__( 'Footer Menus', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'coverage_heading',
			array(
				'label'   => esc_html__( 'Coverage Heading', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'COVERAGE', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'coverage_menu_id',
			array(
				'label'       => esc_html__( 'Coverage Menu', 'ukits-custom-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto_coverage',
				'options'     => $this->get_footer_menu_options( 'auto_coverage', esc_html__( 'Auto / Coverage Menu', 'ukits-custom-element' ) ),
				'description' => esc_html__( 'If no site menu is available, the template Coverage menu is used.', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'info_heading',
			array(
				'label'   => esc_html__( 'Info Heading', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'INFO', 'ukits-custom-element' ),
			)
		);

		$this->add_control(
			'info_menu_id',
			array(
				'label'       => esc_html__( 'Info Menu', 'ukits-custom-element' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto_info',
				'options'     => $this->get_footer_menu_options( 'auto_info', esc_html__( 'Auto / Info Menu', 'ukits-custom-element' ) ),
				'description' => esc_html__( 'If no site menu is available, the template Info menu is used.', 'ukits-custom-element' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'bottom_section',
			array(
				'label' => esc_html__( 'Bottom Bar', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'copyright',
			array(
				'label'   => esc_html__( 'Copyright', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '© 2026 UK INDUSTRIAL TRAINING SERVICES', 'ukits-custom-element' ),
			)
		);

		$legal = new Repeater();

		$legal->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'ukits-custom-element' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'PRIVACY', 'ukits-custom-element' ),
			)
		);

		$legal->add_control(
			'url',
			array(
				'label'       => esc_html__( 'Link', 'ukits-custom-element' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'default'     => array(
					'url' => home_url( '/' ),
				),
			)
		);

		$this->add_control(
			'legal_links',
			array(
				'label'       => esc_html__( 'Legal Links', 'ukits-custom-element' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $legal->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'label' => 'PRIVACY',
						'url'   => array( 'url' => home_url( '/' ) ),
					),
					array(
						'label' => 'TERMS',
						'url'   => array( 'url' => home_url( '/' ) ),
					),
					array(
						'label' => 'ACCREDITATION',
						'url'   => array( 'url' => home_url( '/' ) ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->register_footer_style_controls();
	}

	/**
	 * Register style controls.
	 */
	private function register_footer_style_controls() {
		$this->start_controls_section(
			'footer_style',
			array(
				'label' => esc_html__( 'Footer Style', 'ukits-custom-element' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-frame' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Heading Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4a5565',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-text, {{WRAPPER}} #footer .footer-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#48842b',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-link:hover' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} #footer .footer-link::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} #footer',
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

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-book' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'ukits-custom-element' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					'{{WRAPPER}} #footer .footer-book' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Footer section.
	 */
	protected function render() {
		$settings      = $this->get_settings_for_display();
		$logo_url      = $this->get_footer_logo_url( $settings );
		$coverage_menu = $this->get_footer_menu_items( isset( $settings['coverage_menu_id'] ) ? $settings['coverage_menu_id'] : 'auto_coverage', 'coverage' );
		$info_menu     = $this->get_footer_menu_items( isset( $settings['info_menu_id'] ) ? $settings['info_menu_id'] : 'auto_info', 'info' );
		$legal_links   = ! empty( $settings['legal_links'] ) && is_array( $settings['legal_links'] ) ? $settings['legal_links'] : array();

		$footer_button_link = ! empty( $settings['button_link'] ) && is_array( $settings['button_link'] ) ? $settings['button_link'] : array();
		$footer_button_link['url'] = ! empty( $footer_button_link['url'] ) && '#' !== $footer_button_link['url'] ? $footer_button_link['url'] : home_url( '/#courses' );
		$this->add_link_attributes( 'footer_button', $footer_button_link );
		$this->add_render_attribute( 'footer_button', 'class', 'footer-book' );
		?>
		<section id="footer" class="ukits-custom-element w-[1156px]">
			<div class="footer-frame flex flex-col h-[555px] items-start gap-16 pt-20 pb-0 px-12 relative">
				<div class="footer-main relative self-stretch w-full h-[278px]">
					<div class="footer-brand absolute top-px left-0 w-[360px] h-[278px] flex flex-col gap-8">
						<img class="w-[220px] h-20 object-contain object-left" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
						<div class="footer-brand-copy-wrap w-[360px] flex">
							<p class="footer-brand-copy footer-text mt-[-0.5px] w-[360px] h-[78px] [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-2xl tracking-[0] leading-[39px]">
								<?php echo esc_html( $settings['description'] ); ?>
							</p>
						</div>
						<a <?php $this->print_render_attribute_string( 'footer_button' ); ?>>
							<?php echo esc_html( $settings['button_text'] ); ?>
						</a>
					</div>

					<div class="footer-col footer-contact flex flex-col w-[300px] h-[278px] items-start gap-6 absolute top-px left-[410px]">
						<div class="footer-heading relative self-stretch w-full h-5 [font-family:'Montserrat-Bold',Helvetica] font-bold text-black tracking-[4.20px] text-sm leading-5 whitespace-nowrap"><?php echo esc_html( $settings['contact_heading'] ); ?></div>
						<div class="flex flex-col h-[124px] items-start gap-4 relative self-stretch w-full">
							<div class="footer-text [font-family:'Inter-SemiBold',Helvetica] font-semibold text-[#4a5565] text-lg tracking-[0] leading-7 whitespace-nowrap"><?php echo esc_html( $settings['phone'] ); ?></div>
							<div class="footer-text footer-email [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-base tracking-[0] leading-6 whitespace-nowrap"><?php echo esc_html( $settings['email'] ); ?></div>
							<div class="footer-text w-[300px] [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-sm tracking-[0.70px] leading-5"><?php echo nl2br( esc_html( $settings['hours'] ) ); ?></div>
						</div>
					</div>

					<?php $this->render_footer_menu_column( 'footer-coverage', 'left-[748px]', $settings['coverage_heading'], $coverage_menu ); ?>
					<?php $this->render_footer_menu_column( 'footer-info', 'left-[935px]', $settings['info_heading'], $info_menu ); ?>
				</div>

				<div class="footer-bottom flex h-[53px] items-center justify-between pt-8 pb-0 px-0 relative self-stretch w-full border-t border-[#E5E7EB] [border-top-style:solid]">
					<div class="footer-copyright relative w-[330.62px] h-5">
						<p class="footer-text absolute top-px left-0 [font-family:'Inter-Regular',Helvetica] font-normal text-[#6a7282] text-sm tracking-[0.70px] leading-5 whitespace-nowrap">
							<?php echo esc_html( $settings['copyright'] ); ?>
						</p>
					</div>
					<div class="footer-legal flex w-auto h-5 items-start gap-8 relative">
						<?php foreach ( $legal_links as $link ) : ?>
							<a class="footer-link [font-family:'Inter-Regular',Helvetica] font-normal text-[#6a7282] text-sm tracking-[0.35px] leading-5 whitespace-nowrap" href="<?php echo esc_url( ! empty( $link['url']['url'] ) && '#' !== $link['url']['url'] ? $link['url']['url'] : home_url( '/' ) ); ?>">
								<?php echo esc_html( $link['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * Render one footer menu column.
	 *
	 * @param string $class   Column class.
	 * @param string $left    Position class.
	 * @param string $heading Column heading.
	 * @param array  $items   Menu items.
	 */
	private function render_footer_menu_column( $class, $left, $heading, $items ) {
		?>
		<div class="footer-col <?php echo esc_attr( $class ); ?> flex flex-col w-[137px] h-[278px] items-start gap-6 absolute top-px <?php echo esc_attr( $left ); ?>">
			<div class="footer-heading relative self-stretch w-full h-5 [font-family:'Montserrat-Bold',Helvetica] font-bold text-black text-sm tracking-[4.20px] leading-5 whitespace-nowrap">
				<?php echo esc_html( $heading ); ?>
			</div>
			<div class="flex flex-col items-start gap-2 relative self-stretch w-full">
				<?php foreach ( $items as $item ) : ?>
					<a class="footer-link [font-family:'Inter-Regular',Helvetica] font-normal text-[#4a5565] text-sm tracking-[0.35px] leading-5 whitespace-nowrap <?php echo ! empty( $item['highlight'] ) ? 'font-bold text-[#48842b]' : ''; ?>" href="<?php echo esc_url( $item['url'] ); ?>">
						<?php echo esc_html( $item['title'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get menu select options.
	 *
	 * @param string $auto_label Auto option label.
	 * @return array
	 */
	private function get_footer_menu_options( $auto_key, $auto_label ) {
		$options = array(
			$auto_key  => $auto_label,
			'default'  => esc_html__( 'Template Default', 'ukits-custom-element' ),
		);

		foreach ( wp_get_nav_menus() as $menu ) {
			$options[ (string) $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Resolve menu items.
	 *
	 * @param string $menu_id Selected menu id.
	 * @param string $type    coverage|info.
	 * @return array
	 */
	private function get_footer_menu_items( $menu_id, $type ) {
		$resolved_menu_id = 0;

		if ( 'default' !== $menu_id && 0 !== strpos( $menu_id, 'auto_' ) ) {
			$resolved_menu_id = absint( $menu_id );
		} elseif ( 'default' !== $menu_id ) {
			$resolved_menu_id = $this->get_footer_location_menu_id( $type );
		}

		$menu_items = $resolved_menu_id ? wp_get_nav_menu_items( $resolved_menu_id ) : array();

		if ( ! empty( $menu_items ) && ! is_wp_error( $menu_items ) ) {
			return array_map(
				static function ( $item ) {
					return array(
						'title'     => $item->title,
						'url'       => $item->url,
						'highlight' => false,
					);
				},
				$menu_items
			);
		}

		return 'coverage' === $type ? $this->get_default_coverage_menu() : $this->get_default_info_menu();
	}

	/**
	 * Resolve footer menu from registered theme locations.
	 *
	 * @param string $type coverage|info.
	 * @return int
	 */
	private function get_footer_location_menu_id( $type ) {
		$locations  = get_nav_menu_locations();
		$candidates = 'coverage' === $type
			? array( 'coverage', 'footer-coverage', 'footer_coverage' )
			: array( 'info', 'footer-info', 'footer_info', 'footer', 'menu-2' );

		foreach ( $candidates as $location ) {
			if ( ! empty( $locations[ $location ] ) ) {
				return (int) $locations[ $location ];
			}
		}

		return 0;
	}

	/**
	 * Default coverage menu.
	 *
	 * @return array
	 */
	private function get_default_coverage_menu() {
		return array(
			array( 'title' => 'LONDON', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => 'MIDLANDS', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => 'NORTH WEST', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => 'YORKSHIRE', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => 'SCOTLAND', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => 'WALES', 'url' => home_url( '/#footer' ), 'highlight' => false ),
			array( 'title' => '+ UK WIDE', 'url' => home_url( '/#footer' ), 'highlight' => true ),
		);
	}

	/**
	 * Default info menu.
	 *
	 * @return array
	 */
	private function get_default_info_menu() {
		return array(
			array( 'title' => 'COURSES', 'url' => home_url( '/#courses' ), 'highlight' => false ),
			array( 'title' => 'PRICING', 'url' => home_url( '/#pricing' ), 'highlight' => false ),
			array( 'title' => 'ABOUT US', 'url' => home_url( '/#whychooseus' ), 'highlight' => false ),
			array( 'title' => 'FAQ', 'url' => home_url( '/#faq' ), 'highlight' => false ),
			array( 'title' => 'CONTACT', 'url' => home_url( '/#footer' ), 'highlight' => false ),
		);
	}

	/**
	 * Resolve footer logo URL.
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_footer_logo_url( $settings ) {
		$default = UKITS_CUSTOM_ELEMENT_URL . 'assets/img/image-UK-industrial-training-services.png';
		$source  = isset( $settings['logo_source'] ) ? $settings['logo_source'] : 'auto';

		if ( 'plugin' === $source ) {
			return $default;
		}

		if ( 'custom' === $source && ! empty( $settings['logo']['url'] ) ) {
			return $settings['logo']['url'];
		}

		$site_logo_id = (int) get_theme_mod( 'custom_logo' );
		$site_logo    = $site_logo_id ? wp_get_attachment_image_url( $site_logo_id, 'full' ) : '';

		return $site_logo ? $site_logo : $default;
	}
}

/**
 * Registry helper.
 */
final class UKITS_Custom_Element_Section_Widgets {

	/**
	 * Widget class list.
	 *
	 * @return array
	 */
	public static function get_widgets() {
		return array(
			'UKITS_Custom_Element_Header_Section',
			'UKITS_Custom_Element_Hero_Section',
			'UKITS_Custom_Element_Benefits_Section',
			'UKITS_Custom_Element_HowItWorks_Section',
			'UKITS_Custom_Element_WhyChooseUs_Section',
			'UKITS_Custom_Element_Courses_Section',
			'UKITS_Custom_Element_Testimonials_Section',
			'UKITS_Custom_Element_Pricing_Section',
			'UKITS_Custom_Element_FAQ_Section',
			'UKITS_Custom_Element_FinalCTA_Section',
			'UKITS_Custom_Element_Footer_Section',
		);
	}
}
