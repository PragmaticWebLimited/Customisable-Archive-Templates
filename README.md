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

TODO: how to use it.


## Development Process

This plugin mostly follows the standard [Git Flow](http://jeffkreeftmeijer.com/2010/why-arent-you-using-git-flow/) model.

### Workflow
Development happens in feature branches, which are merged into `develop`, and then eventually merged into `trunk` for deployment to production. When making changes, a feature branch should be created that branches from `develop` (and the corresponding pull request should use `develop` as the target branch).

### Scripts and Tooling
* `composer run lint:phpcs` -- checks PHP files for compatibility with the WordPress-VIP-Go standards.
* `composer run lint:phpcompat` -- checks PHP files for compatibility with PHP 7.3.
