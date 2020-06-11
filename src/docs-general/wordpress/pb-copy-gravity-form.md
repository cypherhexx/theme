# Copy & Gravity Forms

> Allows copy to site along side a form built in Gravity Forms

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
- Form (Gravity Form)
- CTAs (up to 3)

## WordPress Theme
Main template:
- ```wp-content/THEME/templates/pb-copy-gravity-form.php```
- ```wp-content/THEME/templates/pb-copy-form--gravity.php```
- ```wp-content/THEME/templates/pb-copy-form--copy.php```

## Scss files
Main file:
- ```src/scss/components/_copy-form.scss```

Be also aware of:
- ```src/scss/components/_pb-copy.scss```

Please also note that Gravity Forms loads in it's own CSS. You are welcome to disable this within the Gravity Forms settings if you wish
