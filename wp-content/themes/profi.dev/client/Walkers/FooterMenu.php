<?php declare(strict_types=1);

namespace ProfiDev\Client\Walkers;
use Walker_Nav_Menu;

class FooterMenu extends Walker_Nav_Menu {
    public function __construct() {
        parent::__construct();
        add_filter( 'nav_menu_submenu_css_class', [$this, 'nav_menu_submenu_css_class'], 10, 3 );
    }

    public function __destruct() {
        remove_filter('nav_menu_submenu_css_class', [$this, 'nav_menu_submenu_css_class']);
    }

    public function nav_menu_submenu_css_class($classes, $args, $depth): array {
        return array_merge($classes, ['no-list']);
    }

    public function get_menu_root_items($menu_slug): array {
        static $menu_items = [];
        if (!array_key_exists($menu_slug, $menu_items)) {
            $items = wp_get_nav_menu_items( $menu_slug );
            $menu_items[$menu_slug] = $items;
        }
        if (!is_array($menu_items[$menu_slug])) {
            $menu_items[$menu_slug] = [];
        }
        return array_filter($menu_items[$menu_slug], function ($item) {
            return $item->menu_item_parent == "0";
        });
    }

    /**
     * @inheritDoc
     */
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
        if ($depth === 0) {
            // Логіка для заголовків колонок футера (без змін)
            $items = $this->get_menu_root_items($args->menu->slug);
            $menu_item = $data_object;
            $class = "item";
            $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
            $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

            $output .= sprintf(
                '<div class="%2$s"><p class="footer-title">%1$s</p><div class="footer-menu">',
                $title,
                $class
            );
        } else {
            // ЛОГІКА ДЛЯ ПІДМЕНЮ (Іконки + Кнопки)
            
            // 1. Отримуємо іконку ACF (поле 'icon')
            $icon_data = function_exists('get_field') ? get_field('icon', $data_object->ID) : null;
            $icon_id = null;

            if (is_numeric($icon_data)) {
                $icon_id = $icon_data;
            } elseif (is_array($icon_data) && isset($icon_data['ID'])) {
                $icon_id = $icon_data['ID'];
            }

            // 2. Вставляємо іконку перед текстом посилання
            if ($icon_id) {
                $args->link_before = wp_get_attachment_image((int) $icon_id, [24, 24], false, [
                    'class'   => 'icon',
                    'loading' => 'lazy',
                    'alt'     => $data_object->title
                ]);
            } else {
                $args->link_before = '';
            }

            // 3. Генеруємо стандартний HTML (li > a)
            $item_html = '';
            parent::start_el( $item_html, $data_object, $depth, $args, $current_object_id );

            // 4. Додаємо кнопку мобільного перемикача, якщо є вкладені елементи далі
            $classes = empty( $data_object->classes ) ? array() : (array) $data_object->classes;
            if (in_array('menu-item-has-children', $classes)) {
                $button = '<button class="sub-menu-toggle" aria-expanded="false" aria-label="Toggle submenu"></button>';
                $item_html = str_replace('</li>', $button . '</li>', $item_html);
            }

            $output .= $item_html;
        }
    }

    /**
     * @inheritDoc
     */
    public function end_el( &$output, $data_object, $depth = 0, $args = null ): void {
        if ($depth === 0) {
            $output .= "</div></div>";
        } else {
            parent::end_el( $output, $data_object, $depth, $args );
        }
    }
}