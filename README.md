# oxid6-module-userbasketitems-sorting

Module for OXID ESHOP 6.x to sort the saved basket basketitems in the way the user added them to the basket after logout and login.
In the standard, the eShop sorts the items by sku after loading the persisted basket. This makes no sense at all.

# Installation

## manually

1. download and rename directory "xid6-module-userbasketitems-sorting" to "BasePrice"

2. copy BasePrice to [shoproot]/source/modules/rs/BasketItemSorting

3. Add following to you composer.json:
```
"autoload": {
    "psr-4": {
      "Rheinstruktur\\": "./source/modules/rs/"
    }
}
```

## via composer

composer require rheinstruktur/ooxid6-module-userbasketitems-sorting (NOT CONFIGURED YET!)