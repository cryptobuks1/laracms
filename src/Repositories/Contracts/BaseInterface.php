<?php

namespace Laracms\Repositories\Contracts;

interface BaseInterface
{
    public function all();
    public function get();
    public function paginate($limit);
    public function find($id);
    public function findOrFail($id);
    public function create($params);
    public function createGetId($params);
    public function update($id, $params);
    public function updateOrCreate($params, $conditions);
    public function destroy($ids);
}