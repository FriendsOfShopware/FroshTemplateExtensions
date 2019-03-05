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


## Requirements

- Shopware 5.5


## Installation

- Download latest release
- Extract the zip file in `shopware_folder/custom/plugins/`


## Contributing

Feel free to fork and send pull requests!


## Licence

This project uses the [MIT License](LICENCE.md).
