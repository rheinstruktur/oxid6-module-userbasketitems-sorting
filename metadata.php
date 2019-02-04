<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'rs_userbasketitems_sorting',
    'title' => '<strong style="color:#1E477F;">rheinstruktur.de</strong>:  <i>Warenkorbpositionen Sortierung</i>',
    'description' => array(
        'de' => 'Warenkorbpositionen werden mit Sortierung in der Datenbank gespeichert, wie der User sie in den Warenkorb gelegt hat, auch nach logout und login',
    ),
    'thumbnail' => 'rheinstruktur.png',
    'version' => '1.0',
    'author' => 'Daniel Speckhardt',
    'url' => '',
    'email' => 'speckhardt@rheinstruktur.de',
    'extend' => array(
        \OxidEsales\Eshop\Application\Model\Basket::class       => \Rheinstruktur\BasketItemSorting\Application\Model\Basket::class,
        \OxidEsales\Eshop\Application\Model\UserBasket::class   => \Rheinstruktur\BasketItemSorting\Application\Model\UserBasket::class
    ),
    'events' => array(
        'onActivate' => '\Rheinstruktur\BasketItemSorting\Core\Events::onActivate',
        'onDeactivate' => '\Rheinstruktur\BasketItemSorting\Core\Events::onDeactivate'
    )
);