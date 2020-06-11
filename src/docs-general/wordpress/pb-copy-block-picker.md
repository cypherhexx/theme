# Copy & Block Picker

> Allows a content editor to select pages and posts to sit along side each other (upto 8)

## Configuration 
- Alignment
	- There are 3 options, these being Center, Left & Right
- Theme
	- Returns one of 3 options, this ensures that all of the sections remain on brand.
		- primary
		- secondary
		- alternative

## Options
- Header
- Sub Header
- Copy
- Blocks

## Extend
- You can extend this by including custom post types in the array of post types whcih can be selected.
- You can also call in the featured image if required

## WordPress Theme
Main template:
- ```wp-content/THEME/templates/pb-copy-block-picker.php```

## Scss files
Main file:
- ```src/scss/components/_pb-copy-block-picker.scss```

Be also aware of:
- ```src/scss/components/_pb-copy-blocks.scss```
- ```src/scss/components/_pb-copy.scss```