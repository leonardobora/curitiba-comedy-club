<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Split_CTA
{
    const TAG = 'ccc_split_cta';

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
                'title' => 'Escolha sua próxima experiência',
                'subtitle' => 'Garanta ingresso para os shows ou fale com a equipe sobre eventos privados.',
                'primary_text' => 'Ver programação',
                'primary_url' => '/programacao/',
                'secondary_text' => 'Eventos privados',
                'secondary_url' => '/eventos-privados/',
            ),
            $atts,
            self::TAG
        );

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-split-cta" data-ccc-ui-component="split-cta">
            <div class="ccc-ui-container">
                <article class="ccc-ui-split-cta__surface">
                    <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>
                    <?php if ((string) $atts['subtitle'] !== '') : ?>
                        <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                    <?php endif; ?>

                    <div class="ccc-ui-actions ccc-ui-split-cta__actions">
                        <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url((string) $atts['primary_url']); ?>">
                            <?php echo esc_html((string) $atts['primary_text']); ?>
                        </a>
                        <a class="ccc-ui-button ccc-ui-button--ghost" href="<?php echo esc_url((string) $atts['secondary_url']); ?>">
                            <?php echo esc_html((string) $atts['secondary_text']); ?>
                        </a>
                    </div>
                </article>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
