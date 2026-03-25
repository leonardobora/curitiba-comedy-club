<?php
/**
 * Plugin Name: CCC Eventos Standapp
 * Plugin URI: https://curitibacomedyclub.com.br/
 * Description: Lista eventos do Curitiba Comedy Club via API Standapp com shortcode [eventos_standapp].
 * Version: 3.1.0
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
        const VERSION = '3.1.0';
        const SHORTCODE = 'eventos_standapp';
        const SHORTCODE_HOME = 'eventos_standapp_home';
        const API_URL = 'https://api.standapp.com.br/live/presentation/list-by-presentation-hall/2';
        const CACHE_KEY = 'ccc_standapp_eventos_v31';
        const CACHE_TTL = 300; // 5 minutos
        const TIMEZONE = 'America/Sao_Paulo';

        /**
         * ATENÇÃO:
         * Cole aqui o MESMO token usado hoje no seu plugin v3.0.
         * Não altere a estrutura do Bearer, só mantenha o token atual.
         */
        const API_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL2hhc3VyYS5pby9qd3QvY2xhaW1zIjp7IngtaGFzdXJhLWFsbG93ZWQtcm9sZXMiOlsidmlzaXRvciIsImxvZ2dlZCJdLCJ4LWhhc3VyYS1kZWZhdWx0LXJvbGUiOiJ2aXNpdG9yIiwieC1oYXN1cmEtdXNlci1pZCI6IjAifX0.-yI-yUFqsBe6puXTq9znDnLbAZSzlzl6TF_YfPyJtPc';

        public function __construct()
        {
            add_shortcode(self::SHORTCODE, array($this, 'render_shortcode'));
            add_shortcode(self::SHORTCODE_HOME, array($this, 'render_home_shortcode'));
            add_action('wp_enqueue_scripts', array($this, 'register_assets'));
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
                'somente_proximos' => 'no',
                'limit'           => '0',
                'dias_proximos'   => '0',
            ), $atts, self::SHORTCODE);

            $use_cache = ($atts['cache'] === 'yes');
            $events = $this->get_events($use_cache);
            $events = $this->filter_events($events, $atts);

            ob_start();

            echo '<section class="ccc-standapp-wrap" data-ccc-standapp-root>';
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
            echo '<select id="ccc-standapp-month" class="ccc-standapp-select" data-ccc-filter-month>';
            echo '<option value="">Todos</option>';

            foreach ($months as $value => $label) {
                echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
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

            ob_start();

            echo '<article class="ccc-standapp-card"';
            echo ' data-ccc-card';
            echo ' data-search="' . $search_blob . '"';
            echo ' data-month="' . $month_key . '"';
            echo ' data-weekday="' . $weekday . '"';
            echo '>';

            if (!empty($event['banner_url'])) {
                echo '<div class="ccc-standapp-card-media">';
                echo '<img class="ccc-standapp-card-image" src="' . esc_url($event['banner_url']) . '" alt="' . esc_attr($image_alt) . '" loading="lazy">';
                
                if ($atts['mostrar_badges'] === 'yes' && !empty($event['badges'])) {
                    echo '<div class="ccc-standapp-badges">';
                    foreach ($event['badges'] as $badge) {
                        echo '<span class="ccc-standapp-badge">' . esc_html($badge) . '</span>';
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
    color:#1a1a1a;
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
    color:#1a1a1a;
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
    box-shadow:0 10px 30px rgba(0,0,0,.18);
    height:100%;
    border:1px solid rgba(255,255,255,.06);
    transition:transform .22s ease, box-shadow .22s ease;
    color:#ffffff;
}

.ccc-standapp-card:hover{
    transform:translateY(-4px);
    box-shadow:0 16px 40px rgba(0,0,0,.24);
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
    padding:8px 12px;
    border-radius:999px;
    background:#1c1c1c;
    color:#ffffff !important;
    font-size:13px;
    font-weight:800;
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
    color:#1a1a1a !important;
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
CSS;
        }

        private function get_inline_js()
        {
            return <<<JS
document.addEventListener('DOMContentLoaded', function () {
    var roots = document.querySelectorAll('[data-ccc-standapp-root]');

    roots.forEach(function (root) {
        var searchInput = root.querySelector('[data-ccc-filter-search]');
        var monthSelect = root.querySelector('[data-ccc-filter-month]');
        var weekdaySelect = root.querySelector('[data-ccc-filter-weekday]');
        var resetButton = root.querySelector('[data-ccc-filter-reset]');
        var cards = root.querySelectorAll('[data-ccc-card]');
        var noResults = root.querySelector('[data-ccc-no-results]');

        function normalizeText(text) {
            return (text || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\\u0300-\\u036f]/g, '');
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
                if (monthSelect) monthSelect.value = '';
                if (weekdaySelect) weekdaySelect.value = '';
                applyFilters();
            });
        }

        applyFilters();
    });
});
JS;
        }
    }

    new CCC_Eventos_Standapp();
}