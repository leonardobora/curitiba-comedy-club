<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Page_Hero
{
    const TAG = 'ccc_page_hero';

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
        $atts = shortcode_atts(
            array(
                'kicker'      => 'Curitiba Comedy Club',
                'title'       => 'Noite premium de stand-up em Curitiba',
                'subtitle'    => 'Comedia, atmosfera cinematografica e publico apaixonado por humor.',
                'button_text' => 'Ver programacao',
                'button_url'  => '/programacao/',
                'align'       => 'left',
                'heading_level' => 'h1',
            ),
            $atts,
            self::TAG
        );

        $align = in_array($atts['align'], array('left', 'center'), true) ? $atts['align'] : 'left';
        $heading_level = in_array(strtolower((string) $atts['heading_level']), array('h1', 'h2', 'h3'), true)
            ? strtolower((string) $atts['heading_level'])
            : 'h1';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-hero ccc-ui-hero--<?php echo esc_attr($align); ?>" data-ccc-ui-component="page-hero">
            <div class="ccc-ui-container">
                <div class="ccc-ui-hero__surface">
                    <p class="ccc-ui-kicker"><?php echo esc_html($atts['kicker']); ?></p>
                    <<?php echo esc_html($heading_level); ?> class="ccc-ui-title ccc-ui-title--xl"><?php echo esc_html($atts['title']); ?></<?php echo esc_html($heading_level); ?>>
                    <p class="ccc-ui-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
                    <div class="ccc-ui-actions">
                        <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url($atts['button_url']); ?>">
                            <?php echo esc_html($atts['button_text']); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
