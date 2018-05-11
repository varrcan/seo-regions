<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsPublic
 * @package Varrcan\SeoRegions
 */
class SeoRegionsInit
{
    use SeoRegionsLoader;

    /**
     * Plugin name
     */
    public $pluginName = 'seo-regions';

    /**
     * Plugin options
     */
    private $options;

    /**
     * SeoRegions constructor.
     */
    public function __construct()
    {
        //$this->setLocale();
        $this->loadPublicHooks();
        if (is_admin()) {
            $this->loadAdminHooks();
        }

        // Run Actions and Filter
        $this->run();
    }

    /**
     * Регистрация хуков для фронта
     */
    private function loadPublicHooks()
    {
        $pluginPublic    = new SeoRegionsPublic($this->pluginName, SEO_REGIONS_VERSION);
        $pluginShortcode = new SeoRegionsShortcode();
        $pluginWidget    = new SeoRegionsWidget();

        $this->addAction('get_header', $pluginPublic, 'bufferStart');
        $this->addAction('wp_footer', $pluginPublic, 'bufferEnd');
        $this->addAction('wp_head', $pluginPublic, 'verificationCodes');

        $this->addAction('wp_enqueue_scripts', $pluginPublic, 'enqueueShortcodeStyle');
        $this->addAction('wp_enqueue_scripts', $pluginPublic, 'enqueueShortcodeScript');

        $this->addShortcode('seo-domains', $pluginShortcode, 'outputDomainsMenu');
        $this->addAction('widgets_init', $pluginWidget, 'registerWidget');
    }

    /**
     * Регистрация хуков в админ-панели
     */
    private function loadAdminHooks()
    {
        $pluginAdmin = new SeoRegionsAdmin($this->pluginName, SEO_REGIONS_VERSION);

        $this->addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueStyles');
        $this->addAction('admin_enqueue_scripts', $pluginAdmin, 'enqueueScripts');
        $this->addAction('init', $pluginAdmin, 'mainPage');
        $this->addAction('admin_menu', $pluginAdmin, 'settingsPage');
        $this->addAction('add_meta_boxes', $pluginAdmin, 'metaboxDomainPage');
        $this->addAction('save_post', $pluginAdmin, 'savePostDomain');

        $this->addAction('admin_init', $pluginAdmin, 'domainSettingsPage');

        $this->addFilter('enter_title_here', $pluginAdmin, 'changePlaceholders');
        $this->addFilter('plugin_action_links_' . SEO_REGIONS_BASE, $pluginAdmin, 'setMeta', 10, 2);
    }

    //TODO: Добавить перевод
    private function setLocale()
    {
        $plugin_i18n = new SeoRegionsI18n();
        $this->addAction('plugins_loaded', $plugin_i18n, 'loadPluginTextdomain');
    }
}
