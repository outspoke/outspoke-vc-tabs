<?php
/**
 * Plugin Name:       Outspoke VC Tabs
 * Plugin URI:        https://outspoke.co
 * Description:       Custom Visual Composer tabbed content area built with Slick Slider.
 * Version:           1.0.0
 * Author:            Jay Nielsen
 * Author URI:        https://outspoke.co
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set up the VC Element
add_action( 'vc_before_init', function() {
    vc_map([
        "name" => "Outspoke Tabs",
        "base" => "outspoke_tabs",
        "icon" => plugins_url('/assets/img/tabs-icon.svg', __file__),
        "as_parent" => [ 'only' => 'outspoke_tab' ], // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
        "content_element" => true,
        "show_settings_on_create" => false,
        "is_container" => true,
        "params" => [
            // Maybe add some params for hover colors?
        ],
        "js_view" => 'VcColumnView'
    ]);
    vc_map([
        "name" => "Tab",
        "base" => "outspoke_tab",
        "icon" => plugins_url('/assets/img/tab-icon.svg', __file__),
        "content_element" => true,
        "as_child" => [ 'only' => 'outspoke_tabs' ], // Use only|except attributes to limit parent (separate multiple values with comma)
        "as_parent" => [ 'except' => 'outspoke_tabs' ],
        "params" => [
            [
                "type" => "attach_image",
                "heading" => "Active Tab Icon",
                "param_name" => "icon_active",
            ],
            [
                "type" => "attach_image",
                "heading" => "Inactive Tab Icon",
                "param_name" => "icon_inactive",
            ],
            [
                "type" => "textfield",
                "heading" => "Tab Label",
                "param_name" => "label",
                "admin_label" => true,
            ],
        ],
        "js_view" => 'VcColumnView'
    ]);
});

/**
 * Nested VC elemenst must ineherit WPBakery Short Codes Container
 * https://wpbakery.atlassian.net/wiki/spaces/VC/pages/524362
 */

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Outspoke_Tabs extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Outspoke_Tab extends WPBakeryShortCodesContainer {
    }
}

// Enqueue our frontend styles when the shortcode is used
add_action('wp_enqueue_scripts', function(){
    global $post;
    if ( has_shortcode( $post->post_content, 'outspoke_tabs') ) {
        wp_register_script(
            'slick',
            plugins_url('/assets/js/vendor/slick/slick.min.js', __file__),
            [],
            false,
            true
        );
        wp_enqueue_script(
            'outspoke-tabs',
            plugins_url('/assets/js/main.js', __file__),
            ['jquery', 'slick'],
            false,
            true
        );

        wp_register_style(
            'slick',
            plugins_url('/assets/js/vendor/slick/slick.css', __file__)
        );
        wp_register_style(
            'slick-theme',
            plugins_url('/assets/js/vendor/slick/slick-theme.css', __file__)
        );
        wp_enqueue_style(
            'outspoke-tabs',
            plugins_url('/assets/css/main.css', __file__),
            ['slick', 'slick-theme']
        );
    }
});

add_shortcode('outspoke_tabs', function( $atts, $content ){
    // Extract outspoke_tab children attributes
    $pattern = get_shortcode_regex(['outspoke_tab']);
    preg_match_all("/$pattern/", $content, $matches);
    $tabs = [];
    foreach( $matches[3] as $match ){
        $tabs[] = shortcode_parse_atts($match);
    };

    ob_start();
    ?>
        <div class="outspoke-tabs-nav" data-slides-to-show="6">
            <?php foreach( $tabs as $tab ): ?>
                <div>
                    <figure>
                        <?php if( !empty($tab['icon_active']) ): ?>
                            <?php $icon = wp_get_attachment_image_src( $tab['icon_active'], 'full' )[0]; ?>
                            <img class="active" src="<?= $icon; ?>" />
                        <?php endif; ?>
                        <?php if( !empty($tab['icon_inactive']) ): ?>
                            <?php $icon = wp_get_attachment_image_src( $tab['icon_inactive'], 'full' )[0]; ?>
                            <img class="inactive" src="<?= $icon; ?>" />
                        <?php endif; ?>
                    </figure>
                    <?php if( !empty($tab['label'])): ?>
                        <?= $tab['label']; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="outspoke-tabs">
            <?php echo do_shortcode( $content ); ?>
        </div>
    <?php
    return ob_get_clean();
});

add_shortcode('outspoke_tab', function($atts, $content) {
    ob_start();
    ?>
        <div>
            <?php echo do_shortcode( $content ); ?>
        </div>
    <?php
    return ob_get_clean();
});
