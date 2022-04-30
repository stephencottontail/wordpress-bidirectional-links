# Bidirectional Links

This plugin creates bidirectional links between posts: if post `foo` contains a link to post `bar`, when `foo` is saved, the plugin creates post meta on `bar` with `foo`'s ID and title.

## Notes

I created this for a personal project and so some of its behavior is related to the project's needs, though I hope it may still be valuable to you.

* The project doesn't use static pages and so permalinks are set to "Post name". The plugin likely will not work with other permalihk settings.

* The plugin does not account for later post revisions; if the link to `bar` is later removed from `foo`, `bar`'s meta is not updated.
