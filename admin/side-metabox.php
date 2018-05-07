<?php

/** @var $arResult */
if (!$arResult) {
    $arResult = [];
}

?>
<div id="post-formats-select">
    <fieldset>
        <input type="checkbox"
               name="domain_fields[domain_default]"
               class="post-format"
               id="domain_default"
               value="1"
            <?php echo $arResult['domain_default'] === '1' ? 'checked="checked" ' : ''; ?>
        />
        <label for="domain_default">Город по умолчанию</label><br>

        <input type="checkbox"
               name="domain_fields[domain_not_subdomain]"
               class="post-format"
               id="domain_not_subdomain"
               value="1"
            <?php echo $arResult['domain_not_subdomain'] === '1' ? 'checked="checked" ' : ''; ?>
        />
        <label for="domain_not_subdomain">Не поддомен</label>
    </fieldset>
</div>
