<?php

namespace Varrcan\SeoRegions;

use function explode;
use function parse_url;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsPublic
 * @package Varrcan\SeoRegions
 */
class SeoRegionsPublic
{
    use SeoRegionsLoader;

    public static $domainRules = [];
    public static $devPrefix;
    public static $httpHost;
    public static $originalHost;

    private static $wpQuery;

    private $options;

    /**
     * SeoRegionsPublic constructor.
     *
     * @param $pluginName
     * @param $version
     * @param $host
     */
    public function __construct()
    {
        $this->options = get_option('seoregions_option');

        // Домен, указанный в настройках сайта. После объявления константы WP_SITEURL значение переопределится
        self::$originalHost = parse_url(site_url(), PHP_URL_HOST);

        self::$httpHost    = $this->cleanDevDomain($_SERVER['HTTP_HOST']);
        self::$domainRules = $this->getDomainMeta(self::$httpHost);

        if (self::$domainRules) {
            // Эти константы могут сломать сайт, если WP был установлен в каталог отличный от корневого
            \define('WP_HOME', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
            \define('WP_SITEURL', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
            \define('COOKIE_DOMAIN', '.' . self::$originalHost);

            $this->domainRedirect(self::$httpHost);
            $this->setHooks();
        }

        if (!is_admin()) {
            //echo __FILE__ . ' @ ' . __LINE__ . '<pre>' . print_r(self::$httpHost, true) . '</pre>';
            //exit();
        }
    }

    /**
     * Перенаправление на основной домен
     *
     * @param $domain
     */
    private function domainRedirect($domain)
    {
        if ($this->options['redirect'] && self::getSubDomain($domain) !== self::$domainRules['domain_code']) {
            wp_redirect((is_ssl() ? 'https://' : 'http://') . self::$originalHost, 301);
            exit;
        }
    }

    /**
     * Очистка домена на dev версии
     *
     * @param $httpHost
     *
     * @return null|string|string[]
     */
    private function cleanDevDomain($httpHost)
    {
        self::$devPrefix = explode(',', $this->options['devdomain']);

        foreach (self::$devPrefix as &$prefix) {
            $prefix .= '.';
        }

        return str_replace(self::$devPrefix, '', $httpHost);
    }

    /**
     * Получить поддомен
     *
     * @param $domain
     *
     * @return mixed
     */
    public static function getSubDomain($domain)
    {
        return explode('.', $domain)[0];
    }

    /**
     * WP запрос
     *
     * @param $args
     *
     * @return \WP_Query
     */
    private static function getQuery($args):\WP_Query
    {
        return self::$wpQuery = new \WP_Query($args);
    }

    /**
     * Возвращает все данные поддоменов
     * @return array
     */
    public static function getAllDomainsData():array
    {
        $host      = '';
        $objQuery  = self::getQuery([
            'post_type'   => 'domain',
            'post_status' => 'publish',
            'orderby'     => 'title',
            'order'       => 'ASC',
            'meta_query'  => [
                [
                    'key' => '_domain_meta_fields',
                ]
            ]
        ]);

        foreach ($objQuery->posts as $key => $post) {
            $arDomains[$key]                 = get_post_meta($post->ID, '_domain_meta_fields', true);
            $arDomains[$key]['domain_id']    = $post->ID;
            $arDomains[$key]['domain_title'] = $post->post_title;

            if (!isset($arDomains[$key]['domain_not_subdomain'])) {
                $host = '.' . self::$originalHost;
            }
            $arDomains[$key]['domain_host'] = $arDomains[$key]['domain_code'] . $host;
            $arDomains[$key]['domain_url']  =
                (is_ssl() ? 'https://' : 'http://') . $arDomains[$key]['domain_code'] . $host;
        }

        // Сортировка массивов по полю domain_sort
        usort($arDomains, function ($a, $b) {
            return ($a['domain_sort'] - $b['domain_sort']);
        });

        return $arDomains;
    }

    /**
     * Переменные домена
     *
     * @param $domainName
     *
     * @return array|false
     */
    private function getDomainMeta($domainName)
    {
        $host       = '';
        $postDomain = $this->getDomainPost($domainName);

        if (!$postDomain->post_count) {
            return false;
        }
        $domainMeta                 = get_post_meta($postDomain->post->ID, '_domain_meta_fields', true);
        $domainMeta['domain_id']    = $postDomain->post->ID;
        $domainMeta['domain_title'] = $postDomain->post->post_title;

        if (!isset($domainMeta['domain_not_subdomain'])) {
            $host = '.' . self::$originalHost;
        }
        $domainMeta['domain_host'] = $domainMeta['domain_code'] . $host;
        $domainMeta['domain_url']  = (is_ssl() ? 'https://' : 'http://') . $domainMeta['domain_code'] . $host;

        return $domainMeta;
    }

    /**
     * Получить объект записи по поддомену
     *
     * @param $searchDomain
     *
     * @return \WP_Query
     */
    private function getDomainPost($searchDomain):\WP_Query
    {
        $domainPost = self::getQuery([
            'post_type'  => 'domain',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => '_domain_meta_fields',
                    'value'   => sprintf(':"%s";', self::getSubDomain($searchDomain)),
                    'compare' => 'LIKE'
                ],
                [
                    'key'     => '_domain_meta_fields',
                    'value'   => sprintf(':"%s";', $searchDomain),
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        return $domainPost;
    }

    /**
     * Верификация yandex и google
     */
    public function verificationCodes()
    {
        if (is_front_page()) {
            if ('' !== self::$domainRules['domain_yandex']) {
                echo PHP_EOL . '<meta name="yandex-verification" content="' . htmlentities(self::$domainRules['domain_yandex']) . '" />';
            }
            if ('' !== self::$domainRules['domain_google']) {
                echo PHP_EOL . '<meta name="google-site-verification" content="' . htmlentities(self::$domainRules['domain_google']) . '" />';
            }
        }
    }

    /**
     * Замена данных
     *
     * @param $buffer
     *
     * @return null|string|string[]
     */
    public function replaceTemplates($buffer)
    {
        $arReplace = [
            '{адрес}'    => self::$domainRules['domain_address'],
            '{email}'    => self::$domainRules['domain_email'],
            '{телефон}'  => self::$domainRules['domain_phone'],
            '{телефон2}' => self::$domainRules['domain_phone2'],
            '{время}'    => self::$domainRules['domain_timework'],
            '{город}'    => self::$domainRules['domain_city'],
            '{в городе}' => self::$domainRules['domain_in_city'],
        ];

        $content = str_replace(array_keys($arReplace), array_values($arReplace), $buffer);

        return $content;
    }

    public function bufferStart()
    {
        ob_start([$this, 'replaceTemplates']);
    }

    public function bufferEnd()
    {
        ob_end_flush();
    }

    private function setHooks()
    {
        $this->addFilter('template_directory_uri', $this, 'setMainDomain', 1);
        $this->addFilter('plugins_url', $this, 'setMainDomain', 1);

        $this->run();
    }

    /**
     * Изменение нативной функции, возвращающей путь к текущему шаблону или плагину.
     * Некоторые плагины или темы используют функцию get_template_directory_uri() и plugins_url()
     * для подключения своих скриптов и стилей на страницу.
     * Эта функция основана на получении абсолютного пути, поэтому отдает некорректный URL на поддомене.
     *
     * @param $dirUri
     *
     * @return mixed
     */
    public function setMainDomain($dirUri)
    {
        $arUrl   = parse_url($dirUri);
        $siteUrl = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $arUrl['path'];

        return $siteUrl;
    }

    /**
     * Имя домена
     * @return mixed
     */
    public static function getDomainName()
    {
        return self::$domainRules['domain_title'];
    }

    /**
     * ID записи
     * @return mixed
     */
    public static function getDomainId()
    {
        return self::$domainRules['domain_id'];
    }

    /**
     * Символьный код домена
     * @return mixed
     */
    public static function getDomainCode()
    {
        return self::$domainRules['domain_code'];
    }
}
