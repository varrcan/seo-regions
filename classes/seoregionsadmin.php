<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsAdmin
 * @package Varrcan\SeoRegions
 */
class SeoRegionsAdmin
{
    private $pluginName;
    private $version;
    private $pageView;

    /**
     * SeoRegionsAdmin constructor.
     *
     * @param $pluginName
     * @param $version
     */
    public function __construct($pluginName, $version)
    {
        $this->pluginName = $pluginName . '-admin';
        $this->version    = $version;
        $this->pageView   = new SeoRegionsView();
    }

    /**
     * Добавление стилей в админ-панель
     */
    public function enqueueStyles()
    {
        wp_enqueue_style($this->pluginName, SEO_REGIONS_URL . 'admin/css/seo-regions-admin.css', [], $this->version, 'all');
    }

    /**
     * Добавление скриптов в админ-панель
     */
    public function enqueueScripts()
    {
        wp_enqueue_script($this->pluginName, SEO_REGIONS_URL . 'admin/js/seo-regions-admin.js', ['jquery'], $this->version, false);
    }

    /**
     * Добавление пользовательского типа записей
     */
    public function mainPage()
    {
        register_post_type('domain', [
            'label'               => '',
            'labels'              => [
                'name'               => 'Домены',
                'singular_name'      => 'Домен',
                'add_new'            => 'Добавить запись',
                'add_new_item'       => 'Добавить новый домен',
                'edit_item'          => 'Редактировать домен',
                'new_item'           => 'Новый домен',
                'view_item'          => 'Посмотреть домен',
                'search_items'       => 'Найти домен',
                'not_found'          => 'Нет записей',
                'not_found_in_trash' => 'В корзине записей не найдено',
                'parent_item_colon'  => '',
                'menu_name'          => 'Все записи'

            ],
            'public'              => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_menu'        => 'domain',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'show_in_rest'        => false,
            'query_var'           => true,
            'rewrite'             => false,
            'capability_type'     => 'page',
            'has_archive'         => true,
            'map_meta_cap'        => true,
            'hierarchical'        => true,
            'permalink_epmask'    => 'EP_NONE',
            //'register_meta_box_cb' => [$this->pageView, 'domainPageOutput'],
            'menu_position'       => null,
            'supports'            => ['title', 'author']
        ]);
    }

    /**
     * Меню плагина
     */
    public function settingsPage()
    {
        global $_wp_last_object_menu;
        $_wp_last_object_menu++;

        add_menu_page('Seo Regions', 'Seo Regions', 'manage_options', 'domain', '', 'dashicons-admin-site', $_wp_last_object_menu);
        add_submenu_page('domain', 'Добавить новую запись', 'Добавить запись', 'manage_options', 'post-new.php?post_type=domain');
        add_submenu_page('domain', 'Настройки Seo Regions', 'Настройки', 'manage_options', 'domain-settings', [$this->pageView, 'optionsPageOutput']);
        //add_submenu_page('domain', 'Инструкция Seo Regions', 'Инструкция', 'manage_options', 'domain-help', [$this->pageView, 'helpPageOutput']);
    }

    /**
     * Пользовательские метабоксы на странице добавления домена
     */
    public function metaboxDomainPage()
    {
        add_meta_box('seo-regions-section-id', 'Переменные', [$this->pageView, 'domainPageOutput'], 'domain', 'normal', 'high');
        add_meta_box('seo-regions-side-id', 'Опции', [$this->pageView, 'domainSideOutput'], 'domain', 'side');
    }

    /**
     * Сохранение полей метабокса
     *
     * @param $postId
     *
     * @return mixed
     */
    public function savePostDomain($postId)
    {
        $arFields = [];
        $nonce    = $_POST['formDomainNonce'];

        if (null === $nonce && !wp_verify_nonce($nonce, 'formDomain')) {
            return $postId;
        }

        if (\defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $postId;
        }

        if ($_POST['post_type'] !== 'domain' || !current_user_can('edit_page', $postId)) {
            return $postId;
        }

        foreach ((array)$_POST['domain_fields'] as $key => $fields) {
            if ($key === 'domain_timework') {
                $arFields[$key] = $fields;
            } else {
                $arFields[$key] = sanitize_text_field($fields);
            }
        }

        return update_post_meta($postId, '_domain_meta_fields', $arFields);
    }

    /**
     * Регистрация настроек плагина
     */
    public function domainSettingsPage()
    {
        register_setting('seoregions_group', 'seoregions_option', 'sanitizeCallback');
        add_settings_section('seoregions_main', 'Основные настройки', '', 'domain');

        /** Поля формы */
        add_settings_field('redirect', 'Перенаправление на основной домен', [$this->pageView, 'fieldRedirect'], 'domain', 'seoregions_main');
        add_settings_field('devdomain', 'Тестовые поддомены', [$this->pageView, 'fieldDevdomain'], 'domain', 'seoregions_main');
    }

    /**
     * Очистка данных
     *
     * @param $options
     *
     * @return mixed
     */
    public function sanitizeCallback($options)
    {
        foreach ((array)$options as $name => & $val) {
            $val = strip_tags($val);
        }

        return $options;
    }

    /**
     * Изменение плейсхолдера в названии записи
     *
     * @param $placeholder
     *
     * @return string
     */
    public function changePlaceholders($placeholder):string
    {
        if (get_current_screen()->post_type === 'domain') {
            $placeholder = 'Введите название города';
        }

        return $placeholder;
    }

    /**
     * Добавление ссылки на страницу настроек в списке плагинов
     *
     * @param      $links
     * @param null $file
     *
     * @return array
     */
    public function setMeta($links, $file = null):array
    {
        if ($file === SEO_REGIONS_BASE) {
            $newlink = [sprintf('<a href="' . admin_url('admin.php?page=domain-settings') . '">%s</a>', __('Settings'))];
            $links   = array_merge($newlink, $links);
        }

        return $links;
    }
}
