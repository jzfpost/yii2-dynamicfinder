<?php
namespace jzfpost\dynamicFinder;

use yii\helpers\Inflector;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;

trait DynamicFinderTrait
{
    /**
    * Never call this method.
    */
    public static function __callStatic($name, array $params)
    {
        if (preg_match('/^find(?:\w+)?By/', $name) > 0) {
            return self::_dynamicFinder($name, $params);
        }
    }

    /**
    * This method return model by findBy<field> or find<select>By<field> calls
    * @param string $name. The name of called findBy* method.
    * @param array $params. The arguments passed to the findBy* method.
    */
    protected function _dynamicFinder($name, $params)
    {
        $modelName = static::className();
        $model = new $modelName;
        $attributes = $model->getAttributes();

        $name = Inflector::underscore($name); //findAuthorByBook => find_author_by_book

        if (preg_match('/^find_by_([\w]+)/', $name, $match)) {
            $select = 'all';
            $field = $match[1];
        } elseif (preg_match('/^find_([\w]+)_by_([\w]+)/', $name, $match)) {
            $select = $match[1];
            $field = $match[2];
        } else {
            throw new UnknownMethodException('Calling unknown method: ' . $modelName . "::$name()");
        }

        if (!array_key_exists($field, $attributes)) {
            throw new UnknownPropertyException('Calling unknown Property: ' . $modelName . "::$field");
        }
        $conditions[$field] = $params[0];
        if (isset($params[1])) {
            $conditions[$params[1]] = constant('self::'.$params[1]);
        }

        if ($select == 'all') {
            return $modelName::findAll($conditions);
        } elseif ($select == 'one') {
            return $modelName::findOne($conditions);
        } elseif ($select == 'count') {
            return $modelName::find()->andWhere($conditions)->count();
        } elseif(array_key_exists($select, $attributes)) {
            return $modelName::find()->select($select)->andWhere($conditions)->all();
        } else {
            throw new UnknownPropertyException('Calling unknown Property: ' . $modelName . "::$select");
        }
    }
}
