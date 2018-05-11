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

    //private $options;

    /**
     * SeoRegionsPublic constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Вывод меню городов через шорткод
     * @return mixed
     */
    public function outputDomainsMenu()
    {
        if (SeoRegionsPublic::getDomainName()) {
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

        return false;
    }
}
