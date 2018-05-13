<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/** @var SeoRegionsWidget $instance */

$title = $instance['title'] ?? 'Ваш город:';

?>

<div class="seoregions">
    <span><?=$title;?></span>
    <div class="seoregions__wrap">
        <span class="seoregions__active"><?=SeoRegionsPublic::getDomainName();?></span>
        <span class="seoregions__hidden">
        <?php if (isset($arResult)) : ?>
            <?php foreach ((array)$arResult as $arItem) : ?>
                <span class="seoregions__hidden-item">
                    <a href="<?=$arItem['domain_url'] . $_SERVER['REQUEST_URI'];?>"
                       class="seoregions__hidden-link"><?=$arItem['domain_city'];?>
                    </a>
                </span>
            <?php endforeach; ?>
        <?php endif; ?>
        </span>
    </div>
</div>
