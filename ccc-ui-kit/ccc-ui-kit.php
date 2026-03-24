<?php
/**
 * Plugin Name: CCC UI Kit
 * Plugin URI: https://curitibacomedyclub.com.br/
 * Description: Design system and institutional shortcodes for Curitiba Comedy Club.
 * Version: 0.1.0
 * Author: Curitiba Comedy Club
 * License: GPL2+
 * Text Domain: ccc-ui-kit
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('CCC_UI_KIT_VERSION')) {
    define('CCC_UI_KIT_VERSION', '0.1.0');
}

if (!defined('CCC_UI_KIT_FILE')) {
    define('CCC_UI_KIT_FILE', __FILE__);
}

if (!defined('CCC_UI_KIT_PATH')) {
    define('CCC_UI_KIT_PATH', plugin_dir_path(__FILE__));
}

if (!defined('CCC_UI_KIT_URL')) {
    define('CCC_UI_KIT_URL', plugin_dir_url(__FILE__));
}

require_once CCC_UI_KIT_PATH . 'includes/class-ccc-ui-kit.php';

/**
 * Bootstraps the plugin singleton.
 *
 * @return CCC_UI_Kit
 */
function ccc_ui_kit()
{
    return CCC_UI_Kit::get_instance();
}

ccc_ui_kit();
