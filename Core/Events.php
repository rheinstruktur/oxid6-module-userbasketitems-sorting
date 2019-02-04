<?php

namespace Rheinstruktur\BasketItemSorting\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\DatabaseViewsGenerator\ViewsGenerator;

/**
 * Class defines what module does on Shop events.
 */
class Events
{

    /**
     * Add necesary field to article table
     */
    public static function addFieldToUserBasketItemsTable()
    {
        try {
            DatabaseProvider::getDb()->execute(
                    "ALTER TABLE `oxuserbasketitems` ADD COLUMN `RS_SORT` INT(11) NOT NULL DEFAULT 0;"
                );
        } catch (\Exception $e) {
            echo 'Exception catched: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        self::addFieldToUserBasketItemsTable();
        self::regenerateViews();
    }

    /**
     * Execute action on deactivate event
     *
     * @return null
     */
    public static function onDeactivate()
    {

    }

    private static function regenerateViews() {
        $viewsGenerator = new ViewsGenerator();
        $viewsGenerator->generate();
    }
}
