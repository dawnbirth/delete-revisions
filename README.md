# delete-revisions
A simple custom WordPress plugin to delete post revisions before a specific date.

Usage
==============
After activating the plugin you can initialize it like this:
```
//Initialize Our plugin
$deleteRevision = new \DMI\DeleteRevisions();

```
Then go to your wordpress Dashboard to start the plugin.

By Default this plugin will delete all revisions before 'January 1st, 2018', for each ajax request
it will delete 100 revisions.

To change the default behaviour use the methods 'before()' and 'number()' Like this:

```
//Initialize Our plugin
$deleteRevision = new \DMI\DeleteRevisions();

// Set the date before which we want to delete revisions:
$deleteRevision->before( 'June 1st, 2018' );

// Set the number of revisions to delete per request.
$deleteRevision->number( 300 );

```



