<?php

namespace Varrcan\SeoRegions;

use function wp_enqueue_script;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsPublic
 * @package Varrcan\SeoRegions
 */
class SeoRegionsShortcode
{
    use SeoRegionsLoader;

    private $pluginName;
    private $version;
    //private $options;

    /**
     * SeoRegionsPublic constructor.
     *
     * @param $pluginName
     * @param $version
     */
    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName;
        $this->version    = $version;
        //$this->options = get_option('seoregions_option');
    }

    /**
     * Добавление стилей для шорткодов
     */
    public function enqueueShortcodeStyle()
    {
        wp_enqueue_style($this->pluginName, SEO_REGIONS_URL . 'public/css/seo-regions.css', [], $this->version);
    }

    /**
     * Добавление скрипта для шорткодов
     */
    public function enqueueShortcodeScript()
    {
        wp_register_script($this->pluginName, SEO_REGIONS_URL . 'public/js/seo-regions-shortcode.js', ['jquery'], $this->version, true);
    }

    /**
     * Вывод меню городов через шорткод
     * @return mixed
     */
    public function outputDomainsMenu()
    {
        wp_enqueue_script($this->pluginName);

        $arResult = SeoRegionsPublic::getAllDomainsData();

        $out = '<div class="select seoregions-wrap">' . PHP_EOL;
        $out .= '<span>Ваш город:</span>' . PHP_EOL;
        $out .= '<span class="seoregions">' . PHP_EOL;
        $out .= '<span class="seoregions__active">' . SeoRegionsPublic::getDomainName() . '</span>' . PHP_EOL;
        $out .= '<span class="seoregions__hidden">' . PHP_EOL;

        foreach ($arResult as $arItem) {
            $out .=
                '<span class="seoregions__hidden-item">
                    <a href="' . $arItem['domain_url'] . $_SERVER['REQUEST_URI'] . '" 
                        class="seoregions__hidden-link seoregions-href">' . $arItem['domain_city'] . '
                    </a>
                </span>' . PHP_EOL;
        }

        $out .= '</span>' . PHP_EOL;
        $out .= '</span>' . PHP_EOL;
        $out .= '</div>' . PHP_EOL;

        return $out;
    }
}
