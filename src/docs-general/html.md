# HTML

> HTML Guidlines

- Use HTML5
- Use Semantic HTML
- Do not use excessive markup

## Semantic HTML

Use semanitic markup, eg:

![#ff0000](https://placehold.it/15/ff0000/000000?text=+) Bad:
```
<p>Lorem ipsum dolor. <span class="italic">Duis tristique interdum mi vel posuere</span>.</p>
```

![#00ff00](https://placehold.it/15/00ff00/000000?text=+) Good
```
<p>Lorem ipsum dolor. <em>Duis tristique interdum mi vel posuere</em>.</p>
```

## Excessive HTML Elements

Don't be "THAT GUY", there is no need to use excessive HTML elemets. 

For example you can place a class on an image you don't need to wrap that image in a container div.

![#ff0000](https://placehold.it/15/ff0000/000000?text=+) Bad:
```
<div class="profile__avatar-container">
<img class="profile__avatar" src="path-to-asset.jog" loading="lazy">
</div>
```

![#00ff00](https://placehold.it/15/00ff00/000000?text=+) Good
```
<img class="profile__avatar" src="path-to-asset.jog" loading="lazy">
```
