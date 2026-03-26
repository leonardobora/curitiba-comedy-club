<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Home_Photo_Wall
{
    const TAG = 'ccc_home_photo_wall';

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
                'title' => 'Atmosfera da casa',
                'subtitle' => 'Espaços para fotos da plateia, palco, artistas e bastidores.',
                'images' => '',
                'captions' => '',
                'autoplay' => 'true',
                'interval' => '4200',
            ),
            $atts,
            self::TAG
        );

        $images = $this->parse_pipe_list((string) $atts['images']);
        $captions = $this->parse_pipe_list((string) $atts['captions']);

        if (empty($images)) {
            $images = array(
                'https://via.placeholder.com/1200x720?text=Foto+1',
                'https://via.placeholder.com/1200x720?text=Foto+2',
                'https://via.placeholder.com/1200x720?text=Foto+3',
                'https://via.placeholder.com/1200x720?text=Foto+4',
            );
        }

        $interval = max(2500, (int) $atts['interval']);
        $autoplay = ((string) $atts['autoplay'] === 'true') ? '1' : '0';

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-home-photo-wall" data-ccc-ui-component="home-photo-wall" data-ccc-carousel-autoplay="<?php echo esc_attr($autoplay); ?>" data-ccc-carousel-interval="<?php echo esc_attr((string) $interval); ?>">
            <div class="ccc-ui-container">
                <article class="ccc-ui-home-photo-wall__surface">
                    <header class="ccc-ui-home-photo-wall__header">
                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html((string) $atts['title']); ?></h2>
                        <?php if ((string) $atts['subtitle'] !== '') : ?>
                            <p class="ccc-ui-subtitle"><?php echo esc_html((string) $atts['subtitle']); ?></p>
                        <?php endif; ?>
                    </header>

                    <div class="ccc-ui-home-photo-wall__viewport" data-ccc-carousel-viewport>
                        <div class="ccc-ui-home-photo-wall__track" data-ccc-carousel-track>
                            <?php foreach ($images as $index => $image_url) : ?>
                                <?php
                                $caption = isset($captions[$index]) ? $captions[$index] : '';
                                $safe_image_url = esc_url_raw($image_url);
                                if ($safe_image_url === '') {
                                    continue;
                                }
                                ?>
                                <figure class="ccc-ui-home-photo-wall__item" data-ccc-carousel-slide>
                                    <img class="ccc-ui-home-photo-wall__image" src="<?php echo esc_url($safe_image_url); ?>" alt="Foto da casa <?php echo esc_attr((string) ($index + 1)); ?>" loading="lazy">
                                    <?php if ($caption !== '') : ?>
                                        <figcaption class="ccc-ui-home-photo-wall__caption"><?php echo esc_html($caption); ?></figcaption>
                                    <?php endif; ?>
                                </figure>
                            <?php endforeach; ?>
                        </div>

                        <div class="ccc-ui-home-photo-wall__controls" data-ccc-carousel-controls>
                            <button type="button" class="ccc-ui-home-photo-wall__control" data-ccc-carousel-prev aria-label="Slide anterior">&#10094;</button>
                            <button type="button" class="ccc-ui-home-photo-wall__control" data-ccc-carousel-next aria-label="Próximo slide">&#10095;</button>
                        </div>
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
    private function parse_pipe_list($value)
    {
        $items = explode('|', $value);
        $items = array_map('trim', $items);

        return array_values(array_filter($items, static function ($item) {
            return $item !== '';
        }));
    }
}
