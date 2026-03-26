<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Section_Nav
{
    const TAG = 'ccc_section_nav';

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
                'title' => 'Navegue pela história',
                'sections' => 'Visão geral::sobre-visao|Linha do tempo::sobre-timeline|Legado::sobre-legado|Perguntas::sobre-perguntas',
                'sticky' => 'true',
            ),
            $atts,
            self::TAG
        );

        $sections = $this->parse_sections((string) $atts['sections']);
        $sticky = $this->to_bool($atts['sticky']);

        if (empty($sections)) {
            return '';
        }

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-section-nav" data-ccc-ui-component="section-nav">
            <div class="ccc-ui-container">
                <nav class="ccc-ui-section-nav__surface<?php echo $sticky ? ' ccc-ui-section-nav__surface--sticky' : ''; ?>" aria-label="Navegacao de secoes da pagina Sobre">
                    <?php if ((string) $atts['title'] !== '') : ?>
                        <p class="ccc-ui-section-nav__title"><?php echo esc_html((string) $atts['title']); ?></p>
                    <?php endif; ?>

                    <div class="ccc-ui-section-nav__links" data-ccc-section-links>
                        <?php foreach ($sections as $index => $section) : ?>
                            <a class="ccc-ui-section-nav__link<?php echo $index === 0 ? ' is-active' : ''; ?>" href="#<?php echo esc_attr($section['id']); ?>" data-ccc-section-link><?php echo esc_html($section['label']); ?></a>
                        <?php endforeach; ?>
                    </div>
                </nav>
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
    private function parse_sections($value)
    {
        $rows = array_filter(array_map('trim', explode('|', $value)));
        $sections = array();

        foreach ($rows as $row) {
            $parts = explode('::', $row, 2);
            $label = isset($parts[0]) ? trim($parts[0]) : '';
            $id = isset($parts[1]) ? trim($parts[1]) : '';
            $id = ltrim($id, '#');

            if ($label === '' || $id === '') {
                continue;
            }

            $sections[] = array(
                'label' => $label,
                'id' => sanitize_title($id),
            );
        }

        return $sections;
    }
}
