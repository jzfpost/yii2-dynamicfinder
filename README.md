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
    
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
your code...
}
```

### The Controller

```php
$model = Customer::findOneByEmail($email);  // return Customer::findOne('email' => $email);
$model = Customer::findOneByEmail($email, STATUS_ACTIVE);  // return Customer::findOne('email' => $email, 'STATUS_ACTIVE' => 1);
$model = Customer::findAllByEmail($email);  // return Customer::findAll('email' => $email);
$model = Customer::findCountByEmail($email);  // return Customer::findAll('email' => $email)->count();
$model = Customer::findUsernameByEmail($email); // return Customer::find()->select('username')->andWhere('email' => $email)->first();
$model = Customer::findUpdatedAtByEmail($email); // return Customer::find()->select('updated_at')->andWhere('email' => $email)->first();

$model = Customer::findByEmail($email) eq $model = Customer::findAllByEmail($email);

```

### syntax
```php
find<Select>By<Field>(field_value, [class_constant])
```
where:
>'Select' may by 'All', 'One', 'Count' or select condition. If 'Select' not set, by default return 'All'.

>'Field' is model attribute on where condition.
