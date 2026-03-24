<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_CTA_Ingressos
{
    const TAG = 'ccc_cta_ingressos';

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
                'title'       => 'Garanta seu ingresso',
                'text'        => 'A programacao muda toda semana. Escolha o show e reserve seu lugar.',
                'button_text' => 'Comprar agora',
                'button_url'  => '/programacao/',
            ),
            $atts,
            self::TAG
        );

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-cta-ingressos" data-ccc-ui-component="cta-ingressos">
            <div class="ccc-ui-container">
                <div class="ccc-ui-cta-ingressos__surface">
                    <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>
                    <p class="ccc-ui-body"><?php echo esc_html($atts['text']); ?></p>
                    <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url($atts['button_url']); ?>">
                        <?php echo esc_html($atts['button_text']); ?>
                    </a>
                </div>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
