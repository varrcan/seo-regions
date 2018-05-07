<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsAdmin
 * @package Varrcan\SeoRegions
 */
class SeoRegionsView
{
    private $options;

    /**
     * Вывод основного метабокса
     *
     * @param $post
     */
    public function domainPageOutput($post)
    {
        wp_nonce_field('formDomain', 'formDomainNonce');
        $arResult = get_post_meta($post->ID, '_domain_meta_fields', true);

        include_once SEO_REGIONS_DIR . 'admin/main-metabox.php';
    }

    /**
     * Вывод метабокса в сайдбар
     *
     * @param $post
     */
    public function domainSideOutput($post)
    {
        wp_nonce_field('formDomain', 'formDomainNonce');
        $arResult = get_post_meta($post->ID, '_domain_meta_fields', true);

        include_once SEO_REGIONS_DIR . 'admin/side-metabox.php';
    }

    /**
     * Страница настроек
     */
    public function optionsPageOutput()
    {
        $this->options = get_option('seoregions_option');

        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>

            <form action="options.php" method="POST">
                <?php
                settings_fields('seoregions_group');
                do_settings_sections('domain');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function fieldRedirect()
    {
        echo '<label><input type="checkbox" name="seoregions_option[redirect]" value="1" '. checked(1, $this->options['redirect'], false) . ' /> включить</label>';
        echo '<p class="description">если текущий домен не совпадает ни с одним из зарегистрированных</p>';
    }

    public function fieldDevdomain()
    {
        echo '<input type="text" class="regular-text" name="seoregions_option[devdomain]" value="' . esc_attr($this->options['devdomain']) . '"/>';
        echo '<p class="description">коды тестовых поддоменов через запятую</p>';
    }

    /**
     * Страница помощи
     */
    public function helpPageOutput()
    {
        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>
            
        </div>
        <?php
    }
}
