# WordPress

> WordPress Guidlines

- We use [WordPress Coding standards](https://codex.wordpress.org/WordPress_Coding_Standards)
- We have our own [Page Builder](./wordpress/page-builder.md) built in Advanced Custom Fileds Pro, it has a number of pre-built modules.

## Don't brace yourself

We tend not to use braces, therefore;

![#ff0000](https://placehold.it/15/ff0000/000000?text=+) Instead of:
```php
if( this === that ){
	...
} else if( this === something ) {
	...
} else {
	...
}
```

```php
foreach {
	...
}
```

![#00ff00](https://placehold.it/15/00ff00/000000?text=+) Please use:
```php
if( this === that ):
	...
elseif( this === something ):
	...
else:
	...
endif;
```

```php
foreach:
	...
endforeach;
```

## Don't repeat yourself

Make use of WordPress templates to reduce the amount of code repeated.

## Paths for assets & urls

Please take this seriously, If you include absolute paths to any asset or url within the template files, CSS or JS you'll never work for Big Rock again, and be black listed for your crimes. This is non negotiable.

WordPress has more than enough simple hooks you can use to make sure all links are relative. This also includes linking to the domain name. A user should be able to click around on staging without ending up on the production domain.

EG:

- ```get_home_url();```
- ```get_template_directory_uri();```
