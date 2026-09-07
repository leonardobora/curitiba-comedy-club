<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Stats
{
    const TAG = 'ccc_stats';

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
                'items'  => '+15::anos de história|2000+::shows realizados|500+::comediantes no palco',
                'reveal' => 'yes',
            ),
            $atts,
            self::TAG
        );

        $items = $this->parse_items((string) $atts['items']);
        $reveal = $atts['reveal'] !== 'no';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-stats<?php echo $reveal ? ' ccc-ui-reveal' : ''; ?>" data-ccc-ui-component="stats"<?php echo $reveal ? ' data-ccc-reveal' : ''; ?>>
            <div class="ccc-ui-container">
                <div class="ccc-ui-stats__surface">
                    <div class="ccc-ui-stats__grid">
                        <?php foreach ($items as $item) : ?>
                            <div class="ccc-ui-stats__item">
                                <span class="ccc-ui-stats__number" data-ccc-stats-value="<?php echo esc_attr($item['number']); ?>"><?php echo esc_html($item['number']); ?></span>
                                <span class="ccc-ui-stats__desc"><?php echo esc_html($item['desc']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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
            $parts = array_map('trim', explode('::', $row, 2));
            $items[] = array(
                'number' => isset($parts[0]) ? $parts[0] : '0',
                'desc'   => isset($parts[1]) ? $parts[1] : '',
            );
        }

        return $items;
    }
}
