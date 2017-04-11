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
$model = Customer::findOneByEmail($email);  // return Customer::find()->where('email' => $email)->one();
$model = Customer::findAllByEmail($email);  // return Customer::find()->where('email' => $email)->all();
$model = Customer::findCountByEmail($email);  // return Customer::find()->where('email' => $email)->count();

$model = Customer::findUsernameByEmail($email); // return username value where email=$email;
$model = Customer::findUpdatedAtByEmailOrUsername($email, $username); // return updated_at value where email=$email or username = $username;
$model = Customer::findCreatedAtByEmailAndUsername($email, $username); // return created_at value where email=$email and username = $username;

$model = Customer::findByEmail($email) equivalently Customer::findAllByEmail($email);

```

### syntax
```php
find<Select>By<Field>(field_value)
find<Select>By<Field1>And<Field2>(field_value1, field_value2)
find<Select>By<Field1>Or<Field2>(field_value1, field_value2)
```
where:
>'Select' may by 'All', 'One', 'Count' or select condition. If 'Select' not set, by default return 'All'.

>'Field' is model attribute on where condition.
