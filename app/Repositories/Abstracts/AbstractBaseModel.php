<?php

namespace App\Repositories\Abstracts;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseModel
{
    protected $model;

    public function __construct()
    {
        $this->model = app($this->model);
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}