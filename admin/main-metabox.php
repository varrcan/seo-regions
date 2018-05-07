<?php

if (!\defined('ABSPATH')) {
    die;
}

/** @var $arResult */
if (!$arResult) {
    $arResult = [];
}

?>

<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <label for="domain_sort">Сортировка</label>
        </th>
        <td>
            <input type="text"
                   id="domain_sort"
                   name="domain_fields[domain_sort]"
                   value="<?=esc_attr($arResult['domain_sort']);?>"
                   class="regular-text code"
            />
            <p class="description">Используется при выводе городов на странице</p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_code">Символьный код города</label>
        </th>
        <td>
            <input type="text"
                   id="domain_code"
                   name="domain_fields[domain_code]"
                   value="<?=esc_attr($arResult['domain_code']);?>"
                   class="regular-text code"
            />
            <p class="description">Например, <code>spb</code> (если поддомен) или <code>spb-site.ru</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_address">Адрес</label>
        </th>
        <td>
            <input type="text"
                   id="domain_address"
                   name="domain_fields[domain_address]"
                   value="<?=esc_attr($arResult['domain_address']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{адрес}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_email">E-mail</label>
        </th>
        <td>
            <input type="text"
                   id="domain_email"
                   name="domain_fields[domain_email]"
                   value="<?=esc_attr($arResult['domain_email']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{email}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_phone">Телефон</label>
        </th>
        <td>
            <input type="text"
                   id="domain_phone"
                   name="domain_fields[domain_phone]"
                   value="<?=esc_attr($arResult['domain_phone']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{телефон}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_phone2">Телефон 2</label>
        </th>
        <td>
            <input type="text"
                   id="domain_phone2"
                   name="domain_fields[domain_phone2]"
                   value="<?=esc_attr($arResult['domain_phone2']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{телефон2}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_timework">Время работы</label>
        </th>
        <td>
            <textarea id="domain_timework"
                      class="regular-text code"
                      name="domain_fields[domain_timework]"
            ><?=($arResult['domain_timework']);?></textarea>
            <p class="description">Переменная <code>{время}</code>. В этом поле разрешены HTML теги</p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_city">Город</label>
        </th>
        <td>
            <input type="text"
                   id="domain_city"
                   name="domain_fields[domain_city]"
                   value="<?=esc_attr($arResult['domain_city']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{город}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_in_city">В городе</label>
        </th>
        <td>
            <input type="text"
                   id="domain_in_city"
                   name="domain_fields[domain_in_city]"
                   value="<?=esc_attr($arResult['domain_in_city']);?>"
                   class="regular-text code"
            />
            <p class="description">Переменная <code>{в городе}</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_yandex">Код подтверждения Yandex</label>
        </th>
        <td>
            <input type="text"
                   id="domain_yandex"
                   name="domain_fields[domain_yandex]"
                   value="<?=esc_attr($arResult['domain_yandex']);?>"
                   class="regular-text code"
            />
            <p class="description">Будет выведено в meta-поле <code>yandex-verification</code></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="domain_google">Код подтверждения Google</label>
        </th>
        <td>
            <input type="text"
                   id="domain_google"
                   name="domain_fields[domain_google]"
                   value="<?=esc_attr($arResult['domain_google']);?>"
                   class="regular-text code"
            />
            <p class="description">Будет выведено в meta-поле <code>google-site-verification</code></p>
        </td>
    </tr>
    </tbody>
</table>
