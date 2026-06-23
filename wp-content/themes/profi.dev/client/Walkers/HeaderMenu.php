<?php declare(strict_types=1);

namespace ProfiDev\Client\Walkers;

use Walker_Nav_Menu;

class HeaderMenu extends Walker_Nav_Menu {
	private $top_level_total = 0;
	private $top_level_current = 0;

	public function __construct() {
		parent::__construct();
		add_filter( 'nav_menu_submenu_css_class', [$this, 'add_no_list_class'], 10, 3 );
	}

	public function add_no_list_class($classes, $args, $depth): array {
		return array_merge($classes, ['no-list']);
	}

	/**
	 * @inheritDoc
	 */
	public function walk( $elements, $max_depth, ...$args ): string {
		foreach ( $elements as $element ) {
			if ( empty( $element->menu_item_parent ) || 0 == $element->menu_item_parent ) {
				$this->top_level_total++;
			}
		}

		return parent::walk( $elements, $max_depth, ...$args );
	}

	/**
	 * @inheritDoc
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {

		$icon_id = get_post_meta($data_object->ID, 'icon', true);

		if ($depth > 0 && $icon_id) {
			$icon_html = wp_get_attachment_image((int) $icon_id, [24, 24], false, [
				'class'   => 'icon',
				'loading' => 'lazy',
				'alt'     => $data_object->title
			]);

			$args->link_before = $icon_html;
		} else {
			$args->link_before = '';
		}

		$item_html = '';
		parent::start_el($item_html, $data_object, $depth, $args, $current_object_id);

		$classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
		if (in_array('menu-item-has-children', $classes)) {
			$button = '<button class="no-btn sub-menu-toggle" aria-expanded="false" aria-label="'.__('Toggle submenu', 'profidev-theme').'"></button>';
			$item_html .= $button;
		}

		$output .= $item_html;
	}

	/**
	 * @inheritDoc
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {
		parent::end_el( $output, $data_object, $depth, $args );
		if (class_exists('MeprOptions')) {
			if ( $depth === 0 ) {
				$this->top_level_current ++;

				if ( $this->top_level_current === $this->top_level_total ) {
					// Guest: Membership -> www.cyclenorway.com/membership
					// LoggedIn: Members -> www.cyclenorway.com/dashboard

					$dashboard_page  = '';
					$dashboard_title = '';
					$is_dashboard = false;

					if (is_user_logged_in()) {
						$dashboard = get_page_by_path( 'dashboard' );
						if ( $dashboard ) {
							$is_dashboard = get_queried_object_id() == $dashboard->ID;
							$dashboard_title = __('Members', 'profidev-theme');
							$dashboard_page = get_permalink( $dashboard );
						}
					} else {
						$dashboard = get_page_by_path( 'membership' );
						if ( $dashboard ) {
							$is_dashboard = get_queried_object_id() == $dashboard->ID;
							$dashboard_title = __( 'Membership', 'profidev-theme' );
							$dashboard_page = get_permalink( $dashboard );
						}
					}

					if (!empty( $dashboard_title ) && !empty( $dashboard_page )) {
						$output .= sprintf(
							'<li class="menu-item mobile %3$s"><a href="%2$s">%1$s</a></li>',
							$dashboard_title,
							$dashboard_page,
							$is_dashboard ? 'current-menu-item current_page_item' : ''
						);
					}
				}
			}
		}
	}
}
