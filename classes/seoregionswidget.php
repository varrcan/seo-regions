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
            'list'   => 'Список',
            'select' => 'Выпадающее меню',
            'modal'  => 'Модальное окно',
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

            switch ($instance['options']) {
                case 'list':
                    include SEO_REGIONS_DIR . 'public/output/list.php';
                    break;
                case 'select':
                    include SEO_REGIONS_DIR . 'public/output/select.php';
                    break;
                case 'modal':
                    include SEO_REGIONS_DIR . 'public/output/modal.php';
                    break;
            }
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
        $values['options'] = htmlentities($newInstance['options']);

        return $values;
    }
}
