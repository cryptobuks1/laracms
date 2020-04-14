<?php
namespace Laracms\Repositories\Eloquents;

use Laracms\Repositories\Contracts\RepositoryInterface;
use Laracms\Repositories\Contracts\PostRepositoryInterface;

class OptionEloquentRepository extends RepositoryInterface implements PostRepositoryInterface
{
    /**
     * Get model
     * 
     * @return string
     */
    public function getModel()
    {
        return Laracms\Models\Option::class;
    }
}
