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
     * Get all data
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->_model->all();
    }

    /**
     * Get all data publish column
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->_model->published()->get();
    }

    /**
     * Pagination
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function paginate($limit)
    {
        return $this->_model->published()->paginate($limit);
    }

    /**
     * Find an item by ID
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function find($ids)
    {
        return $this->_model->published()->find($ids);
    }

    /**
     * Find or Fail
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findOrFail($id)
    {
        return $this->_model->published()->findOrFail($id);
    }

    /**
     * Create item
     * 
     * @return boolean
     */
    public function create($params)
    {
        return $this->_model->create($params);
    }

    /**
     * Create item get ID
     * 
     * @return integer
     */
    public function createGetId($params)
    {
        return $this->_model->insertGetId($params);
    }

    /**
     * Update item
     * 
     * @return boolean
     */
    public function update($id, $params)
    {
        return $this->_model->where('id', $id)->update($params);
    }

    /**
     * Update item
     * 
     * @return boolean
     */
    public function updateOrCreate($params, $conditions)
    {
        return $this->_model->updateOrCreate($params, $conditions);
    }

    /**
     * Destroy item
     * 
     * @return boolean
     */
    public function destroy($ids)
    {
        return $this->_model->destroy($ids);
    }
}