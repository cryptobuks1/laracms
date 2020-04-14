<?php
namespace Laracms\Repositories\Eloquents;

use Laracms\Repositories\Eloquents\BaseRepository;
use Laracms\Repositories\Contracts\OptionInterface;

class OptionRepository extends BaseRepository implements OptionInterface
{
    /**
     * Get model
     * 
     * @return string
     */
    public function getModel()
    {
        return \Laracms\Models\Option::class;
    }

    /**
     * find item by name
     */
    public function findByName($name)
    {
        return $this->_model->where('option_name', $name)->value('option_value');
    }

    /**
     * Find data by name
     */
    public function findByNames($names)
    {
        $data_name = [];
        if (count((array) $names) > 0) {
            foreach ($names as $value) {
                $data_name[$value] = '';
            }
        }
        $data_option = $this->_model->whereIn('option_name', $names)->pluck('option_value', 'option_name')->toArray();
        return !is_null($data_option) && !is_null($data_name) ? array_merge($data_name, $data_option) : null;
    }
}
