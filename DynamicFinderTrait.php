<?php
namespace jzfpost\dynamicfinder;

use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;

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

        $name = Inflector::underscore($name);

        if (preg_match('/^find_by_([\w]+)/', $name, $match)) {
            $select = 'all';
            $fields = $match[1];
        } elseif (preg_match('/^find_([\w]+)_by_([\w]+)/', $name, $match)) {
            $select = $match[1];
            $fields = $match[2];
        }

        if (preg_match('/^([\w]+)_and_([\w]+)/', $fields, $match)) {
            $conditions = [
                'and',
                [$match[1] => $params[0]],
                [$match[2] => $params[1]]
            ];
        } elseif (preg_match('/^([\w]+)_or_([\w]+)/', $fields, $match)) {
            $conditions = [
                'or',
                [$match[1] => $params[0]],
                [$match[2] => $params[1]]
            ];
        } else {
            $conditions = [$fields => $params[0]];
        }

        if(array_key_exists($select, $attributes)) {
            return $modelName::find()->select($select)->where($conditions)->one()->$select;
        } else {
            if (($result = $modelName::find()->where($conditions)->$select()) !== null) {
                return $result;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
    }
}
