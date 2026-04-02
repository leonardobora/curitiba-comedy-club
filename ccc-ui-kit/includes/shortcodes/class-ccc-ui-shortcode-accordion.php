<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Accordion
{
    const TAG = 'ccc_accordion';

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
                'title' => 'Perguntas sobre a casa',
                'subtitle' => 'Detalhes importantes para quem quer conhecer melhor o Curitiba Comedy Club.',
                'items' => 'O Curitiba Comedy Club foi mesmo pioneiro?::Sim. A casa foi inaugurada em 2010 como o primeiro comedy club dedicado do Brasil.|O que mudou com a nova fase?::A operação evoluiu com nova sede em Santa Felicidade, mantendo o legado do palco e ampliando a experiência.|Como funciona o Open Mic?::O formato é voltado para testes de texto e desenvolvimento de novos comediantes, com curadoria da casa.',
                'open_first' => 'true',
                'allow_multiple' => 'false',
            ),
            $atts,
            self::TAG
        );

        $items = $this->parse_items((string) $atts['items']);
        $open_first = $this->to_bool($atts['open_first']);
        $allow_multiple = $this->to_bool($atts['allow_multiple']);
        $group_id = function_exists('wp_unique_id') ? wp_unique_id('ccc-ui-accordion-') : uniqid('ccc-ui-accordion-');

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-accordion" data-ccc-ui-component="accordion" data-ccc-accordion-allow-multiple="<?php echo $allow_multiple ? '1' : '0'; ?>">
            <div class="ccc-ui-container">
                <article class="ccc-ui-accordion__surface">
                    <header class="ccc-ui-accordion__header">
                        <?php if ((string) $atts['kicker'] !== '') : ?>
                            <p class="ccc-ui-kicker"><?php echo esc_html((string) $atts['kicker']); ?></p>
                        <?php endif; ?>

                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>

                        <?php if ((string) $atts['subtitle'] !== '') : ?>
                            <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                        <?php endif; ?>
                    </header>

                    <div class="ccc-ui-accordion__list">
                        <?php foreach ($items as $index => $item) : ?>
                            <?php
                            $is_open = $open_first && $index === 0;
                            $item_id = $group_id . '-' . $index;
                            $button_id = $item_id . '-button';
                            $panel_id = $item_id . '-panel';
                            ?>
                            <article class="ccc-ui-accordion__item" data-ccc-accordion-item>
                                <h3 class="ccc-ui-accordion__item-title">
                                    <button
                                        id="<?php echo esc_attr($button_id); ?>"
                                        class="ccc-ui-accordion__trigger"
                                        type="button"
                                        data-ccc-accordion-trigger
                                        aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                        aria-controls="<?php echo esc_attr($panel_id); ?>">
                                        <span><?php echo esc_html($item['title']); ?></span>
                                        <span class="ccc-ui-accordion__icon" aria-hidden="true">+</span>
                                    </button>
                                </h3>

                                <div
                                    id="<?php echo esc_attr($panel_id); ?>"
                                    class="ccc-ui-accordion__panel"
                                    data-ccc-accordion-panel
                                    role="region"
                                    aria-labelledby="<?php echo esc_attr($button_id); ?>"
                                    <?php echo $is_open ? '' : ' hidden'; ?>>
                                    <p class="ccc-ui-accordion__text"><?php echo esc_html($item['text']); ?></p>
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
     * @param mixed $value
     * @return bool
     */
    private function to_bool($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, array('1', 'true', 'yes', 'on'), true);
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
            $items[] = array(
                'title' => 'Pergunta frequente',
                'text' => 'Resposta em atualização.',
            );
        }

        return $items;
    }
}
