<?php
/**
 * Plugin Name: CCC Eventos Standapp
 * Plugin URI: https://curitibacomedyclub.com.br/
 * Description: Lista eventos do Curitiba Comedy Club via API Standapp com shortcode [eventos_standapp].
 * Version: 3.2.3
 * Author: Curitiba Comedy Club
 * License: GPL2+
 * Text Domain: ccc-eventos-standapp
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CCC_Eventos_Standapp')) {

    final class CCC_Eventos_Standapp
    {
        const VERSION = '3.2.3';
        const SHORTCODE = 'eventos_standapp';
        const SHORTCODE_HOME = 'eventos_standapp_home';
        const SHORTCODE_HOJE = 'eventos_standapp_hoje';
        const API_URL = 'https://api.standapp.com.br/live/presentation/list-by-presentation-hall/2';
        const CACHE_KEY = 'ccc_standapp_eventos_v311';
        const CACHE_TTL = 300; // 5 minutos
        const TIMEZONE = 'America/Sao_Paulo';

        /**
         * ATENÇÃO:
         * Cole aqui o MESMO token usado hoje no seu plugin v3.0.
         * Não altere a estrutura do Bearer, só mantenha o token atual.
         */
        const API_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL2hhc3VyYS5pby9qd3QvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsidmlzaXRvciIsImxvZ2dlZCJdLCJ4LWhhc3VyYS1kZWZhdWx0LXJvbGUiOiJ2aXNpdG9yIiwieC1oYXN1cmEtdXNlci1pZCI6IjAifX0.-yI-yUFqsBe6puXTq9znDnLbAZSzlzl6TF_YfPyJtPc';

        /**
         * Evita renderizar o banner duas vezes quando a auto-injeção
         * (astra_content_before) e um shortcode manual coexistem.
         *
         * @var bool
         */
        private $today_banner_rendered = false;

        public function __construct()
        {
            add_shortcode(self::SHORTCODE, array($this, 'render_shortcode'));
            add_shortcode(self::SHORTCODE_HOME, array($this, 'render_home_shortcode'));
            add_shortcode(self::SHORTCODE_HOJE, array($this, 'render_today_banner'));
            add_action('wp_enqueue_scripts', array($this, 'register_assets'));
            add_action('astra_content_before', array($this, 'maybe_inject_today_banner'));
        }

        /**
         * Shortcode dedicado para Home: sempre retorna eventos próximos da semana com limite enxuto.
         *
         * @param array $atts
         * @return string
         */
        public function render_home_shortcode($atts = array())
        {
            $atts = shortcode_atts(array(
                'titulo'           => 'Próximos eventos',
                'limit'            => '6',
                'dias_proximos'    => '7',
                'mostrar_filtros'  => 'no',
                'mostrar_busca'    => 'no',
                'mostrar_badges'   => 'yes',
                'cache'            => 'yes',
                'somente_proximos' => 'yes',
            ), $atts, self::SHORTCODE_HOME);

            return $this->render_shortcode($atts);
        }

        /**
         * Banner horizontal com o ingresso de hoje. Dinamicamente atualizado:
         * usa a mesma fonte da agenda e é escondido no cliente se a data não
         * for mais hoje (blindagem contra page cache).
         *
         * @param array $atts
         * @return string
         */
        public function render_today_banner($atts = array())
        {
            if ($this->today_banner_rendered) {
                return '';
            }

            $atts = shortcode_atts(array(
                'label'  => 'Hoje',
                'cta'    => 'Comprar ingresso',
                'cache'  => 'yes',
            ), $atts, self::SHORTCODE_HOJE);

            $events = $this->get_events($atts['cache'] === 'yes');
            $today_event = $this->find_today_event($events);

            if (empty($today_event) || empty($today_event['timestamp'])) {
                return '';
            }

            $this->today_banner_rendered = true;

            $tz = new DateTimeZone(self::TIMEZONE);
            $date_key = (new DateTimeImmutable('@' . (int) $today_event['timestamp']))->setTimezone($tz)->format('Y-m-d');

            ob_start();

            echo '<div class="ccc-standapp-today" data-ccc-today data-timestamp="' . esc_attr((string) $today_event['timestamp']) . '" data-date="' . esc_attr($date_key) . '">';
            echo '<div class="ccc-standapp-today__info">';
            echo '<span class="ccc-standapp-today__label">' . esc_html($atts['label']) . '</span>';

            if (!empty($today_event['title'])) {
                echo '<span class="ccc-standapp-today__title">' . esc_html($today_event['title']) . '</span>';
            }

            if (!empty($today_event['time_label'])) {
                echo '<span class="ccc-standapp-today__time">às ' . esc_html($today_event['time_label']) . '</span>';
            }

            echo '</div>';

            if (!empty($today_event['buy_url'])) {
                echo '<a class="ccc-standapp-today__cta" href="' . esc_url($today_event['buy_url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($atts['cta']) . '</a>';
            }

            echo '</div>';

            return ob_get_clean();
        }

        /**
         * Injeção automática do banner "ingresso de hoje" no topo da Home,
         * independente de Elementor/shortcode manual. Disparada pelo hook
         * `astra_content_before` apenas na página inicial.
         */
        public function maybe_inject_today_banner()
        {
            if (!is_front_page()) {
                return;
            }

            if (!apply_filters('ccc_standapp_auto_inject_today', true)) {
                return;
            }

            echo $this->render_today_banner();
        }

        /**
         * Retorna o próximo evento de hoje (mais cedo) ou null se não houver.
         *
         * @param array $events
         * @return array|null
         */
        private function find_today_event($events)
        {
            if (!is_array($events) || empty($events)) {
                return null;
            }

            $tz = new DateTimeZone(self::TIMEZONE);
            $today = (new DateTimeImmutable('now', $tz))->format('Y-m-d');
            $today_events = array();

            foreach ($events as $event) {
                if (empty($event['timestamp'])) {
                    continue;
                }

                $dt = (new DateTimeImmutable('@' . (int) $event['timestamp']))->setTimezone($tz);
                if ($dt->format('Y-m-d') === $today) {
                    $today_events[] = $event;
                }
            }

            if (empty($today_events)) {
                return null;
            }

            usort($today_events, function ($a, $b) {
                return ((int) $a['timestamp']) <=> ((int) $b['timestamp']);
            });

            return $today_events[0];
        }

        public function register_assets()
        {
            $handle = 'ccc-standapp-inline-assets';

            wp_register_style($handle, false, array(), self::VERSION);
            wp_enqueue_style($handle);
            wp_add_inline_style($handle, $this->get_inline_css());

            wp_register_script($handle, '', array(), self::VERSION, true);
            wp_enqueue_script($handle);
            wp_add_inline_script($handle, $this->get_inline_js());
        }

        public function render_shortcode($atts = array())
        {
            $atts = shortcode_atts(array(
                'titulo'          => '',
                'mostrar_filtros' => 'yes',
                'mostrar_busca'   => 'yes',
                'mostrar_badges'  => 'yes',
                'cache'           => 'yes',
                'somente_proximos' => 'yes',
                'limit'           => '0',
                'dias_proximos'   => '0',
            ), $atts, self::SHORTCODE);

            $use_cache = ($atts['cache'] === 'yes');
            $events = $this->get_events($use_cache);
            $events = $this->filter_events($events, $atts);
            $limit_attr = isset($atts['limit']) ? max(0, (int) $atts['limit']) : 0;
            $current_month = $this->get_current_month_key();
            $render_dt = new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE));

            ob_start();

            echo '<section class="ccc-standapp-wrap" data-ccc-standapp-root data-ccc-limit="' . esc_attr((string) $limit_attr) . '" data-ccc-current-month="' . esc_attr($current_month) . '">';
            echo '<!-- CCC agenda por Leonardo Bora, eximio programador -->';
            echo '<!-- CCC agenda renderizada em ' . esc_html($render_dt->format('Y-m-d H:i P')) . ' -->';
            echo '<div class="ccc-standapp-header">';

            if (!empty($atts['titulo'])) {
                echo '<h2 class="ccc-standapp-title">' . esc_html($atts['titulo']) . '</h2>';
            }

            echo '</div>';

            if (!empty($events) && $atts['mostrar_filtros'] === 'yes') {
                echo $this->render_filters($events, $atts);
            }

            if (empty($events)) {
                echo $this->render_empty_state();
            } else {
                echo '<div class="ccc-standapp-grid" data-ccc-event-grid>';

                foreach ($events as $event) {
                    echo $this->render_event_card($event, $atts);
                }

                echo '</div>';
                echo '<div class="ccc-standapp-no-results" data-ccc-no-results hidden>Nenhum evento encontrado para os filtros selecionados.</div>';
            }

            echo '</section>';

            return ob_get_clean();
        }

        /**
         * @param array $events
         * @param array $atts
         * @return array
         */
        private function filter_events($events, $atts)
        {
            if (!is_array($events)) {
                return array();
            }

            $somente_proximos = isset($atts['somente_proximos']) && $atts['somente_proximos'] === 'yes';
            $limit = isset($atts['limit']) ? (int) $atts['limit'] : 0;
            $dias_proximos = isset($atts['dias_proximos']) ? (int) $atts['dias_proximos'] : 0;

            if ($somente_proximos) {
                $events = array_values(array_filter($events, function ($event) {
                    if (!isset($event['timestamp'])) {
                        return false;
                    }

                    return $this->is_future_event((int) $event['timestamp']);
                }));
            }

            if ($dias_proximos > 0) {
                $events = array_values(array_filter($events, function ($event) use ($dias_proximos) {
                    if (!isset($event['timestamp'])) {
                        return false;
                    }

                    return $this->is_within_next_days((int) $event['timestamp'], $dias_proximos);
                }));
            }

            if ($limit > 0) {
                $events = array_slice($events, 0, $limit);
            }

            return $events;
        }

        /**
         * @param int $timestamp
         * @return bool
         */
        private function is_future_event($timestamp)
        {
            $tz = new DateTimeZone(self::TIMEZONE);
            $now = new DateTimeImmutable('now', $tz);

            return $timestamp >= (int) $now->getTimestamp();
        }

        /**
         * @param int $timestamp
         * @param int $days
         * @return bool
         */
        private function is_within_next_days($timestamp, $days)
        {
            if ($days <= 0) {
                return true;
            }

            $tz = new DateTimeZone(self::TIMEZONE);
            $now = new DateTimeImmutable('now', $tz);
            $max = $now->modify('+' . (int) $days . ' days');

            return $timestamp >= (int) $now->getTimestamp() && $timestamp <= (int) $max->getTimestamp();
        }

        /**
         * Mês atual (Y-m) no timezone da casa. Usado para pré-selecionar
         * o filtro de mês e esconder meses passados (ex.: abrir em
         * setembro em vez de agosto).
         *
         * @return string
         */
        private function get_current_month_key()
        {
            $tz = new DateTimeZone(self::TIMEZONE);
            $now = new DateTimeImmutable('now', $tz);

            return $now->format('Y-m');
        }

        private function get_events($use_cache = true)
        {
            if ($use_cache) {
                $cached = get_transient(self::CACHE_KEY);
                if ($cached !== false && is_array($cached)) {
                    return $cached;
                }
            }

            $events = $this->fetch_events_from_api();

            if ($use_cache) {
                set_transient(self::CACHE_KEY, $events, self::CACHE_TTL);
            }

            return $events;
        }

        private function fetch_events_from_api()
        {
            $response = wp_remote_get(self::API_URL, array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . self::API_TOKEN,
                    'Accept'        => 'application/json',
                ),
                'timeout' => 20,
            ));

            if (is_wp_error($response)) {
                return array();
            }

            $status_code = wp_remote_retrieve_response_code($response);
            if ((int) $status_code !== 200) {
                return array();
            }

            $body = wp_remote_retrieve_body($response);
            if (empty($body)) {
                return array();
            }

            $decoded = json_decode($body, true);
            if (!is_array($decoded) || empty($decoded['presentation']) || !is_array($decoded['presentation'])) {
                return array();
            }

            $events = array();

            foreach ($decoded['presentation'] as $raw_event) {
                $normalized = $this->normalize_event($raw_event);

                if (!empty($normalized)) {
                    $events[] = $normalized;
                }
            }

            usort($events, function ($a, $b) {
                return $a['timestamp'] <=> $b['timestamp'];
            });

            return $events;
        }

        private function normalize_event($raw_event)
        {
            if (!is_array($raw_event)) {
                return array();
            }

            if ($this->is_hidden_event($raw_event)) {
                return array();
            }

            $title = isset($raw_event['title']) ? trim(wp_strip_all_tags($raw_event['title'])) : '';
            $uuid  = isset($raw_event['uuid']) ? trim((string) $raw_event['uuid']) : '';
            $banner = isset($raw_event['banner']) ? trim((string) $raw_event['banner']) : '';

            $event_datetime = $this->parse_datetime(isset($raw_event['datetime']) ? $raw_event['datetime'] : '');
            if (!$event_datetime) {
                return array();
            }

            $closetime = $this->parse_datetime(isset($raw_event['closetime']) ? $raw_event['closetime'] : '');
            $hall_opening = $this->parse_datetime(isset($raw_event['hall_opening']) ? $raw_event['hall_opening'] : '');

            $slug = $this->slugify($title);

            $timestamp = (int) $event_datetime->getTimestamp();
            $month_key = $event_datetime->format('Y-m');
            $weekday_index = (int) $event_datetime->format('w');
            $weekday_label = $this->get_weekday_label($weekday_index);

            $buy_url = '';
            if (!empty($slug) && !empty($uuid)) {
                $buy_url = sprintf(
                    'https://standapp.com.br/evento/%s/%s',
                    rawurlencode($slug),
                    rawurlencode($uuid)
                );
            }

            $banner_url = '';
            if (!empty($banner)) {
                $banner_url = 'https://cdn.standapp.com.br/presentation/' . ltrim($banner, '/');
            }

            return array(
                'title'               => $title,
                'uuid'                => $uuid,
                'slug'                => $slug,
                'banner'              => $banner,
                'banner_url'          => $banner_url,
                'datetime_raw'        => isset($raw_event['datetime']) ? $raw_event['datetime'] : '',
                'datetime_obj'        => $event_datetime,
                'datetime_iso'        => $event_datetime->format('c'),
                'timestamp'           => $timestamp,
                'date_label'          => $this->format_event_date($event_datetime),
                'time_label'          => $this->format_event_time($event_datetime),
                'month_key'           => $month_key,
                'month_label'         => $this->format_month_label($event_datetime),
                'weekday_index'       => $weekday_index,
                'weekday_label'       => $weekday_label,
                'hall_opening_obj'    => $hall_opening,
                'hall_opening_label'  => $hall_opening ? $hall_opening->format('H:i') : '',
                'closetime_obj'       => $closetime,
                'closetime_label'     => $closetime ? $closetime->format('d/m/Y H:i') : '',
                'min_age'             => isset($raw_event['min_age']) ? trim((string) $raw_event['min_age']) : '',
                'buy_url'             => $buy_url,
                'badges'              => $this->get_event_badges($event_datetime),
                'search_blob'         => $this->build_search_blob($title, $weekday_label, $event_datetime),
            );
        }

        private function is_hidden_event($event)
        {
            if (!isset($event['hidden'])) {
                return false;
            }

            $hidden = $event['hidden'];

            if ($hidden === true || $hidden === 1 || $hidden === '1' || $hidden === 'true') {
                return true;
            }

            return false;
        }

        private function parse_datetime($value)
        {
            if (empty($value) || !is_string($value)) {
                return null;
            }

            $value = trim($value);
            $tz = new DateTimeZone(self::TIMEZONE);

            $formats = array(
                DateTimeInterface::ATOM,
                'Y-m-d H:i:s',
                'Y-m-d H:i',
                'Y-m-d\TH:i:s',
                'Y-m-d\TH:i:sP',
                'Y-m-d\TH:i:s.uP',
                'd/m/Y H:i:s',
                'd/m/Y H:i',
                'H:i',
            );

            foreach ($formats as $format) {
                $dt = DateTimeImmutable::createFromFormat($format, $value, $tz);
                if ($dt instanceof DateTimeImmutable) {
                    return $dt->setTimezone($tz);
                }
            }

            try {
                $dt = new DateTimeImmutable($value, $tz);
                return $dt->setTimezone($tz);
            } catch (Exception $e) {
                return null;
            }
        }

        private function format_event_date(DateTimeImmutable $dt)
        {
            $months = array(
                '01' => 'jan',
                '02' => 'fev',
                '03' => 'mar',
                '04' => 'abr',
                '05' => 'mai',
                '06' => 'jun',
                '07' => 'jul',
                '08' => 'ago',
                '09' => 'set',
                '10' => 'out',
                '11' => 'nov',
                '12' => 'dez',
            );

            $day = $dt->format('d');
            $month = $months[$dt->format('m')];

            return $day . ' ' . $month;
        }

        private function format_event_time(DateTimeImmutable $dt)
        {
            return $dt->format('H:i');
        }

        private function format_month_label(DateTimeImmutable $dt)
        {
            $months = array(
                '01' => 'Janeiro',
                '02' => 'Fevereiro',
                '03' => 'Março',
                '04' => 'Abril',
                '05' => 'Maio',
                '06' => 'Junho',
                '07' => 'Julho',
                '08' => 'Agosto',
                '09' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro',
            );

            return $months[$dt->format('m')] . ' ' . $dt->format('Y');
        }

        private function get_weekday_label($weekday_index)
        {
            $weekdays = array(
                0 => 'Domingo',
                1 => 'Segunda',
                2 => 'Terça',
                3 => 'Quarta',
                4 => 'Quinta',
                5 => 'Sexta',
                6 => 'Sábado',
            );

            return isset($weekdays[$weekday_index]) ? $weekdays[$weekday_index] : '';
        }

        private function get_event_badges(DateTimeImmutable $event_dt)
        {
            $badges = array();
            $tz = new DateTimeZone(self::TIMEZONE);
            $now = new DateTimeImmutable('now', $tz);

            $today = $now->format('Y-m-d');
            $tomorrow = $now->modify('+1 day')->format('Y-m-d');
            $event_day = $event_dt->format('Y-m-d');

            if ($event_day === $today) {
                $badges[] = 'HOJE';
            } elseif ($event_day === $tomorrow) {
                $badges[] = 'AMANHÃ';
            }

            return $badges;
        }

        private function build_search_blob($title, $weekday_label, DateTimeImmutable $dt)
        {
            $parts = array(
                $title,
                $weekday_label,
                $this->format_month_label($dt),
                $dt->format('d/m/Y'),
                $dt->format('H:i'),
            );

            return strtolower(remove_accents(implode(' ', $parts)));
        }

        private function slugify($text)
        {
            $text = sanitize_title($text);
            return $text;
        }

        private function render_filters($events, $atts)
        {
            $months = array();
            $weekdays = array(
                '0' => 'Domingo',
                '1' => 'Segunda',
                '2' => 'Terça',
                '3' => 'Quarta',
                '4' => 'Quinta',
                '5' => 'Sexta',
                '6' => 'Sábado',
            );

            foreach ($events as $event) {
                $months[$event['month_key']] = $event['month_label'];
            }

            ksort($months);

            // Esconde meses passados: compara Y-m como string (ex.: 2026-08 < 2026-09).
            $current_month = $this->get_current_month_key();
            foreach (array_keys($months) as $month_key) {
                if (strcmp((string) $month_key, $current_month) < 0) {
                    unset($months[$month_key]);
                }
            }

            // Pré-seleciona o mês atual quando houver eventos nele;
            // senão, cai para "Todos" (não força um mês passado).
            $default_month = isset($months[$current_month]) ? $current_month : '';

            ob_start();

            echo '<div class="ccc-standapp-filters" data-ccc-filters>';

            if ($atts['mostrar_busca'] === 'yes') {
                echo '<div class="ccc-standapp-filter-item ccc-standapp-filter-item--search">';
                echo '<label class="ccc-standapp-label" for="ccc-standapp-search">Buscar evento</label>';
                echo '<input type="text" id="ccc-standapp-search" class="ccc-standapp-input" placeholder="Ex: Ary Toledo" data-ccc-filter-search>';
                echo '</div>';
            }

            echo '<div class="ccc-standapp-filter-item">';
            echo '<label class="ccc-standapp-label" for="ccc-standapp-month">Mês</label>';
            echo '<select id="ccc-standapp-month" class="ccc-standapp-select" data-ccc-filter-month data-ccc-default-month="' . esc_attr($default_month) . '">';
            echo '<option value="">Todos</option>';

            foreach ($months as $value => $label) {
                $selected = ((string) $value === (string) $default_month) ? ' selected' : '';
                echo '<option value="' . esc_attr($value) . '"' . $selected . '>' . esc_html($label) . '</option>';
            }

            echo '</select>';
            echo '</div>';

            echo '<div class="ccc-standapp-filter-item">';
            echo '<label class="ccc-standapp-label" for="ccc-standapp-weekday">Dia da semana</label>';
            echo '<select id="ccc-standapp-weekday" class="ccc-standapp-select" data-ccc-filter-weekday>';
            echo '<option value="">Todos</option>';

            foreach ($weekdays as $value => $label) {
                echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
            }

            echo '</select>';
            echo '</div>';

            echo '<div class="ccc-standapp-filter-item ccc-standapp-filter-item--button">';
            echo '<button type="button" class="ccc-standapp-reset" data-ccc-filter-reset>Limpar filtros</button>';
            echo '</div>';

            echo '</div>';

            return ob_get_clean();
        }

        private function render_event_card($event, $atts)
        {
            $title = !empty($event['title']) ? $event['title'] : 'Evento';
            $image_alt = $title;

            $search_blob = esc_attr($event['search_blob']);
            $month_key = esc_attr($event['month_key']);
            $weekday = esc_attr((string) $event['weekday_index']);
            $timestamp = isset($event['timestamp']) ? esc_attr((string) $event['timestamp']) : '';

            ob_start();

            echo '<article class="ccc-standapp-card"';
            echo ' data-ccc-card';
            echo ' data-search="' . $search_blob . '"';
            echo ' data-month="' . $month_key . '"';
            echo ' data-weekday="' . $weekday . '"';
            echo ' data-timestamp="' . $timestamp . '"';
            echo '>';

            if (!empty($event['banner_url'])) {
                echo '<div class="ccc-standapp-card-media">';
                echo '<img class="ccc-standapp-card-image" src="' . esc_url($event['banner_url']) . '" alt="' . esc_attr($image_alt) . '" loading="lazy">';
                
                if ($atts['mostrar_badges'] === 'yes') {
                    echo '<div class="ccc-standapp-badges" data-ccc-badges>';
                    if (!empty($event['badges'])) {
                        foreach ($event['badges'] as $badge) {
                            echo '<span class="ccc-standapp-badge">' . esc_html($badge) . '</span>';
                        }
                    }
                    echo '</div>';
                }

                echo '</div>';
            }

            echo '<div class="ccc-standapp-card-content">';

            echo '<div class="ccc-standapp-card-date-row">';
            echo '<span class="ccc-standapp-date">' . esc_html($event['date_label']) . '</span>';
            echo '<span class="ccc-standapp-time">' . esc_html($event['time_label']) . '</span>';
            echo '</div>';

            echo '<h3 class="ccc-standapp-card-title">' . esc_html($title) . '</h3>';

            echo '<div class="ccc-standapp-meta">';

            echo '<div class="ccc-standapp-meta-item">';
            echo '<span class="ccc-standapp-meta-label">Dia</span>';
            echo '<span class="ccc-standapp-meta-value">' . esc_html($event['weekday_label']) . '</span>';
            echo '</div>';

            if (!empty($event['hall_opening_label'])) {
                echo '<div class="ccc-standapp-meta-item">';
                echo '<span class="ccc-standapp-meta-label">Abertura da casa</span>';
                echo '<span class="ccc-standapp-meta-value">' . esc_html($event['hall_opening_label']) . '</span>';
                echo '</div>';
            }

            if ($event['min_age'] !== '') {
                echo '<div class="ccc-standapp-meta-item">';
                echo '<span class="ccc-standapp-meta-label">Classificação</span>';
                echo '<span class="ccc-standapp-meta-value">' . esc_html($event['min_age']) . ' anos</span>';
                echo '</div>';
            }

            echo '</div>';

            if (!empty($event['buy_url'])) {
                echo '<div class="ccc-standapp-actions">';
                echo '<a class="ccc-standapp-button" href="' . esc_url($event['buy_url']) . '" target="_blank" rel="noopener noreferrer">';
                echo 'Comprar ingresso';
                echo '</a>';
                echo '</div>';
            }

            echo '</div>';
            echo '</article>';

            return ob_get_clean();
        }

        private function render_empty_state()
        {
            ob_start();

            echo '<div class="ccc-standapp-empty">';
            echo '<h3 class="ccc-standapp-empty-title">Nenhum evento disponível no momento</h3>';
            echo '<p class="ccc-standapp-empty-text">A programação será atualizada em breve. Volte daqui a pouco para conferir os próximos shows.</p>';
            echo '</div>';

            echo '<div class="ccc-standapp-grid" aria-hidden="true">';
            for ($i = 0; $i < 3; $i++) {
                echo '<div class="ccc-standapp-card ccc-standapp-skeleton-card">';
                echo '<div class="ccc-standapp-skeleton ccc-standapp-skeleton--media"></div>';
                echo '<div class="ccc-standapp-card-content">';
                echo '<div class="ccc-standapp-skeleton ccc-standapp-skeleton--line" style="width:40%"></div>';
                echo '<div class="ccc-standapp-skeleton ccc-standapp-skeleton--line" style="width:70%"></div>';
                echo '<div class="ccc-standapp-skeleton ccc-standapp-skeleton--line" style="width:55%"></div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';

            return ob_get_clean();
        }

        private function get_inline_css()
        {
            return <<<CSS
.ccc-standapp-wrap{
    width:100%;
    max-width:100%;
    margin:0 auto;
}

.ccc-standapp-header{
    margin-bottom:24px;
}

.ccc-standapp-title{
    margin:0;
    font-size:clamp(32px,4.8vw,52px);
    line-height:1.1;
    font-weight:800;
    font-family:"Bitter","Merriweather",Georgia,"Times New Roman",serif;
    color:#f5f7fa;
}

.ccc-standapp-filters{
    display:grid;
    grid-template-columns:1.4fr 1fr 1fr auto;
    gap:14px;
    align-items:end;
    margin:0 0 28px;
}

.ccc-standapp-filter-item{
    display:flex;
    flex-direction:column;
}

.ccc-standapp-filter-item--button{
    justify-content:flex-end;
}

.ccc-standapp-label{
    display:block;
    margin:0 0 8px;
    font-size:13px;
    font-weight:700;
    line-height:1.2;
    opacity:.85;
    color:#f5f7fa;
}

.ccc-standapp-input,
.ccc-standapp-select{
    width:100%;
    min-height:46px;
    padding:12px 14px;
    border:1px solid rgba(255,255,255,.12);
    border-radius:12px;
    background:#111111;
    color:#ffffff !important;
    font-size:15px;
    box-shadow:none;
    outline:none;
}

.ccc-standapp-input:focus,
.ccc-standapp-select:focus{
    border-color:rgba(255,255,255,.28);
}

.ccc-standapp-input::placeholder{
    color:#a0a7b4;
    opacity:1;
}

.ccc-standapp-select option{
    color:#ffffff;
    background:#111111;
}

.ccc-standapp-reset{
    min-height:46px;
    padding:12px 18px;
    border:0;
    border-radius:12px;
    cursor:pointer;
    font-size:14px;
    font-weight:700;
    background:#2b2b2b;
    color:#ffffff;
    transition:transform .18s ease, opacity .18s ease;
}

.ccc-standapp-reset:hover{
    transform:translateY(-1px);
    opacity:.92;
}

.ccc-standapp-grid{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:24px;
}

.ccc-standapp-card{
    display:flex;
    flex-direction:column;
    overflow:hidden;
    border-radius:20px;
    background:#111111;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.05), 0 12px 32px rgba(0,0,0,.28);
    height:100%;
    border:0;
    transition:transform .25s ease, box-shadow .25s ease;
    color:#ffffff;
}

.ccc-standapp-card:hover{
    transform:translateY(-4px);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.07), 0 16px 40px rgba(213,0,28,.16);
}

.ccc-standapp-card-media{
    position:relative;
    overflow:hidden;
    aspect-ratio:16/9;
    background:#111111;
}

.ccc-standapp-card-image{
    width:100%;
    height:100%;
    object-fit:cover;
    display:block;
}

.ccc-standapp-badges{
    position:absolute;
    top:14px;
    left:14px;
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

.ccc-standapp-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:30px;
    padding:6px 10px;
    border-radius:999px;
    background:#e11d48;
    color:#ffffff !important;
    font-size:12px;
    font-weight:800;
    letter-spacing:.04em;
    text-transform:uppercase;
    box-shadow:0 6px 16px rgba(0,0,0,.18);
}

.ccc-standapp-card-content{
    display:flex;
    flex-direction:column;
    gap:18px;
    padding:18px;
    flex:1;
    background:#0d0d0d;
    color:#ffffff;
}

.ccc-standapp-card-date-row{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.ccc-standapp-date{
    display:inline-flex;
    align-items:baseline;
    gap:6px;
    color:#ffffff !important;
    font-family:"Bitter","Merriweather",Georgia,"Times New Roman",serif;
    font-size:20px;
    font-weight:800;
    line-height:1;
    letter-spacing:-.01em;
    text-transform:uppercase;
}

.ccc-standapp-time{
    font-size:14px;
    font-weight:700;
    color:rgba(255,255,255,.88) !important;
}

.ccc-standapp-card-title{
    margin:0;
    font-size:clamp(24px,2.4vw,30px);
    line-height:1.2;
    font-weight:800;
    font-family:"Bitter","Merriweather",Georgia,"Times New Roman",serif;
    word-break:break-word;
    color:#ffffff !important;
}

.ccc-standapp-meta{
    display:grid;
    gap:10px;
}

.ccc-standapp-meta-item{
    display:flex;
    flex-direction:column;
    gap:2px;
}

.ccc-standapp-meta-label{
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.04em;
    font-weight:700;
    color:rgba(255,255,255,.62) !important;
}

.ccc-standapp-meta-value{
    font-size:15px;
    font-weight:600;
    line-height:1.35;
    color:#ffffff !important;
}

.ccc-standapp-actions{
    margin-top:auto;
    padding-top:6px;
}

.ccc-standapp-button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:100%;
    min-height:50px;
    padding:14px 18px;
    border-radius:14px;
    text-decoration:none !important;
    font-size:15px;
    font-weight:800;
    line-height:1;
    background:#ffffff;
    color:#000000 !important;
    transition:transform .18s ease, opacity .18s ease;
}

.ccc-standapp-button:hover{
    transform:translateY(-1px);
    opacity:.92;
    color:#000000 !important;
}

.ccc-standapp-empty,
.ccc-standapp-no-results{
    padding:28px;
    border-radius:20px;
    background:#111111;
    border:1px solid rgba(255,255,255,.06);
    color:#ffffff;
}

.ccc-standapp-empty-title{
    margin:0 0 10px;
    font-size:24px;
    font-weight:800;
    color:#ffffff !important;
}

.ccc-standapp-empty-text{
    margin:0;
    font-size:15px;
    line-height:1.6;
    opacity:.84;
    color:#ffffff !important;
}

.ccc-standapp-no-results{
    margin-top:20px;
    color:#ffffff !important;
}

.ccc-standapp-card[hidden],
.ccc-standapp-no-results[hidden]{
    display:none !important;
}

.ccc-standapp-skeleton-card{
    pointer-events:none;
    opacity:.5;
}

.ccc-standapp-skeleton{
    background:linear-gradient(90deg,rgba(255,255,255,.04) 25%,rgba(255,255,255,.08) 50%,rgba(255,255,255,.04) 75%);
    background-size:200% 100%;
    animation:ccc-shimmer 1.5s infinite;
    border-radius:10px;
}

.ccc-standapp-skeleton--media{
    aspect-ratio:16/9;
    border-radius:20px 20px 0 0;
}

.ccc-standapp-skeleton--line{
    height:14px;
    margin-bottom:10px;
}

@keyframes ccc-shimmer{
    to{background-position:-200% 0;}
}

/* Blindagem contra herança do tema/Elementor */
.ccc-standapp-wrap,
.ccc-standapp-wrap .ccc-standapp-card,
.ccc-standapp-wrap .ccc-standapp-card-content,
.ccc-standapp-wrap .ccc-standapp-card-content p,
.ccc-standapp-wrap .ccc-standapp-card-content span,
.ccc-standapp-wrap .ccc-standapp-card-content div,
.ccc-standapp-wrap .ccc-standapp-card-content li,
.ccc-standapp-wrap .ccc-standapp-card-content strong{
    color:#ffffff;
}

.ccc-standapp-wrap .ccc-standapp-card-title,
.ccc-standapp-wrap .ccc-standapp-meta-value,
.ccc-standapp-wrap .ccc-standapp-time,
.ccc-standapp-wrap .ccc-standapp-date,
.ccc-standapp-wrap .ccc-standapp-badge{
    color:#ffffff !important;
}

.ccc-standapp-wrap .ccc-standapp-title,
.ccc-standapp-wrap .ccc-standapp-label{
    color:#f5f7fa !important;
}

@media (max-width: 1024px){
    .ccc-standapp-grid{
        grid-template-columns:repeat(2,minmax(0,1fr));
    }

    .ccc-standapp-filters{
        grid-template-columns:1fr 1fr;
    }
}

@media (max-width: 767px){
    .ccc-standapp-grid{
        grid-template-columns:1fr;
        gap:18px;
    }

    .ccc-standapp-filters{
        grid-template-columns:1fr;
        gap:12px;
    }

    .ccc-standapp-card-title{
        font-size:clamp(22px,6vw,26px);
    }
}

/* Banner "ingresso de hoje" */
.ccc-standapp-today{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
    margin:0 0 24px;
    padding:14px 18px;
    border-radius:16px;
    background:linear-gradient(135deg, #1c0a0f 0%, #0d0d0d 100%);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.06), 0 10px 28px rgba(0,0,0,.25);
    color:#ffffff;
}

.ccc-standapp-today__info{
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    min-width:0;
}

.ccc-standapp-today__label{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:28px;
    padding:5px 11px;
    border-radius:999px;
    background:#e11d48;
    color:#ffffff !important;
    font-family:"Oswald","DM Sans",sans-serif;
    font-size:12px;
    font-weight:600;
    letter-spacing:.08em;
    text-transform:uppercase;
}

.ccc-standapp-today__title{
    font-family:"Oswald","DM Sans",sans-serif;
    font-size:clamp(18px,2.3vw,24px);
    font-weight:600;
    line-height:1.2;
    color:#ffffff !important;
    word-break:break-word;
}

.ccc-standapp-today__time{
    font-family:"DM Sans","Montserrat","Segoe UI",sans-serif;
    font-size:14px;
    font-weight:600;
    color:rgba(255,255,255,.82) !important;
    white-space:nowrap;
}

.ccc-standapp-today__cta{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:44px;
    padding:0 18px;
    border-radius:12px;
    background:linear-gradient(135deg, #e8112b 0%, #7a0b1e 100%);
    color:#ffffff !important;
    text-decoration:none !important;
    font-family:"DM Sans","Montserrat","Segoe UI",sans-serif;
    font-size:14px;
    font-weight:700;
    white-space:nowrap;
    transition:transform .18s ease, filter .18s ease;
}

.ccc-standapp-today__cta:hover{
    transform:translateY(-1px);
    filter:brightness(1.1);
}

@media (max-width: 767px){
    .ccc-standapp-today{
        align-items:stretch;
        flex-direction:column;
    }

    .ccc-standapp-today__cta{
        width:100%;
    }
}
CSS;
        }

        private function get_inline_js()
        {
            return <<<JS
document.addEventListener('DOMContentLoaded', function () {
    console.log('%cCuritiba Comedy Club %c— agenda por Leonardo Bora, exímio programador', 'font-weight:bold', 'color:#e11d48');
    var roots = document.querySelectorAll('[data-ccc-standapp-root]');

    roots.forEach(function (root) {
        var searchInput = root.querySelector('[data-ccc-filter-search]');
        var monthSelect = root.querySelector('[data-ccc-filter-month]');
        var weekdaySelect = root.querySelector('[data-ccc-filter-weekday]');
        var resetButton = root.querySelector('[data-ccc-filter-reset]');
        var cards = root.querySelectorAll('[data-ccc-card]');
        var noResults = root.querySelector('[data-ccc-no-results]');
        var limitAttr = parseInt(root.getAttribute('data-ccc-limit') || '0', 10);
        var eventLimit = isNaN(limitAttr) ? 0 : limitAttr;
        var currentMonth = root.getAttribute('data-ccc-current-month') || '';
        var defaultMonth = (monthSelect && monthSelect.getAttribute('data-ccc-default-month')) || currentMonth || '';

        // Abre no mês atual quando houver eventos nele (ex.: setembro em vez de agosto).
        if (monthSelect && defaultMonth) {
            var hasOption = Array.prototype.some.call(monthSelect.options, function (opt) {
                return opt.value === defaultMonth;
            });
            if (hasOption && !monthSelect.value) {
                monthSelect.value = defaultMonth;
            }
        }

        function normalizeText(text) {
            return (text || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\\u0300-\\u036f]/g, '');
        }

        // Timestamp do início de hoje (relógio do visitante). Serve para
        // esconder eventos passados mesmo quando a página vem de page cache.
        function startOfTodayTs() {
            var now = new Date();
            return new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000;
        }

        function isPastEvent(card) {
            var ts = parseInt(card.getAttribute('data-timestamp') || '', 10);
            if (isNaN(ts)) {
                return false;
            }
            return ts < startOfTodayTs();
        }

        function dayKeyOfTs(ts) {
            var d = new Date(ts * 1000);
            return new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
        }

        // Recalcula o badge HOJE/AMANHÃ no cliente, corrigindo badges
        // desatualizados de um HTML em cache antigo.
        function recalcBadges(card) {
            var container = card.querySelector('[data-ccc-badges]');
            if (!container) {
                return;
            }

            var ts = parseInt(card.getAttribute('data-timestamp') || '', 10);
            if (isNaN(ts)) {
                container.style.display = 'none';
                return;
            }

            var now = new Date();
            var today = dayKeyOfTs(now.getTime() / 1000);
            var tomorrow = dayKeyOfTs((now.getTime() / 1000) + 86400);
            var eventDay = dayKeyOfTs(ts);
            var badge = '';

            if (eventDay === today) {
                badge = 'HOJE';
            } else if (eventDay === tomorrow) {
                badge = 'AMANHÃ';
            }

            if (badge) {
                container.style.display = '';
                container.innerHTML = '<span class="ccc-standapp-badge">' + badge + '</span>';
            } else {
                container.style.display = 'none';
            }
        }

        function applyFilters() {
            var searchValue = normalizeText(searchInput ? searchInput.value : '');
            var monthValue = monthSelect ? monthSelect.value : '';
            var weekdayValue = weekdaySelect ? weekdaySelect.value : '';
            var visibleCount = 0;

            cards.forEach(function (card) {
                var cardSearch = normalizeText(card.getAttribute('data-search') || '');
                var cardMonth = card.getAttribute('data-month') || '';
                var cardWeekday = card.getAttribute('data-weekday') || '';

                var matchSearch = !searchValue || cardSearch.indexOf(searchValue) !== -1;
                var matchMonth = !monthValue || cardMonth === monthValue;
                var matchWeekday = !weekdayValue || cardWeekday === weekdayValue;

                var show = matchSearch && matchMonth && matchWeekday;

                // Blindagem anti-cache: nunca exibe evento de dias passados.
                if (show && isPastEvent(card)) {
                    show = false;
                }

                if (show && eventLimit > 0 && visibleCount >= eventLimit) {
                    show = false;
                }

                card.hidden = !show;

                if (show) {
                    visibleCount++;
                }
            });

            if (noResults) {
                noResults.hidden = visibleCount > 0;
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        if (monthSelect) {
            monthSelect.addEventListener('change', applyFilters);
        }

        if (weekdaySelect) {
            weekdaySelect.addEventListener('change', applyFilters);
        }

        if (resetButton) {
            resetButton.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                if (monthSelect) monthSelect.value = defaultMonth || '';
                if (weekdaySelect) weekdaySelect.value = '';
                applyFilters();
            });
        }

        // Corrige badges desatualizados antes do primeiro render.
        cards.forEach(recalcBadges);

        applyFilters();
    });

    // Banner "ingresso de hoje": esconde se a data não for mais hoje
    // (blindagem contra page cache servindo o banner de um dia anterior).
    document.querySelectorAll('[data-ccc-today]').forEach(function (el) {
        var ts = parseInt(el.getAttribute('data-timestamp') || '', 10);
        if (isNaN(ts)) {
            return;
        }
        var now = new Date();
        var startToday = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime() / 1000;
        var startTomorrow = startToday + 86400;
        if (ts < startToday || ts >= startTomorrow) {
            el.style.display = 'none';
        }
    });
});
JS;
        }
    }

    new CCC_Eventos_Standapp();
}