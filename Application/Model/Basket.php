<?php

namespace Rheinstruktur\BasketItemSorting\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class Basket extends Basket_parent
{

    /**
     * Saves existing basket to database
     */
    protected function _save()
    {
        if ($this->isSaveToDataBaseEnabled()) {
            if ($oUser = $this->getBasketUser()) {
                //first delete all contents
                //#2039
                $oSavedBasket = $oUser->getBasket('savedbasket');
                $oSavedBasket->delete();

                //then save
                /** @var \oxBasketItem $oBasketItem */
                //RS NEW NEXT LINE
                $i = 0;
                foreach ($this->_aBasketContents as $oBasketItem) {
                    // discount or bundled products will be added automatically if available
                    if (!$oBasketItem->isBundle() && !$oBasketItem->isDiscountArticle()) {
                        //TSP MOD NEXT LINE
                        $oSavedBasket->addItemToBasket(
                            $oBasketItem->getProductId(),
                            $oBasketItem->getAmount(),
                            $oBasketItem->getSelList(),
                            true,
                            $oBasketItem->getPersParams(),
                            $i //RS NEW THIS LINE
                        );
                        //RS NEW NEXT LINE
                        $i++;
                    }
                }
            }
        }
    }

}
