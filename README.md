# FroshTemplateExtensions

[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)


The idea behind this plugin is to provide useful functions for theme developers in smarty.

## Current available functions


### Thumbnail

You generate with this function for every uploaded image custom thumbnails.

Following parameters are available

* id / path 
* width
* height
* keepProportion
* quality

All images are generated using Shopware default Thumbnail Generator. So also all images are optimized

Examples

```
<img src="{thumbnail id=739 width=1920 height=1920}">
```

```
<img src="{thumbnail path=media/image/foo.png width=1920 height=1920}">
```

### Fetch

Fetch data from the database using the functions:

* `fetchOne` - fetches a single value from the specified column of a row
* `fetchRow` - fetches the first row from the specified table
* `fetchAll` - fetches all rows from the specified table

The following parameters are available

* `var` - required for `fetchRow` and `fetchAll`, defines the variable the resulting array will be stored in
* `select` - may either be of type `string` or `array`, specifies the columns to be fetch, required for `fetchOne`
* `from` - required for all functions, specifies the table to be fetched from
* `where` - must be of type `array`, must contain key value pairs in the format of `'[column][operand]' => value` 
* `order` - must be of type `array`, must contain key value pairs in the format of `'[column]' => '[ASC][DESC]'` 
* `offset` - must be of type `integer`, sets the offset for the first result
* `limit` - must be of type `integer`, sets the limit of results

Examples

```smarty
{* Fetches the price of the last article added to basket of the current user *}
{fetchOne select="price" from="s_order_basket" where=["modus =" => 0, "sessionID =" => $smarty.session.Shopware.sessionId] order=["id" => "DESC"]}

{* Fetches the 5th to 15th row of s_articles *}
{fetchAll var="articles" from="s_articles" offset=5 limit=10}
{foreach $articles as $article}
    Article: {$article.name} - {$article.description}<br>
{/foreach}

{* Fetches the last order's id and ordernumber *}
{fetchRow var="order" select=["ordernumber", "id"] from="s_order" where=["status !=" => -1] order=["ordertime" => "DESC"]}
{$order.id} - {$order.ordernumber}
```

*Please note:* The fetch functions need to be actived through the configuration of the plugin. They are disabled by default. 
Before activating the fetch functions, please make sure, that none of your changes to your theme allow for unsanitized user 
inputs to be compiled as smarty.

## Requirements

- Shopware 5.5


## Installation

- Download latest release
- Extract the zip file in `shopware_folder/custom/plugins/`


## Contributing

Feel free to fork and send pull requests!


## Licence

This project uses the [MIT License](LICENCE.md).
