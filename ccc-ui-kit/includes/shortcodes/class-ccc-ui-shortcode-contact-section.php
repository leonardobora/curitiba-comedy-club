<?php

if (!defined('ABSPATH')) {
    exit;
}

final class CCC_UI_Shortcode_Contact_Section
{
    const TAG = 'ccc_contact_section';

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
                'title'     => 'Contato',
                'address'   => 'Rua Exemplo, 123 - Curitiba/PR',
                'whatsapp'  => '(41) 99999-9999',
                'instagram' => '@curitibacomedyclub',
                'email'     => 'contato@curitibacomedyclub.com.br',
                'map_url'   => '',
            ),
            $atts,
            self::TAG
        );

        $phone_display = trim((string) $atts['whatsapp']);
        $phone_link = preg_replace('/[^0-9+]/', '', $phone_display);

        $email = sanitize_email((string) $atts['email']);

        $instagram_raw = trim((string) $atts['instagram']);
        $instagram_url = '';
        if ($instagram_raw !== '') {
            if (filter_var($instagram_raw, FILTER_VALIDATE_URL)) {
                $instagram_url = $instagram_raw;
            } else {
                $instagram_handle = ltrim($instagram_raw, '@');
                $instagram_handle = preg_replace('/[^A-Za-z0-9._]/', '', $instagram_handle);
                if ($instagram_handle !== '') {
                    $instagram_url = 'https://instagram.com/' . $instagram_handle;
                }
            }
        }

        $address = trim((string) $atts['address']);
        $map_url = trim((string) $atts['map_url']);
        if ($map_url === '' && $address !== '') {
            $map_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);
        }

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-contact" data-ccc-ui-component="contact-section">
            <div class="ccc-ui-container">
                <div class="ccc-ui-contact__surface">
                    <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>

                    <address class="ccc-ui-contact__grid">
                        <div class="ccc-ui-contact__item">
                            <span class="ccc-ui-label">Endereco</span>
                            <p class="ccc-ui-body">
                                <?php if ($map_url !== '' && $address !== '') : ?>
                                    <a class="ccc-ui-contact__link" href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($address); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($address); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="ccc-ui-contact__item">
                            <span class="ccc-ui-label">WhatsApp</span>
                            <p class="ccc-ui-body">
                                <?php if (!empty($phone_link)) : ?>
                                    <a class="ccc-ui-contact__link" href="tel:<?php echo esc_attr($phone_link); ?>"><?php echo esc_html($phone_display); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($phone_display); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="ccc-ui-contact__item">
                            <span class="ccc-ui-label">Instagram</span>
                            <p class="ccc-ui-body">
                                <?php if ($instagram_url !== '') : ?>
                                    <a class="ccc-ui-contact__link" href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($instagram_raw); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html($instagram_raw); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="ccc-ui-contact__item">
                            <span class="ccc-ui-label">E-mail</span>
                            <p class="ccc-ui-body">
                                <?php if (!empty($email)) : ?>
                                    <a class="ccc-ui-contact__link" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html((string) $atts['email']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </address>

                    <?php if ($map_url !== '') : ?>
                        <p class="ccc-ui-contact__map-link-wrap">
                            <a class="ccc-ui-button ccc-ui-button--ghost" href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer">
                                Ver local no mapa
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php

        return (string) ob_get_clean();
    }
}
