<?php
/**
 * Plugin Name: CCC Cache Flush Helper
 * Description: REST endpoint to flush Elementor CSS cache and page caches. Drop in mu-plugins/.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', function () {
    register_rest_route('wp-mcp/v1', '/flush-cache', array(
        'methods' => 'POST',
        'callback' => 'ccc_flush_cache_handler',
        'permission_callback' => function () {
            return current_user_can('edit_pages');
        },
    ));
});

function ccc_flush_cache_handler($request)
{
    $results = array();

    // 1. Elementor CSS cache
    if (class_exists('Elementor\Plugin')) {
        Elementor\Plugin::$instance->files_manager->clear_cache();
        $results[] = 'Elementor CSS cache cleared';
    }

    // 2. Elementor data cache for specific page
    $page_id = $request->get_param('page_id');
    if ($page_id) {
        delete_post_meta($page_id, '_elementor_css');
        $results[] = "Elementor CSS meta cleared for page #$page_id";
    }

    // 3. WordPress object cache
    wp_cache_flush();
    $results[] = 'WP object cache flushed';

    // 4. LiteSpeed cache (if active)
    if (class_exists('LiteSpeed\Purge')) {
        do_action('litespeed_purge_all');
        $results[] = 'LiteSpeed cache purged';
    }

    // 5. WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
        $results[] = 'WP Super Cache cleared';
    }

    // 6. W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        $results[] = 'W3 Total Cache flushed';
    }

    // 7. WP Fastest Cache
    if (function_exists('wpfc_clear_all_cache')) {
        wpfc_clear_all_cache();
        $results[] = 'WP Fastest Cache cleared';
    }

    return new WP_REST_Response(array(
        'success' => true,
        'actions' => $results,
    ), 200);
}
