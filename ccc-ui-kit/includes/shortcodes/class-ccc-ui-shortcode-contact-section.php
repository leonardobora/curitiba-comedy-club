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
                'whatsapp_note' => 'Somente mensagens. Não atendemos ligações.',
                'instagram' => '@curitibacomedy',
                'map_url'   => '',
                'linktree_url' => '',
                'linktree_text' => 'Chamar no WhatsApp',
            ),
            $atts,
            self::TAG
        );

        $address = trim((string) $atts['address']);

        $phone_display = trim((string) $atts['whatsapp']);
        $phone_digits = preg_replace('/\D+/', '', $phone_display);
        $whatsapp_note = trim((string) $atts['whatsapp_note']);
        $whatsapp_text = rawurlencode('Oi! Tudo bem? Quero falar com o Curitiba Comedy Club.');
        $whatsapp_url = $phone_digits !== '' ? 'https://wa.me/' . $phone_digits . '?text=' . $whatsapp_text : '';

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

        $map_url = trim((string) $atts['map_url']);
        if ($map_url === '' && $address !== '') {
            $map_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);
        }

        $map_embed_query = $address !== '' ? $address : $map_url;
        $map_embed_url = '';
        if ($map_embed_query !== '') {
            $map_embed_url = 'https://www.google.com/maps?q=' . rawurlencode($map_embed_query) . '&output=embed';
        }

        $linktree_url = trim((string) $atts['linktree_url']);
        if ($linktree_url === '') {
            $linktree_url = $whatsapp_url;
        }

        ob_start();
        ?>
        <section class="ccc-ui-section ccc-ui-contact" data-ccc-ui-component="contact-section">
            <div class="ccc-ui-container">
                <div class="ccc-ui-contact__surface">
                    <header class="ccc-ui-contact__header">
                        <h2 class="ccc-ui-title ccc-ui-title--lg"><?php echo esc_html($atts['title']); ?></h2>
                        <?php if ($linktree_url !== '') : ?>
                            <a class="ccc-ui-button ccc-ui-button--ghost ccc-ui-contact__linktree" href="<?php echo esc_url($linktree_url); ?>" target="_blank" rel="noopener noreferrer">
                                <?php echo esc_html((string) $atts['linktree_text']); ?>
                            </a>
                        <?php endif; ?>
                    </header>

                    <address class="ccc-ui-contact__grid">
                        <div class="ccc-ui-contact__item">
                            <?php if ($map_url !== '' && $address !== '') : ?>
                                <a class="ccc-ui-contact__line ccc-ui-contact__link" href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.3 7 13 7 13s7-7.7 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($address); ?></span>
                                </a>
                            <?php else : ?>
                                <div class="ccc-ui-contact__line">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.3 7 13 7 13s7-7.7 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($address); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="ccc-ui-contact__item">
                            <?php if ($whatsapp_url !== '') : ?>
                                <a class="ccc-ui-contact__line ccc-ui-contact__link" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.3 11.3 0 0 0 3.56.57 1 1 0 0 1 1 1V21a1 1 0 0 1-1 1A18 18 0 0 1 2 4a1 1 0 0 1 1-1h4.5a1 1 0 0 1 1 1 11.3 11.3 0 0 0 .57 3.56 1 1 0 0 1-.25 1L6.6 10.8Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($phone_display); ?>
                                        <?php if ($whatsapp_note !== '') : ?>
                                            <small class="ccc-ui-contact__value-note"><?php echo esc_html($whatsapp_note); ?></small>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            <?php else : ?>
                                <div class="ccc-ui-contact__line">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.3 11.3 0 0 0 3.56.57 1 1 0 0 1 1 1V21a1 1 0 0 1-1 1A18 18 0 0 1 2 4a1 1 0 0 1 1-1h4.5a1 1 0 0 1 1 1 11.3 11.3 0 0 0 .57 3.56 1 1 0 0 1-.25 1L6.6 10.8Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($phone_display); ?>
                                        <?php if ($whatsapp_note !== '') : ?>
                                            <small class="ccc-ui-contact__value-note"><?php echo esc_html($whatsapp_note); ?></small>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="ccc-ui-contact__item">
                            <?php if ($instagram_url !== '') : ?>
                                <a class="ccc-ui-contact__line ccc-ui-contact__link" href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener noreferrer">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2.2A2.8 2.8 0 0 0 4.2 7v10A2.8 2.8 0 0 0 7 19.8h10a2.8 2.8 0 0 0 2.8-2.8V7A2.8 2.8 0 0 0 17 4.2H7Zm5 2.3A5.5 5.5 0 1 1 6.5 12 5.5 5.5 0 0 1 12 6.5Zm0 2.2a3.3 3.3 0 1 0 3.3 3.3A3.3 3.3 0 0 0 12 8.7Zm5.75-2.95a1.3 1.3 0 1 1-1.3 1.3 1.3 1.3 0 0 1 1.3-1.3Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($instagram_raw); ?></span>
                                </a>
                            <?php else : ?>
                                <div class="ccc-ui-contact__line">
                                    <span class="ccc-ui-contact__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" width="18" height="18" focusable="false" aria-hidden="true"><path fill="currentColor" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2.2A2.8 2.8 0 0 0 4.2 7v10A2.8 2.8 0 0 0 7 19.8h10a2.8 2.8 0 0 0 2.8-2.8V7A2.8 2.8 0 0 0 17 4.2H7Zm5 2.3A5.5 5.5 0 1 1 6.5 12 5.5 5.5 0 0 1 12 6.5Zm0 2.2a3.3 3.3 0 1 0 3.3 3.3A3.3 3.3 0 0 0 12 8.7Zm5.75-2.95a1.3 1.3 0 1 1-1.3 1.3 1.3 1.3 0 0 1 1.3-1.3Z"/></svg>
                                    </span>
                                    <span class="ccc-ui-contact__value"><?php echo esc_html($instagram_raw); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                    </address>

                    <?php if ($map_embed_url !== '') : ?>
                        <div class="ccc-ui-contact__map-wrap">
                            <iframe
                                class="ccc-ui-contact__map"
                                src="<?php echo esc_url($map_embed_url); ?>"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                                title="Mapa do local do Curitiba Comedy Club">
                            </iframe>
                        </div>
                    <?php endif; ?>

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
