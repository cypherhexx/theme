# Google Maps

> Embed a map from Google

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

## Additional setup requitred.
An API key is required, this needs adding to the codebase, you'll find a function commented out called ```br_acf_init``` within the following file.
```wp-content/plugins/CUSTOM-SITE-PLUGIN/inc/plugins/acf-pro```

also be aware of the docs at ACF Pro <https://www.advancedcustomfields.com/resources/google-map/>

## WordPress Theme
Main template:
- ```wp-content/THEME/templates/pb-google-maps.php```

## Scss files
Main file:
- ```src/scss/components/_pb-google-maps.scss```

Be also aware of:
- ```src/scss/components/_pb-copy.scss```

## JS file
- ```src/js/google-maps.js```
