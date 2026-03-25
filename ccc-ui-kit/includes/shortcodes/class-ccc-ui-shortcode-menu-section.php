<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Menu_Section
{
    const TAG = 'ccc_menu_section';

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
                'title' => 'Cardápio',
                'subtitle' => 'Bebidas, petiscos, pratos exclusivos e sobremesas',
                'description' => 'Use o botão principal para abrir o Prato Digital.',
                'food_slots_title' => 'Espaços de comidas',
                'food_slots_note' => '',
                'food_slots' => 'Bebidas|Petiscos|Pratos exclusivos|Especialidades Dom Antonio|Sobremesas',
                'show_food_slots' => 'true',
                'button_text' => 'Abrir Prato Digital',
                'button_url' => '',
                'open_in_new_tab' => 'true',
                'embed' => 'false',
                'pdf_url' => '',
            ),
            $atts,
            self::TAG
        );

        $embed = $this->to_bool($atts['embed']);
        $show_food_slots = $this->to_bool($atts['show_food_slots']);
        $open_in_new_tab = $this->to_bool($atts['open_in_new_tab']);

        $button_url = esc_url_raw((string) $atts['button_url']);
        $pdf_url = esc_url_raw((string) $atts['pdf_url']);
        $food_slots = $this->parse_pipe_list((string) $atts['food_slots']);

        if ($button_url === '' && $pdf_url !== '') {
            $button_url = $pdf_url;
        }

        $target = $open_in_new_tab ? '_blank' : '_self';
        $rel = $open_in_new_tab ? 'noopener noreferrer' : '';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-menu" data-ccc-ui-component="menu-section" data-ccc-ui-embed="<?php echo $embed ? '1' : '0'; ?>">
            <div class="ccc-ui-container">
                <article class="ccc-ui-menu__surface">
                    <header class="ccc-ui-menu__header">
                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>

                        <?php if ((string) $atts['subtitle'] !== '') : ?>
                            <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                        <?php endif; ?>
                    </header>

                    <?php if ((string) $atts['description'] !== '') : ?>
                        <p class="ccc-ui-body"><?php echo esc_html((string) $atts['description']); ?></p>
                    <?php endif; ?>

                    <?php if ($embed) : ?>
                        <div class="ccc-ui-menu__embed-placeholder" data-ccc-ui-menu-embed-placeholder>
                            Em breve: visualização embutida do cardápio.
                        </div>
                    <?php endif; ?>

                    <?php if ($show_food_slots && !empty($food_slots)) : ?>
                        <section class="ccc-ui-menu__slots" aria-label="Categorias de comidas">
                            <?php if ((string) $atts['food_slots_title'] !== '') : ?>
                                <h3 class="ccc-ui-menu__slots-title"><?php echo esc_html((string) $atts['food_slots_title']); ?></h3>
                            <?php endif; ?>

                            <?php if ((string) $atts['food_slots_note'] !== '') : ?>
                                <p class="ccc-ui-menu__slots-note"><?php echo esc_html((string) $atts['food_slots_note']); ?></p>
                            <?php endif; ?>

                            <div class="ccc-ui-menu__slots-grid">
                                <?php foreach ($food_slots as $slot_label) : ?>
                                    <article class="ccc-ui-menu__slot-item">
                                        <h4 class="ccc-ui-menu__slot-title"><?php echo esc_html($slot_label); ?></h4>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <div class="ccc-ui-actions ccc-ui-menu__actions">
                        <?php if ($button_url !== '') : ?>
                            <a class="ccc-ui-button ccc-ui-button--primary" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($target); ?>"<?php echo $rel !== '' ? ' rel="' . esc_attr($rel) . '"' : ''; ?>>
                                <?php echo esc_html((string) $atts['button_text']); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($pdf_url !== '') : ?>
                            <a class="ccc-ui-button ccc-ui-button--ghost" href="<?php echo esc_url($pdf_url); ?>" target="<?php echo esc_attr($target); ?>"<?php echo $rel !== '' ? ' rel="' . esc_attr($rel) . '"' : ''; ?>>
                                Ver cardápio em PDF
                            </a>
                        <?php endif; ?>
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
    private function parse_pipe_list($value)
    {
        $items = explode('|', $value);
        $items = array_map('trim', $items);

        return array_values(array_filter($items, static function ($item) {
            return $item !== '';
        }));
    }
}
