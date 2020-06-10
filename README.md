# Customisable Archive Templates

__A project by [Pragmatic](https://pragmatic.agency).__

> Customisable Archive Templates for your WordPress.

---

* [Requirements](#requirements)
* [Installation](#installation)
* [Development Process](#development-process)

---

## Requirements
### Usage Requirements
Ensure you have the prerequisite software installed:

* [Composer](https://getcomposer.org/) 1.8+, installed globally.
* [PHP](https://php.net/) 7.3.5+.


## Installation
Install this plugin with Composer: `composer require pragmaticweb/customisable-archive-templates`. Activate the plugin in your WordPress in the usual manner.

Edit a taxonomy term, and enable "Use Template". Set "Template Post ID" to a Post ID of any kind of WordPress post object.
This post will be rendered onto the taxonomy archive URL for the specific term.

After that, create a custom `taxonomy-{taxonomy_name}.php` template in your theme and customise it for your requirements. We suggest duplicating a single post template like `page.php`.

The first post in the template loop will be the "Template Post" you selected earlier; everything subsequent will be the standard list of all posts that are assigned to the current taxonomy term.
You can choose to split the loop and render both parts in different places (e.g. a "see more xyz content" footer), or just render the main "Template Post" and skip the rest of the template loop with a `break` instruction inside the loop.


## Development Process

This plugin mostly follows the standard [Git Flow](http://jeffkreeftmeijer.com/2010/why-arent-you-using-git-flow/) model.

### Workflow
Development happens in feature branches, which are merged into `develop`, and then eventually merged into `trunk` for deployment to production. When making changes, a feature branch should be created that branches from `develop` (and the corresponding pull request should use `develop` as the target branch).

### Scripts and Tooling
* `composer run lint:phpcs` -- checks PHP files for compatibility with the WordPress-VIP-Go standards.
* `composer run lint:phpcompat` -- checks PHP files for compatibility with PHP 7.3.
