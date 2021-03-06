<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Repository
{
    public function __call($name, $args)
    {
        return $this->getModel()->__call($name, $args);
    }
    public function find($id)
    {
        return $this->findBy('id', $id);
    }
    public function findOrFail($id)
    {
        return $this->findByOrFail('id', $id);
    }
    public function findBy($field, $value)
    {
        return $this->findWhere([
            $field => $value
        ]);
    }
    public function findByOrFail($field, $value)
    {
        return $this->findWhereOrFail([
            $field => $value
        ]);
    }
    public function findWhere(...$conditions)
    {
        return $this->getModel()->where(...$conditions)->first();
    }
    public function findWhereOrFail(...$conditions)
    {
        $result = $this->findWhere(...$conditions);

        if (empty($result)) {
            throw (new ModelNotFoundException)->setModel(get_class($this->getModel()), ...$conditions);
        }

        return $result;
    }
    public function getModel()
    {
        return app($this->model);
    }
}
