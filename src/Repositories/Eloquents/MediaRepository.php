<?php
namespace Laracms\Repositories\Eloquents;

use Laracms\Repositories\Eloquents\BaseRepository;
use Laracms\Repositories\Contracts\MediaInterface;

class MediaRepository extends BaseRepository implements MediaInterface
{
    /**
     * Get model
     * 
     * @return string
     */
    public function getModel()
    {
        return \Laracms\Models\Media::class;
    }

    /**
     * Get folder list
     */
    public function folders(){
        return $this->_model->orderBy('folder', 'DESC')->pluck('folder')->toArray();
    }
}
