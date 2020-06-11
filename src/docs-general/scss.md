# SCSS

> Scss & CSS Guidlines

Make sure you read through the docs below;

- We use the Scss (.scss) variant of Sass so please adhere to this.
- Make sure you are taking a Mobile First approach with all your CSS.
- Please be aware this project is set up to use [Sassdoc](http://sassdoc.com/), please use it to document your sass, you'll find all of the Sass docs in src/docs-sass.
- We like to have a structure, this means make your sass modular
- We use Object Orientated CSS (OOCSS)
- We also use BEM
- We don't style IDs
- We also don't style HTML elements
- We only style with class names

## Project structure

### ITCSS

Your Scss should be well structured, the base of this project uses the ITCSS approach, please leave this in place.

- [ITCSS: Scalable and Maintainable CSS Architecture](https://www.xfive.co/blog/itcss-scalable-maintainable-css-architecture/)

### Object Oriented CSS (OOCSS)
We use Object Oriented CSS, if you don't know what that is then you need to check out the following tutorials and blog articles first.

In simple terms you need to make all your CSS class based.

 - [An Introduction To Object Oriented CSS (OOCSS)
 ](https://www.smashingmagazine.com/2011/12/an-introduction-to-object-oriented-css-oocss/)


### BEM
We also use BEM and make great use of modifier classes, make sure you read the following if you have no idea what BEM is

- [Get BEM](http://getbem.com/introduction/)

## Nesting, Chaining and using @extend

### Nesting:
You shouln't under any

![#ff0000](https://placehold.it/15/ff0000/000000?text=+) Bad:
```
.foo {
	color: $Black,
	.bar {
		font-weight: bold;
	}
}
```

![#00ff00](https://placehold.it/15/00ff00/000000?text=+) Good:
```
.foo {
	color: $Black,
}

.bar {
	font-weight: bold;
}
```

### Chaining

Chaining CSS classes means longer CSS selectors and also slower rendering, therefore do not do it. You can in 99.99% of the time produce the desired result with a modifier class. 

![#ff0000](https://placehold.it/15/ff0000/000000?text=+) Bad:
```
.foo {

	color: $Black,

	&.emphasis {
		font-weight: bold;
	}

}
```

![#00ff00](https://placehold.it/15/00ff00/000000?text=+) Good
```
.foo {
	
	color: $Black,
	
	&--emphasis {
		font-style: italic;
	}

}
```

### @extend

Do not use at all, you'll end up with a bloated code base, you'll be better using a helper class.
