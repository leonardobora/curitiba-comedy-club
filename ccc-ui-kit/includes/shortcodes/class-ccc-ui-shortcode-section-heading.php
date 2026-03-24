<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Section_Heading
{
    const TAG = 'ccc_section_heading';

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
                'kicker' => '',
                'title' => 'Curitiba Comedy Club',
                'subtitle' => '',
                'align' => 'left',
            ),
            $atts,
            self::TAG
        );

        $align = in_array($atts['align'], array('left', 'center'), true) ? $atts['align'] : 'left';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-section-heading ccc-ui-section-heading--<?php echo esc_attr($align); ?>" data-ccc-ui-component="section-heading">
            <div class="ccc-ui-container">
                <header class="ccc-ui-section-heading__inner">
                    <?php if ($atts['kicker'] !== '') : ?>
                        <p class="ccc-ui-kicker"><?php echo esc_html($atts['kicker']); ?></p>
                    <?php endif; ?>

                    <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>

                    <?php if ($atts['subtitle'] !== '') : ?>
                        <p class="ccc-ui-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
                    <?php endif; ?>
                </header>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
