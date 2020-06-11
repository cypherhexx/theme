# ACF Pro

> The site uses ACF Pro for creating custom fields.

## Local JSON
If you don't know what [Local JSON](https://www.advancedcustomfields.com/resources/local-json/) is then you need to read up on it.

Never under any circumstances change fields on staging or production. The fields must always be synced from dev using the JSON files so that they are managed within version control.

You'll need to sync (from within the custom fields menus) the fields after you have set up the site.

## Keep it tidy
The backend / dashboard should remain tidy for the end user so take advantage of the layout options ACF offers.

Do not over complicate the backend, it should be easy for a user to navigate between the different blocks within a page builder for example.

## Hiding fields
Be careful using ACF Pro's hide/show conditional rules. There is a bug within the plugin which doesn't correctly move hidden fields when inside a repeater or flexible content. This results in hidden fields now being associated with different content.
