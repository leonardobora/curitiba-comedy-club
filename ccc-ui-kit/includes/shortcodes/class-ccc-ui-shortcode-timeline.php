<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Timeline
{
    const TAG = 'ccc_timeline';

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
                'kicker' => 'Nossa historia',
                'title' => 'Linha do tempo',
                'subtitle' => 'Marcos que moldaram o Curitiba Comedy Club.',
                'items' => '2010::Inauguracao pioneira::Nasce o primeiro comedy club dedicado do Brasil em Curitiba.|2020::Pausa da operacao::A pandemia forca o encerramento da sede original.|2021::Renascimento::A casa volta em Santa Felicidade, preservando o legado do palco.|Hoje::Nova fase::Curadoria nacional, Open Mic e experiencia completa de entretenimento.',
            ),
            $atts,
            self::TAG
        );

        $items = $this->parse_items((string) $atts['items']);

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-timeline" data-ccc-ui-component="timeline">
            <div class="ccc-ui-container">
                <article class="ccc-ui-timeline__surface">
                    <header class="ccc-ui-timeline__header">
                        <?php if ((string) $atts['kicker'] !== '') : ?>
                            <p class="ccc-ui-kicker"><?php echo esc_html((string) $atts['kicker']); ?></p>
                        <?php endif; ?>

                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>

                        <?php if ((string) $atts['subtitle'] !== '') : ?>
                            <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                        <?php endif; ?>
                    </header>

                    <div class="ccc-ui-timeline__list" role="list">
                        <?php foreach ($items as $item) : ?>
                            <article class="ccc-ui-timeline__item" role="listitem">
                                <div class="ccc-ui-timeline__marker" aria-hidden="true"></div>

                                <div class="ccc-ui-timeline__content">
                                    <p class="ccc-ui-timeline__period"><?php echo esc_html($item['period']); ?></p>
                                    <h3 class="ccc-ui-timeline__title"><?php echo esc_html($item['title']); ?></h3>

                                    <?php if ($item['text'] !== '') : ?>
                                        <p class="ccc-ui-timeline__text"><?php echo esc_html($item['text']); ?></p>
                                    <?php endif; ?>

                                    <?php if ($item['image_url'] !== '') : ?>
                                        <figure class="ccc-ui-timeline__figure">
                                            <img class="ccc-ui-timeline__image" src="<?php echo esc_url($item['image_url']); ?>" alt="Marco historico: <?php echo esc_attr($item['title']); ?>" loading="lazy">
                                        </figure>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
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
            $parts = array_map('trim', explode('::', $row));
            $items[] = array(
                'period' => isset($parts[0]) ? $parts[0] : '',
                'title' => isset($parts[1]) ? $parts[1] : '',
                'text' => isset($parts[2]) ? $parts[2] : '',
                'image_url' => isset($parts[3]) ? esc_url_raw($parts[3]) : '',
            );
        }

        if (empty($items)) {
            $items[] = array(
                'period' => 'Hoje',
                'title' => 'Historia em construcao',
                'text' => 'A casa segue escrevendo novos capitulos com publico e artistas.',
                'image_url' => '',
            );
        }

        return $items;
    }
}
