# yii2-dynamicFinder
Dynamic finder trait for Yii2-framework

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jzfpost/yii2-dynamicFinder "*"
```

or add

```
"jzfpost/yii2-dynamicFinder": "*"
```

to the require section of your `composer.json` file.

Usage
-----

### The Model

```php
class Customer extends \yii\db\ActiveRecord
{
    use DynamicFinderTrait;
    
your code...
}
```

### The Controller

```php
$model = Customer::findOneByEmail($email);  // return Customer::findOne('email' => $email);
$model = Customer::findAllByEmail($email);  // return Customer::findAll('email' => $email);
$model = Customer::findCountByEmail($email);  // return count where email=$email;
$model = Customer::findUsernameByEmail($email); // return username value where email=$email;
$model = Customer::findUpdatedAtByEmail($email); // return updated_at value where email=$email;

$model = Customer::findByEmail($email) eq Customer::findAllByEmail($email);

```

### syntax
```php
find<Select>By<Field>(field_value)
```
where:
>'Select' may by 'All', 'One', 'Count' or select condition. If 'Select' not set, by default return 'All'.

>'Field' is model attribute on where condition.
