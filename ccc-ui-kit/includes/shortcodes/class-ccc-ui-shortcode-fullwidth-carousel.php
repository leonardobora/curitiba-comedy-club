<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Fullwidth_Carousel
{
    const TAG = 'ccc_fullwidth_carousel';

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
                'images' => '',
                'captions' => '',
                'height_desktop' => 'clamp(320px, 52vw, 620px)',
                'height_mobile' => '56vw',
                'autoplay' => 'true',
                'interval' => '5000',
            ),
            $atts,
            self::TAG
        );

        $images = $this->parse_pipe_list((string) $atts['images']);
        $captions = $this->parse_pipe_list((string) $atts['captions']);

        if (empty($images)) {
            $images = array(
                'https://via.placeholder.com/1920x1080?text=Foto+Home+1',
                'https://via.placeholder.com/1920x1080?text=Foto+Home+2',
                'https://via.placeholder.com/1920x1080?text=Foto+Home+3',
            );
        }

        $interval = max(2500, (int) $atts['interval']);
        $autoplay = ((string) $atts['autoplay'] === 'true') ? '1' : '0';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-fullwidth-carousel" data-ccc-ui-component="fullwidth-carousel" data-ccc-carousel-autoplay="<?php echo esc_attr($autoplay); ?>" data-ccc-carousel-interval="<?php echo esc_attr((string) $interval); ?>" style="--ccc-carousel-height-desktop: <?php echo esc_attr((string) $atts['height_desktop']); ?>; --ccc-carousel-height-mobile: <?php echo esc_attr((string) $atts['height_mobile']); ?>;">
            <div class="ccc-ui-fullwidth-carousel__viewport" data-ccc-carousel-viewport>
                <div class="ccc-ui-fullwidth-carousel__track" data-ccc-carousel-track>
                    <?php foreach ($images as $index => $image_url) : ?>
                        <?php
                        $safe_image_url = esc_url_raw($image_url);
                        if ($safe_image_url === '') {
                            continue;
                        }
                        $caption = isset($captions[$index]) ? $captions[$index] : '';
                        ?>
                        <figure class="ccc-ui-fullwidth-carousel__slide" data-ccc-carousel-slide>
                            <img class="ccc-ui-fullwidth-carousel__image" src="<?php echo esc_url($safe_image_url); ?>" alt="Foto de destaque <?php echo esc_attr((string) ($index + 1)); ?>" loading="lazy">
                            <?php if ($caption !== '') : ?>
                                <figcaption class="ccc-ui-fullwidth-carousel__caption"><?php echo esc_html($caption); ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endforeach; ?>
                </div>

                <div class="ccc-ui-fullwidth-carousel__controls" data-ccc-carousel-controls>
                    <button type="button" class="ccc-ui-fullwidth-carousel__control" data-ccc-carousel-prev aria-label="Slide anterior">&#10094;</button>
                    <button type="button" class="ccc-ui-fullwidth-carousel__control" data-ccc-carousel-next aria-label="Próximo slide">&#10095;</button>
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
    private function parse_pipe_list($value)
    {
        $items = explode('|', $value);
        $items = array_map('trim', $items);

        return array_values(array_filter($items, static function ($item) {
            return $item !== '';
        }));
    }
}
