<?php

namespace WOWMALL\THEME\Theme\Classes;

class GoogleFonts
{

    protected static $_instance;
    public $placeholder;

    public function __construct()
    {
        add_action('admin_init', [$this, 'enqueue_editor_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_editor_styles()
    {
        add_editor_style(
            [
                $this->google_fonts_url(),
            ]
        );
    }

    public function enqueue_scripts()
    {
        // Enqueue Google fonts
        wp_enqueue_style('wowmall-google-fonts', $this->google_fonts_url(), [], null);
    }

    public function google_fonts_url()
    {
        if (!class_exists('WP_Theme_JSON_Resolver_Gutenberg')) {
            return '';
        }

        $theme_data = \WP_Theme_JSON_Resolver_Gutenberg::get_merged_data()->get_settings();
        if (empty($theme_data) || empty($theme_data['typography']) || empty($theme_data['typography']['fontFamilies'])) {
            return '';
        }

        $font_families = [];
        if (!empty($theme_data['typography']['fontFamilies']['user'])) {
            foreach ($theme_data['typography']['fontFamilies']['user'] as $font) {
                if (!empty($font['google'])) {
                    $font_families[] = $font['google'];
                }
            }
        } else {
            if (!empty($theme_data['typography']['fontFamilies']['theme'])) {
                foreach ($theme_data['typography']['fontFamilies']['theme'] as $font) {
                    if (!empty($font['google'])) {
                        $font_families[] = $font['google'];
                    }
                }
            }
        }

        if (empty($font_families)) {
            return '';
        }

        // Make a single request for the theme or user fonts.
        return esc_url_raw('https://fonts.googleapis.com/css2?' . implode('&', array_unique($font_families)) . '&display=swap');
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
