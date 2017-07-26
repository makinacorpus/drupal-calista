# Calista - Advanced dashboard/list API for Drupal

This initial version is a raw export of the *ucms_dashboard* from the
https://github.com/makinacorpus/drupal-ucms Drupal module suite. Only namespaces
have been changed, and a few utility functions moved from the *ucms_contrib*
module.

It should be stable enough to use.

This is licensed under the GPL-2 licence. This package includes third-party
libraries, licences provided in the Resources/meta directory.

## Installation

It depends heavily on makinacorpus/drupal-sf-dic, the easiest way to install is:

```
composer install makinacorpus/drupal-calista
```



## Configuration

### Runtime configuration

#### Enable top toolbar

```php
$conf['calista_context_pane_enable'] = true;
```


#### Enable context pane

```php
$conf['calista_context_pane_enable'] = true;
```


#### Enable admin pages breadcrumb alteration

This is a very specific setting for usage with UCMS.

```php
$conf['calista_breadcrumb_alter'] = true;
```


### Display configuration

#### Disable custom CSS

If you wish to embed this module's CSS or custom LESS into your own custom
theme, you might wish to disable CSS loading:

```php
$conf['calista_disable_css'] = true;
```


#### Drupal seven theme fixes

By setting this to ``true``, seven fixes will always be included:

```php
$conf['calista_seven_force'] = true;
```

By setting it to ``false``, the will be always dropped.

By removing the variable or setting it to ``null`` seven admin theme will be
automatically detected at runtime and fixes will be loaded if necessary.


### Usage

For extensive documentation, please refer to the [Calista API documentation](https://github.com/makinacorpus/calista)

