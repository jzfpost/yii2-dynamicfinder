# yii2-dynamicfinder
Dynamic finder trait for Yii2-framework ActiveRecord models

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jzfpost/yii2-dynamicfinder "*"
```

or add

```
"jzfpost/yii2-dynamicfinder": "*"
```

to the require section of your `composer.json` file.

Usage
-----

### Model

```php
use jzfpost\dynamicfinder\DynamicFinderTrait;

class Customer extends \yii\db\ActiveRecord
{
    use DynamicFinderTrait;
    
your code...
}
```

### Controller

```php
$model = Customer::findOneByEmail($email);  // return Customer::find()->where(['email' => $email])->one();
$customers = Customer::findAllByEmail($email);  // return Customer::find()->where(['email' => $email])->all();
$count = Customer::findCountByEmail($email);  // return Customer::find()->where(['email' => $email])->count();

$username = Customer::findUsernameByEmail($email); // return username value where email=$email;
$updatedAt = Customer::findUpdatedAtByEmailOrUsername($email, $username); // return updated_at value where email=$email or username = $username;
$createdAt = Customer::findCreatedAtByEmailAndUsername($email, $username); // return created_at value where email=$email and username = $username;

$customers = Customer::findByEmail($email) equivalently Customer::findAllByEmail($email);

```

### Syntax
```
findBy<Field>(field_value)
find<Select>By<Field>(field_value)
find<Select>By<Field1>And<Field2>(field_value1, field_value2)
find<Select>By<Field1>Or<Field2>(field_value1, field_value2)
```

where:
>'Select' may by 'All', 'One', 'Count' or one of model attribute. If 'Select' not set, by default return 'All'.

>'Field' is model attribute on where condition.
