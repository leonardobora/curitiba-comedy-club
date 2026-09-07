<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Countdown
{
    const TAG = 'ccc_countdown';

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
                'date'          => '',
                'label'         => 'Próximo show',
                'expired_label' => 'Confira a programação',
                'link'          => '/programacao/',
                'link_text'     => 'Ver programação',
                'reveal'        => 'yes',
            ),
            $atts,
            self::TAG
        );

        if ($atts['date'] === '') {
            return '';
        }

        $reveal = $atts['reveal'] !== 'no';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-countdown<?php echo $reveal ? ' ccc-ui-reveal' : ''; ?>" data-ccc-ui-component="countdown"<?php echo $reveal ? ' data-ccc-reveal' : ''; ?>>
            <div class="ccc-ui-container">
                <div class="ccc-ui-countdown__surface" data-ccc-countdown-target="<?php echo esc_attr($atts['date']); ?>">
                    <p class="ccc-ui-kicker"><?php echo esc_html($atts['label']); ?></p>

                    <div class="ccc-ui-countdown__grid" data-ccc-countdown-grid>
                        <div class="ccc-ui-countdown__unit">
                            <span class="ccc-ui-countdown__number" data-ccc-countdown-days>--</span>
                            <span class="ccc-ui-countdown__label">dias</span>
                        </div>
                        <div class="ccc-ui-countdown__unit">
                            <span class="ccc-ui-countdown__number" data-ccc-countdown-hours>--</span>
                            <span class="ccc-ui-countdown__label">horas</span>
                        </div>
                        <div class="ccc-ui-countdown__unit">
                            <span class="ccc-ui-countdown__number" data-ccc-countdown-mins>--</span>
                            <span class="ccc-ui-countdown__label">minutos</span>
                        </div>
                        <div class="ccc-ui-countdown__unit">
                            <span class="ccc-ui-countdown__number" data-ccc-countdown-secs>--</span>
                            <span class="ccc-ui-countdown__label">segundos</span>
                        </div>
                    </div>

                    <div class="ccc-ui-countdown__expired" data-ccc-countdown-expired hidden>
                        <p class="ccc-ui-body"><?php echo esc_html($atts['expired_label']); ?></p>
                        <?php if ($atts['link'] !== '') : ?>
                            <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url($atts['link']); ?>">
                                <?php echo esc_html($atts['link_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
