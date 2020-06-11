# Plugins

> Avoid using plugins where possible

9 times out of 10 a plugin is not required and you can do the work by spending a few minutes thinking about an issue. Custom Post Types are a perfect example of this. You do not need a plugin to add a custom post type, create it in code instead, this way we can easily manage deployments to environments as well as storing everything in version control.

## Big Rock
Our Big Rock plugin sets up WordPress to work with our custom page builder, it needs to remain installed and untouched.

## Custom Site Plugin
Rather than adding all of your functions into the functions.php file you should ask the following question.

> If this site were to be reskinned tomorrow would this function be required?

If the answer was yes, you need to add the code to this plugin rather than within the functions.php file. There is an example custom post type and taxonomy which cn be used as a base.

This plugin is intended to be extended by you the developer.

## YOAST
We do not use YOAST. The SEO Framework has been installed in its place as it is far more secure and better at doing what it should.
