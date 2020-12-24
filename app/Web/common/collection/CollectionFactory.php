<?php

declare(strict_types=1);

namespace App\Web\common\collection;

use Exception;
use ReflectionException;

class CollectionFactory
{
    /**
     * @param string $DTOClassName
     * @return CollectionTemplate
     * @throws Exception
     */
    public static function newCollection(string $DTOClassName): CollectionTemplate
    {
        $collection = new CollectionTemplate();
        $collection->setClassName($DTOClassName);
        return $collection;
    }

    /**
     * @param $model
     * @return string
     * @throws ReflectionException
     */
    public static function getObjectFullName($model): string
    {
        return (new \ReflectionClass($model))->getName();
    }

}