<?php

namespace Laracms\Repositories\Contracts;

interface MediaInterface
{
    public function folders();
    public function getMedia($limit);
    public function upload($file, $storage_path, $date_folder = false, $file_name = '');
    public function getMediaType($type);
}
