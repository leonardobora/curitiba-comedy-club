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
                'height_desktop' => 'clamp(300px, 42vw, 500px)',
                'height_mobile' => 'clamp(220px, 56vw, 340px)',
                'autoplay' => 'true',
                'interval' => '5000',
                'alt_prefix' => 'Foto de destaque',
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
                <div class="ccc-ui-fullwidth-carousel__track" data-ccc-carousel-track style="display:flex; width:100%; transition:transform .45s ease; transform:translateX(0);">
                    <?php foreach ($images as $index => $image_url) : ?>
                        <?php
                        $safe_image_url = esc_url_raw($image_url);
                        if ($safe_image_url === '') {
                            continue;
                        }
                        $caption = isset($captions[$index]) ? $captions[$index] : '';
                        ?>
                        <figure class="ccc-ui-fullwidth-carousel__slide" data-ccc-carousel-slide style="flex:0 0 100%; margin:0; position:relative;">
                            <img class="ccc-ui-fullwidth-carousel__image" src="<?php echo esc_url($safe_image_url); ?>" alt="<?php echo esc_attr(trim((string) $atts['alt_prefix'])); ?> <?php echo esc_attr((string) ($index + 1)); ?>" loading="lazy" style="display:block; width:100%; height:100%; object-fit:cover;">
                            <?php if ($caption !== '') : ?>
                                <figcaption class="ccc-ui-fullwidth-carousel__caption" style="position:absolute; left:24px; right:auto; bottom:24px; margin:0; max-width:min(820px,86vw); padding:10px 14px; border-radius:10px; border:1px solid rgba(255,255,255,.2); background:rgba(0,0,0,.62); color:#fff; font-size:14px; line-height:1.45;"><?php echo esc_html($caption); ?></figcaption>
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
