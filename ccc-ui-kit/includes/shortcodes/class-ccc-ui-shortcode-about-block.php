<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_About_Block
{
    const TAG = 'ccc_about_block';

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
                'kicker' => 'Sobre a casa',
                'title' => 'Comédia premium no coração de Curitiba',
                'text' => 'O Curitiba Comedy Club entrega uma experiência completa: palco intimista, curadoria de comediantes e clima de casa de espetáculo para noites memoráveis.',
                'highlights' => 'Palco profissional|Drinks e cozinha|Programação semanal',
                'button_text' => 'Ver programação',
                'button_url' => '/programacao/',
            ),
            $atts,
            self::TAG
        );

        $highlights = array_filter(array_map('trim', explode('|', (string) $atts['highlights'])));

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-about" data-ccc-ui-component="about-block">
            <div class="ccc-ui-container">
                <article class="ccc-ui-about__surface">
                    <?php if ($atts['kicker'] !== '') : ?>
                        <p class="ccc-ui-kicker"><?php echo esc_html($atts['kicker']); ?></p>
                    <?php endif; ?>

                    <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>
                    <p class="ccc-ui-body"><?php echo esc_html($atts['text']); ?></p>

                    <?php if (!empty($highlights)) : ?>
                        <ul class="ccc-ui-about__highlights">
                            <?php foreach ($highlights as $item) : ?>
                                <li class="ccc-ui-about__highlight-item"><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="ccc-ui-actions">
                        <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url($atts['button_url']); ?>">
                            <?php echo esc_html($atts['button_text']); ?>
                        </a>
                    </div>
                </article>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
