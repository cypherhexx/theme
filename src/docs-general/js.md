# JavaScript

> For the time being you are free to use jQuery, however this will be changing in the future. 

The JS is compiled using Gulp from the ```src/js``` directory.

Write custom JavaScript/jQuery where possible instead of using a libary or bloating the code base. 

All JS should be loaded using wp_enqueue_script()

Do not load in more scripts than are required. There are 2 custom files loaded into the the base project, these are ```assets/js/app-min.js``` and ```assets/js/libs/app-libs-min.js```. Both of these files are compiled using [Gulp](./gulp.md).
