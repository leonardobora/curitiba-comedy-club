<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Divider
{
    const TAG = 'ccc_divider';

    /**
     * @return string
     */
    public function get_tag()
    {
        return self::TAG;
    }

    /**
     * @param array|string $atts
     * @param string|null  $content
     * @return string
     */
    public function render($atts, $content = null)
    {
        return '<hr class="ccc-ui-divider" aria-hidden="true">';
    }
}
