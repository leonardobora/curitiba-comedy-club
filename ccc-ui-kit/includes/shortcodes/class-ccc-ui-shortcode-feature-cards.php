<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Feature_Cards
{
    const TAG = 'ccc_feature_cards';

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
                'title' => 'Por que viver essa noite com a gente',
                'subtitle' => 'Diferenciais que transformam um show em experiência completa.',
                'items' => 'Curadoria de comediantes::Line-up selecionado com nomes fortes e revelações.|Casa premium::Conforto, som e visibilidade pensados para stand-up.|Gastronomia no ponto::Drinks e cozinha para acompanhar toda a noite.|Localização fácil::Acesso prático em uma das regiões mais conhecidas de Curitiba.',
            ),
            $atts,
            self::TAG
        );

        $items = $this->parse_items((string) $atts['items']);

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-feature-cards" data-ccc-ui-component="feature-cards">
            <div class="ccc-ui-container">
                <article class="ccc-ui-feature-cards__surface">
                    <header class="ccc-ui-feature-cards__header">
                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>
                        <?php if ((string) $atts['subtitle'] !== '') : ?>
                            <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                        <?php endif; ?>
                    </header>

                    <div class="ccc-ui-feature-cards__grid">
                        <?php foreach ($items as $item) : ?>
                            <article class="ccc-ui-feature-cards__item">
                                <h3 class="ccc-ui-feature-cards__item-title"><?php echo esc_html($item['title']); ?></h3>
                                <?php if ($item['text'] !== '') : ?>
                                    <p class="ccc-ui-feature-cards__item-text"><?php echo esc_html($item['text']); ?></p>
                                <?php endif; ?>
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
            $parts = explode('::', $row, 2);
            $items[] = array(
                'title' => isset($parts[0]) ? trim($parts[0]) : '',
                'text' => isset($parts[1]) ? trim($parts[1]) : '',
            );
        }

        if (empty($items)) {
            $items[] = array('title' => 'Experiência completa', 'text' => 'Estrutura e atendimento para uma noite memorável.');
        }

        return $items;
    }
}
