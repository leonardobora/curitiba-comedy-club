<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Kit
{
    /**
     * @var CCC_UI_Kit|null
     */
    private static $instance = null;

    /**
     * @var CCC_UI_Assets
     */
    private $assets;

    /**
     * @var CCC_UI_Shortcodes
     */
    private $shortcodes;

    /**
     * @return CCC_UI_Kit
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->load_dependencies();
        $this->boot_modules();
    }

    private function load_dependencies()
    {
        require_once CCC_UI_KIT_PATH . 'includes/class-ccc-ui-assets.php';
        require_once CCC_UI_KIT_PATH . 'includes/class-ccc-ui-shortcodes.php';

        require_once CCC_UI_KIT_PATH . 'includes/shortcodes/class-ccc-ui-shortcode-page-hero.php';
        require_once CCC_UI_KIT_PATH . 'includes/shortcodes/class-ccc-ui-shortcode-cta-ingressos.php';
        require_once CCC_UI_KIT_PATH . 'includes/shortcodes/class-ccc-ui-shortcode-contact-section.php';
    }

    private function boot_modules()
    {
        $this->assets = new CCC_UI_Assets();
        $this->assets->register();

        $this->shortcodes = new CCC_UI_Shortcodes();
        $this->shortcodes->register();
    }
}
