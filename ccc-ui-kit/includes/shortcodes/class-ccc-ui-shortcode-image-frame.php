<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Image_Frame
{
    const TAG = 'ccc_image_frame';

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
                'image_url' => '',
                'alt' => 'Imagem do Curitiba Comedy Club',
                'caption' => '',
                'link_url' => '',
                'open_in_new_tab' => 'false',
            ),
            $atts,
            self::TAG
        );

        $image_url = esc_url_raw((string) $atts['image_url']);
        if ($image_url === '') {
            return '';
        }

        $link_url = esc_url_raw((string) $atts['link_url']);
        $open_in_new_tab = $this->to_bool($atts['open_in_new_tab']);
        $target = $open_in_new_tab ? '_blank' : '_self';
        $rel = $open_in_new_tab ? 'noopener noreferrer' : '';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-image-frame" data-ccc-ui-component="image-frame">
            <div class="ccc-ui-container">
                <figure class="ccc-ui-image-frame__surface">
                    <?php if ($link_url !== '') : ?>
                        <a class="ccc-ui-image-frame__link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($target); ?>"<?php echo $rel !== '' ? ' rel="' . esc_attr($rel) . '"' : ''; ?>>
                            <img class="ccc-ui-image-frame__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr((string) $atts['alt']); ?>" loading="lazy">
                        </a>
                    <?php else : ?>
                        <img class="ccc-ui-image-frame__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr((string) $atts['alt']); ?>" loading="lazy">
                    <?php endif; ?>

                    <?php if ((string) $atts['caption'] !== '') : ?>
                        <figcaption class="ccc-ui-image-frame__caption"><?php echo esc_html((string) $atts['caption']); ?></figcaption>
                    <?php endif; ?>
                </figure>
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
}
