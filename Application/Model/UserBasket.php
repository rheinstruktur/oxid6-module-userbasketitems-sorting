<?php

namespace Rheinstruktur\BasketItemSorting\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class UserBasket extends UserBasket_parent
{

    /**
     * Method adds/removes user chosen article to/from his noticelist or wishlist. Returns total amount
     * of articles in list.
     *
     * @param string $sProductId Article ID
     * @param double $dAmount    Product amount
     * @param array  $aSel       product select lists
     * @param bool   $blOverride if true overrides $dAmount, else sums previous with current it
     * @param array  $aPersParam product persistent parameters (default null)
     * @param int  $iSorting
     *
     * @return integer
     */
    //TSP MOD NEXT LINE
    public function addItemToBasket($sProductId = null,
                                    $dAmount = null,
                                    $aSel = null,
                                    $blOverride = false,
                                    $aPersParam = null,
                                    $iSorting = 0 //RS NEW THIS LINE
    )
    {
        // basket info is only written in DB when something is in it
        if ($this->_blNewBasket) {
            $this->save();
        }

        //TSP MOD NEXT INE
        if (($oUserBasketItem = $this->getItem($sProductId, $aSel, $aPersParam, $iSorting))) {
            // updating object info and adding (if not yet added) item into basket items array
            if (!$blOverride && !empty($oUserBasketItem->oxuserbasketitems__oxamount->value)) {
                $dAmount += $oUserBasketItem->oxuserbasketitems__oxamount->value;
            }

            if (!$dAmount) {
                // amount = 0 removes the item
                $oUserBasketItem->delete();
                //TSP MOD NEXT TWO LINES
                if (isset($this->_aBasketItems[$this->_getItemKey($sProductId, $aSel, $aPersParam)])) {
                    unset($this->_aBasketItems[$this->_getItemKey($sProductId, $aSel, $aPersParam)]);
                }
            } else {
                $oUserBasketItem->oxuserbasketitems__oxamount = new \OxidEsales\Eshop\Core\Field($dAmount, \OxidEsales\Eshop\Core\Field::T_RAW);
                $oUserBasketItem->save();

                $this->_aBasketItems[$this->_getItemKey($sProductId, $aSel, $aPersParam)] = $oUserBasketItem;
            }

            //update timestamp
            $this->oxuserbaskets__oxupdate = new \OxidEsales\Eshop\Core\Field(\OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime());
            $this->save();

            return $dAmount;
        }
    }

    /**
     * Searches for item in basket items array and returns it. If not item was
     * found - new item is created.
     *
     * @param string $sProductId  product id, basket item id or basket item index
     * @param array  $aSelList    select lists
     * @param string $aPersParams persistent parameters
     * @param int  $iSorting
     *
     * @return oxUserBasketItem
     */
    //TSP MOD NEXT LINE
    public function getItem($sProductId,
                            $aSelList,
                            $aPersParams = null,
                            $iSorting = 0 //TSP NEW
    )
    {
        // loading basket item list
        $aItems = $this->getItems();
        $sItemKey = $this->_getItemKey($sProductId, $aSelList, $aPersParams);
        $oItem = null;
        // returning existing item
        if (isset($aItems[$sProductId])) {
            $oItem = $aItems[$sProductId];
        } elseif (isset($aItems[$sItemKey])) {
            $oItem = $aItems[$sItemKey];
        } else {
            //TSP MOD NEXT LINE
            $oItem = $this->_createItem($sProductId, $aSelList, $aPersParams, $iSorting);
        }

        return $oItem;
    }

    /**
     * Creates and returns  oxuserbasketitem object
     *
     * @param string $sProductId  Product Id
     * @param array  $aSelList    product select lists
     * @param string $aPersParams persistent parameters
     * @param int  $iSorting
     *
     * @return oxUserBasketItem
     */
    //TSP MOD NEXT LINE
    protected function _createItem($sProductId,
                                   $aSelList = null,
                                   $aPersParams = null,
                                   $iSorting = 0 //RS NEW THIS LINE
    )
    {
        $oNewItem = oxNew(\OxidEsales\Eshop\Application\Model\UserBasketItem::class);
        $oNewItem->oxuserbasketitems__oxartid = new \OxidEsales\Eshop\Core\Field($sProductId, \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewItem->oxuserbasketitems__oxbasketid = new \OxidEsales\Eshop\Core\Field($this->getId(), \OxidEsales\Eshop\Core\Field::T_RAW);

        //RS NEW NEXT LINE
        $oNewItem->oxuserbasketitems__tspsort = new \OxidEsales\Eshop\Core\Field($iSorting, \OxidEsales\Eshop\Core\Field::T_RAW);

        if ($aPersParams && count($aPersParams)) {
            $oNewItem->setPersParams($aPersParams);
        }

        if (!$aSelList) {
            $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oArticle->load($sProductId);
            $aSelectLists = $oArticle->getSelectLists();
            if (($iSelCnt = count($aSelectLists))) {
                $aSelList = array_fill(0, $iSelCnt, '0');
            }
        }

        $oNewItem->setSelList($aSelList);

        return $oNewItem;
    }
}
