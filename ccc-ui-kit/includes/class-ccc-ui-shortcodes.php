<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcodes
{
    /**
     * @var array
     */
    private $shortcode_objects = array();

    public function register()
    {
        $this->shortcode_objects = array(
            new CCC_UI_Shortcode_Page_Hero(),
            new CCC_UI_Shortcode_Section_Heading(),
            new CCC_UI_Shortcode_About_Block(),
            new CCC_UI_Shortcode_Image_Frame(),
            new CCC_UI_Shortcode_CTA_Ingressos(),
            new CCC_UI_Shortcode_Menu_Section(),
            new CCC_UI_Shortcode_Contact_Section(),
        );

        foreach ($this->shortcode_objects as $shortcode) {
            add_shortcode($shortcode->get_tag(), array($shortcode, 'render'));
        }
    }
}
