# Redirect Module for Yii2

### To access the module, you need to add this to your application configuration:

```php
'on beforeRequest' => function () {
        \scorchsoft\scorchcore\modules\redirect\models\Redirect::checkRedirect();
},
'modules' => [
    'redirect' => [
        'class' => 'scorchsoft\scorchcore\modules\redirect\Redirect',
    ],
],
```

Also add in params file to appear in admin menu

```php
['controller' => 'redirect', 'title' => 'Redirects', 'icon' => 'fa fa-bars'],
```
