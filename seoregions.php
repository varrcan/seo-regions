<?php

/**
 * Bootstrap file
 *
 * @link              https://varrcan.me
 * @since             1.2.2
 * @package           SeoRegions
 * @wordpress-plugin
 *
 * Plugin Name:       WordPress SeoRegions
 * Plugin URI:        https://github.com/varrcan/seo-regions
 * Description:       Продвижение проекта в региональных выдачах поисковых систем на основе поддоменов сайта. Основные возможности плагина: Замена мета тегов, текстов, тайтлов, заголовков, на страницах разных подоменов; возможность подтверждения каждого поддомена в сервисах вебмастеров через мета тег; возможность использовать отличный от корневого домен, вместо поддоменов.
 * Version:           1.2.2
 * Author:            Sergey Voloshin
 * Author URI:        https://varrcan.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       seo-regions
 * Domain Path:       /languages
 */

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

\define('NOBLOGREDIRECT', (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
\define('SEO_REGIONS_VERSION', '1.2.2');
\define('SEO_REGIONS_FILE', __FILE__);
\define('SEO_REGIONS_DIR', plugin_dir_path(SEO_REGIONS_FILE));
\define('SEO_REGIONS_URL', plugin_dir_url(SEO_REGIONS_FILE));
\define('SEO_REGIONS_BASE', plugin_basename(SEO_REGIONS_FILE));

/**
 * Class SeoRegions
 * @package Varrcan\SeoRegions
 */
class SeoRegions
{
    /**
     * @var $instance self
     */
    private static $instance;

    /**
     * SeoRegions constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Возвращает текущий экземпляр
     * @return SeoRegions
     */
    public static function getInstance():SeoRegions
    {
        if (null === self::$instance) {
            self::$instance = new self;

            // Register hook autoload in WP
            add_action('after_setup_theme', [__CLASS__, 'loadInstance']);
        }

        return self::$instance;
    }

    /**
     * Регистрация функции автозагрузки
     */
    public static function loadInstance()
    {
        if (\function_exists('spl_autoload_register')) {
            spl_autoload_register([self::$instance, 'autoload']);
        } else {
            self::$instance->loadDependencies();
        }

        self::$instance->init();

        do_action('seoRegionsLoaded');
    }

    /**
     * Автозагрузка классов
     *
     * @param $class
     */
    private function autoload($class)
    {
        $class = ltrim($class, '\\');
        if (strpos($class, __NAMESPACE__) !== 0) {
            return;
        }

        $class = str_replace(__NAMESPACE__, '', $class);
        $path  = SEO_REGIONS_DIR . 'classes' . strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';

        require_once $path;
    }

    /**
     * Инициализация плагина
     */
    public function init()
    {
        // Set WP Actions and Filter
        new SeoRegionsInit();
    }

    /**
     * Подключение классов, если PHP функции spl_autoload_register не существует
     */
    private function loadDependencies()
    {
        require_once SEO_REGIONS_DIR . 'classes/seoregionsinit.php';
        require_once SEO_REGIONS_DIR . 'classes/seoregionsloader.php';
        require_once SEO_REGIONS_DIR . 'classes/seoregionsi18n.php';
        require_once SEO_REGIONS_DIR . 'classes/seoregionspublic.php';
        require_once SEO_REGIONS_DIR . 'classes/seoregionsshortcode.php';
        require_once SEO_REGIONS_DIR . 'classes/seoregionswidget.php';

        if (is_admin()) {
            require_once SEO_REGIONS_DIR . 'classes/seoregionsadmin.php';
            require_once SEO_REGIONS_DIR . 'classes/seoregionsview.php';
        }
    }
}

/**
 * Start seoregions
 * @return SeoRegions
 */
function seoregions()
{
    return SeoRegions::getInstance();
}

seoregions();
