<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Testimonials
{
    const TAG = 'ccc_testimonials';

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
                'kicker'      => 'Depoimentos',
                'title'       => 'Quem já viveu essa noite',
                'items'       => 'Melhor comedy club do Brasil. Ambiente incrível e shows de primeira.::Público frequente::Avaliação Google|A casa é referência nacional em stand-up. Sempre volto.::Comediante convidado::Bastidores|Noite perfeita: drinks, risadas e um som impecável.::Cliente corporativo::Evento privado',
                'autoplay'    => '1',
                'interval'    => '6000',
                'reveal'      => 'yes',
                'review_url'  => '',
                'review_text' => 'Deixe sua avaliação',
            ),
            $atts,
            self::TAG
        );

        $items = $this->parse_items((string) $atts['items']);
        $reveal = $atts['reveal'] !== 'no';

        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-testimonials<?php echo $reveal ? ' ccc-ui-reveal' : ''; ?>" data-ccc-ui-component="testimonials"<?php echo $reveal ? ' data-ccc-reveal' : ''; ?> data-ccc-carousel-autoplay="<?php echo esc_attr($atts['autoplay']); ?>" data-ccc-carousel-interval="<?php echo esc_attr($atts['interval']); ?>">
            <div class="ccc-ui-container">
                <article class="ccc-ui-testimonials__surface">
                    <header class="ccc-ui-testimonials__header">
                        <?php if ($atts['kicker'] !== '') : ?>
                            <p class="ccc-ui-kicker"><?php echo esc_html($atts['kicker']); ?></p>
                        <?php endif; ?>
                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>
                    </header>

                    <div class="ccc-ui-testimonials__viewport">
                        <div class="ccc-ui-testimonials__track" data-ccc-carousel-track>
                            <?php foreach ($items as $item) : ?>
                                <blockquote class="ccc-ui-testimonials__slide" data-ccc-carousel-slide>
                                    <span class="ccc-ui-testimonials__quote" aria-hidden="true">&ldquo;</span>
                                    <p class="ccc-ui-testimonials__text"><?php echo esc_html($item['text']); ?></p>
                                    <footer class="ccc-ui-testimonials__footer">
                                        <cite class="ccc-ui-testimonials__author"><?php echo esc_html($item['author']); ?></cite>
                                        <?php if ($item['context'] !== '') : ?>
                                            <span class="ccc-ui-testimonials__context"><?php echo esc_html($item['context']); ?></span>
                                        <?php endif; ?>
                                    </footer>
                                </blockquote>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($items) > 1) : ?>
                            <div class="ccc-ui-testimonials__controls">
                                <button type="button" class="ccc-ui-testimonials__control" data-ccc-carousel-prev aria-label="Depoimento anterior">&#10094;</button>
                                <button type="button" class="ccc-ui-testimonials__control" data-ccc-carousel-next aria-label="Próximo depoimento">&#10095;</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($atts['review_url'] !== '') : ?>
                        <footer class="ccc-ui-testimonials__review">
                            <a href="<?php echo esc_url($atts['review_url']); ?>" target="_blank" rel="noopener noreferrer" class="ccc-ui-btn ccc-ui-btn--outline">
                                <?php echo esc_html($atts['review_text']); ?>
                            </a>
                        </footer>
                    <?php endif; ?>
                </article>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }

    /**
     * @param string $value
     * @return array
     */
    private function parse_items($value)
    {
        $rows = array_filter(array_map('trim', explode('|', $value)));
        $items = array();

        foreach ($rows as $row) {
            $parts = array_map('trim', explode('::', $row, 3));
            $items[] = array(
                'text'    => isset($parts[0]) ? $parts[0] : '',
                'author'  => isset($parts[1]) ? $parts[1] : '',
                'context' => isset($parts[2]) ? $parts[2] : '',
            );
        }

        return $items;
    }
}
