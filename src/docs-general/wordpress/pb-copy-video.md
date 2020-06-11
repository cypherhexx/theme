# Copy & Video

> A module wich allows users to have copy and an image

## Configuration 
- Alignment
	- There are 2 options, these being Left & Right. the alignment refers to the copy on everything from the ```sm-up``` breakpoint
- Theme
	- Returns one of 3 options, this ensures that all of the sections remain on brand.
	- primary
	- secondary
	- alternative
- Video Provider
	- This is either YouTube or Vimeo
- Video ID
	- This is the ID of the video, it is used to generate the embded iFrame

## Options
- Header
- Sub Header
- Copy
- CTAs (up to 3)

## WordPress Theme
Main template:
- ```wp-content/THEME/templates/pb-copy-video.php```

## Scss files
Main file:
- ```src/scss/components/_pb-copy-video.scss```

Be also aware of:
- ```src/scss/components/_pb-copy.scss```
