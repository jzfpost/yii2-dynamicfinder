<?php
namespace jzfpost\dynamicfinder;

use yii\helpers\Inflector;

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
    * @return ActiveRecord|mixed|null
    */
    protected function _dynamicFinder($name, $params)
    {
        $name = Inflector::underscore($name);

        if (preg_match('/^find_by_(\w+)/', $name, $match)) {
            $select = 'all';
            $fields = $match[1];
        } elseif (preg_match('/^find_(\w+)_by_(\w+)/', $name, $match)) {
            $select = $match[1];
            $fields = $match[2];
        }

        if (preg_match('/^(\w+)_and_(\w+)/', $fields, $match)) {
            $conditions = [
                'and',
                is_array($params[0]) ? $params[0] : [$match[1] => $params[0]],
                is_array($params[1]) ? $params[1] : [$match[2] => $params[1]]
            ];
        } elseif (preg_match('/^(\w+)_or_(\w+)/', $fields, $match)) {
            $conditions = [
                'or',
                is_array($params[0]) ? $params[0] : [$match[1] => $params[0]],
                is_array($params[1]) ? $params[1] : [$match[2] => $params[1]]
            ];
        } else {
            $conditions = is_array($params[0]) ? $params[0] : [$fields => $params[0]];
        }

        $attributes = (new self)->getAttributes();
        if(array_key_exists($select, $attributes)) {
            if ( ($result = static::find()->select($select)->where($conditions)->limit(1)->one()) !== null ) {
                return $result->$select;
            }
        } else {
            return static::find()->where($conditions)->$select();
        }
        return null;
    }
}
