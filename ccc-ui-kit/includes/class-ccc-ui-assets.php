<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Assets
{
    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets()
    {
        wp_enqueue_style(
            'ccc-ui-tokens',
            CCC_UI_KIT_URL . 'assets/css/ccc-ui-tokens.css',
            array(),
            CCC_UI_KIT_VERSION
        );

        wp_enqueue_style(
            'ccc-ui-base',
            CCC_UI_KIT_URL . 'assets/css/ccc-ui-base.css',
            array('ccc-ui-tokens'),
            CCC_UI_KIT_VERSION
        );

        wp_enqueue_style(
            'ccc-ui-components',
            CCC_UI_KIT_URL . 'assets/css/ccc-ui-components.css',
            array('ccc-ui-base'),
            CCC_UI_KIT_VERSION
        );

        wp_enqueue_script(
            'ccc-ui-kit',
            CCC_UI_KIT_URL . 'assets/js/ccc-ui-kit.js',
            array(),
            CCC_UI_KIT_VERSION,
            true
        );
    }
}
