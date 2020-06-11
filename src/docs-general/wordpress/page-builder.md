# Page Builder

> The page builder uses ACF Pro's flexible content to enable multiple layouts on different pages using common elements.

## Overview
All of the modules have been set up and left in a very basic state delibtatly so that a developer is able to reskin them to fit the design of the site.

Many have a theme selector to allow the module to have multiple layouts or designs within the same website.

## Modules

- [Banners - Full Bleed](./wordpress/pb-banner-full-bleed.md)
- [Copy Only](./wordpress/pb-copy-only.md)
- [Copy & Image](./wordpress/pb-copy-image.md)
- [Copy & Gravity Forms](./wordpress/pb-copy-gravity-form.md)
- [Copy & Video](./wordpress/pb-copy-video.md)
- [Cooy Blocks ](./wordpress/pb-copy-blocks.md)
- [Cooy & Block Picker](./wordpress/pb-copy-block-picker.md)
- [Compare](./wordpress/pb-compare.md)
- [Full Width Video](./wordpress/pb-full-width-video.md)
- [Quote](./wordpress/pb-quote.md)
- [Accordion](./wordpress/pb-accordion.md)
- [Gallery](./wordpress/pb-gallery.md)
- [Google Maps](./wordpress/pb-google-maps.md)

All sections have an id attached to them of "section-x" where x is a number. The first section in the page is always 1, this is incremented for every single new section added. This allows content editors to add an internal section link within CTAs eg: https://www.domain.com/#section-4

## Framework grid
There is a very basic grid been put in place, you'll find this in the following file
- ```src/scss/objects/grid.scss```

## Framework theme
Many of the modules allow you to select a theme which returns either ```primary```, ```secondary``` or ```alternative```. This is to allow you to add an alternative background colour on the modules to help breakup the pages.
- ```src/scss/objects/theme.scss```

## Unused Modules
Remove any un-used modules which haven't been reskinned / designed 
