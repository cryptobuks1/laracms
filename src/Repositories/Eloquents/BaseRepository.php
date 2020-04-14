<?php

namespace Laracms\Repositories\Eloquents;

use Laracms\Repositories\Contracts\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_model;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model
     * 
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     * 
     * @return void
     */
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function get() {
        return $this->_model->published()->get();
    }
}