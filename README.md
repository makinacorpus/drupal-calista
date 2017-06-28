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


## Usage

### Philosophy

This module takes the philosophy from SOLID principles, for this to work, you will need to create:

* a Controller
* a DataSource
* a twig template
* possibly an ActionProvider

To demo this, we will replace the user administration from Drupal core.

### Bind with Drupal

```php
/**
 * Implements hook_menu_alter().
 */
function mymodule_menu_alter(&$items) {
  $items['admin/people']['page callback'] = 'sf_dic_page';
  $items['admin/people']['page arguments'] = [AccountController::class . '::accountList'];
}
```
The `AccountController::actionListAction` method will be called when hitting `admin/people`.

### Datasource

A `DefaultAccountDatasource` is available and abstracts the main part of querying the `users` table, but you can add you own sorts for example

```php
<?php

namespace MyModule\Page;

use MakinaCorpus\Drupal\Calista\Datasource\Account\DefaultAccountDatasource;
use MakinaCorpus\Drupal\Calista\Datasource\Query;

/**
 * Account Datasource
 */
class AccountDatasource extends DefaultAccountDatasource
{
    /**
     * Add our own columns
     *
     * @inheritdoc
     */
    public function getSortFields()
    {
        $sortFields = parent::getSortFields();

        $sortFields['m.myfield_value'] = "my field";

        return $sortFields;
    }

    protected function applyFilters(\SelectQueryInterface $select, Query $query)
    {
        $select->leftJoin('field_data_myfield', 'm', 'm.entity_id = u.uid');
    }
}
```

It must be desclared in your `mymodule.services.yml`

```yaml
services:
  mymodule.datasource.account:
    public: true
    shared: false
    class: MyModule\Page\AccountDatasource
    arguments: ["@database", "@entity.manager"]
```

### Template

The template extends `module:calista:views/Page/page.html.twig` which provides the main components for display a table of items. All you have to do is override the columns and rows:

```twig
{% extends 'module:calista:views/Page/page.html.twig' %}

{%- block item -%}
    <tr class="separator">
        <td>{{ item.name }}</td>
        <td>{{ item.myfield.und.0.value }}</td>
        <td>
            <ul>
                {% for role in item.roles %}
                    {% if role != 'authenticated user' %}
                        <li>{{ role }}</li>
                    {% endif %}
                {% endfor %}
            </ul>
        </td>
        <td>{{ item.created|date('d/m/Y H:i') }}</td>
        <td>{{ item.access|date('d/m/Y H:i') }}</td>
        <td>{{ item.status ? 'Yes'|t : 'No'|t }}</td>
        <td class="actions">{{ calista_primary(item) }}</td>
    </tr>
{%- endblock -%}

{% block item_list %}
    <table class="table table-striped table-condensed table-demandes">
        <thead>
        <tr>
            <th>Name</th>
            <th>My Field</th>
            <th>Roles</th>
            <th>Member since</th>
            <th>Last access</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {% for item in items %}
            {{ block('item') }}
        {% else %}
            <tr>
                <td colspan="9">
                    No account to display.
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endblock %}
```

### Action provider

An action provider is not mandatory but it allows to add actions on items. Example for an account:

```php
<?php

namespace MyModule\Action;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\user\User;
use MakinaCorpus\Drupal\Calista\Action\Action;
use MakinaCorpus\Drupal\Calista\Action\ActionProviderInterface;

/**
 * Action provider for accounts
 */
class AccountActionProvider implements ActionProviderInterface
{
    use StringTranslationTrait;

    /**
     * @inheritdoc
     */
    public function getActions($item, $primaryOnly = false, array $groups = [])
    {
        $ret = [];

        $ret[] = new Action($this->t("Edit"), 'user/'.$item->id().'/edit', null,'pencil', 0, true, false);
        $ret[] = new Action($this->t("Delete"), 'user/'.$item->id().'/cancel', null, 'trash', 2, true, false);

        return $ret;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($item)
    {
        return $item instanceof User;
    }
}
```

It must be declared in your `mymodule.services.yml`

```yaml
services:
    mymodule.account_action_provider:
        public: false
        class: MyModule\Action\AccountActionProvider
        tags: [{name: calista.action_provider}]
```
