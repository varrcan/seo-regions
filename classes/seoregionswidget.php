<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsWidget
 * @package Varrcan\SeoRegions
 */
class SeoRegionsWidget extends \WP_Widget
{
    use SeoRegionsLoader;

    /**
     * SeoRegionsWidget constructor.
     */
    public function __construct()
    {
        parent::__construct('seoregions_widget', 'SeoRegions', ['description' => 'Список, выпадающее меню или модальное окно списка городов.']);
    }

    /**
     * Регистрация виджета
     */
    public function registerWidget()
    {
        register_widget($this);
    }

    /**
     * Отображение в админке
     *
     * @param array $instance сохраненные данные из настроек
     */
    public function form($instance)
    {
        $title = $instance['title'] ?? 'Ваш город:';

        $options = [
            '1' => 'Список',
            '2' => 'Выпадающее меню',
            '3' => 'Модальное окно',
        ];

        $titleId     = $this->get_field_id('title');
        $titleName   = $this->get_field_name('title');
        $optionsId   = $this->get_field_id('options');
        $optionsName = $this->get_field_name('options');

        echo '<p><label for="' . $titleId . '">Текст текущего города</label><br />';
        echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '"></p>';

        echo '<p><label for="' . $optionsId . '">Внешний вид</label><br />';
        echo '<select id="' . $optionsId . '" name="' . $optionsName . '">';
        foreach ($options as $key => $option) {
            echo '<option value="' . $key . '" ' . selected($instance['options'], $key, false) . '>' . $option . '</option>';
        }
        echo '</select></p>';
    }

    /**
     * Вывод виджета front-end
     *
     * @param array $args аргументы виджета.
     * @param array $instance сохраненные данные из настроек
     */
    public function widget($args, $instance)
    {
        if (SeoRegionsPublic::getDomainName()) {
            $arResult = SeoRegionsPublic::getAllDomainsData();

            $out = '<div class="select seoregions-wrap">' . PHP_EOL;
            $out .= '<span>' . $instance['title'] . '</span>' . PHP_EOL;
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

            echo $out;
        }
    }

    /**
     * Сохранение настроек виджета.
     *
     * @param $newInstance
     * @param $oldInstance
     *
     * @return array данные которые будут сохранены
     */
    public function update($newInstance, $oldInstance):array
    {
        $values            = [];
        $values['title']   = htmlentities($newInstance['title']);
        $values['options'] = (int)$newInstance['options'];

        return $values;
    }
}
