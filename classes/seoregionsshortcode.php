<?php

namespace Varrcan\SeoRegions;

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

    /**
     * SeoRegionsPublic constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Вывод меню городов через шорткод
     */
    public function outputDomainsMenu()
    {
        if (SeoRegionsPublic::getDomainName()) {
            $arResult = SeoRegionsPublic::getAllDomainsData();

            include SEO_REGIONS_DIR . 'public/output/select.php';
        }
    }
}
