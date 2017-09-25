# Website Management

**Note: This is not recommended for production use just yet, still removing some business
details as hard-coded strings.**

This web application is designed to be a simple portal to allow clients to create support
tickets, create new emails for their web hosting, change email passwords, and check their
disk usage.

It is built with Laravel, Vue.js and the Bulma CSS framework.

There is also simple invoicing functionality built-in which will become optional and possibly
removable in the future.

## Screenshots

![](http://keithmcgahey.com/images/geckode/geckode-01.jpg)

![](http://keithmcgahey.com/images/geckode/geckode-03.jpg)

## TODO

- [ ] Remove all references to Geckode website.
- [ ] Make invoicing details dynamic.
- [ ] Escape/Filter WHM function calls.
- [ ] Add a default invoice note setting.
- [ ] Rewrite as many tests without using Laravel Dusk as possible.
