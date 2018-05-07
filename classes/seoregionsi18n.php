<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegions_i18n
 * @package Varrcan\SeoRegions
 */
class SeoRegionsI18n
{

    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'seo-regions',
            false,
            \dirname(plugin_basename(__FILE__), 2) . '/languages/'
        );
    }
}
