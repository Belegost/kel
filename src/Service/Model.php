<?php

namespace App\Service;

use App\Traits\DoctrineAwareTrait;

/**
 * Class Model
 */
class Model
{
    use DoctrineAwareTrait;

    /**
     * Create model instance by name
     *
     * @param string $name full class name or relative to App\Model
     * @param array $options
     *
     * @return Model|null
     */
    public function factory(string $name, array $options = []): ?Model
    {
        $name = str_replace('App\\Model\\', '', $name);
        $class = 'App\\Model\\' . trim($name, '\\');

        if (class_exists($class)) {
            /**
             * @var Model $model
             */
            $model = new $class();
            $model->setDoctrine($this->getDoctrine());
            $model->populate($options);

            return $model;
        }

        return null;
    }

    /**
     * Populate existing property
     *
     * @param array $options
     */
    protected function populate(array $options): void
    {
        foreach ($options as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }
    }
}
