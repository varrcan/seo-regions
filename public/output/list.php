<?php

namespace Varrcan\SeoRegions;

use Varrcan\SeoRegions\SeoRegionsPublic;

if (!\defined('ABSPATH')) {
    die;
}

/** @var SeoRegionsWidget $instance */
$title = $instance['title'] === '' ? 'Офисы в других городах:' : $instance['title'];

?>

<div class="seoregions">
    <span><?=$title;?></span>
    <div class="seoregions__list">
        <?php if (isset($arResult)) : ?>
            <?php foreach ((array)$arResult as $arItem) : ?>
                <?php if ($arItem['domain_city'] !== SeoRegionsPublic::getDomainName()) : ?>
                    <a href="<?=$arItem['domain_url'] . $_SERVER['REQUEST_URI'];?>"
                       class="seoregions__list-item"><?=$arItem['domain_city'];?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
