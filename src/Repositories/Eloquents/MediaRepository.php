<?php
namespace Laracms\Repositories\Eloquents;

use Laracms\Repositories\Eloquents\BaseRepository;
use Laracms\Repositories\Contracts\MediaInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    /**
     * Get media
     */
    public function getMedia($limit)
    {
        return $this->_model->orderBy('id', 'desc')->limit($limit)->get();
    }

    /**
     * Upload media
     */
    public function upload($file, $storage_path, $month_folder = false, $file_name = '')
    {
        # Check file size
        $max_size = $file->getMaxFilesize();
        $file_size = $file->getSize();
        if ($file_size > $max_size) {
            return false;
        }

        # Ge storage path
        $storage_path = trim($storage_path, '/');
        $folder = date('Y-m');
        if ($month_folder == true) {
            $path = $storage_path . '/' . $month_folder;
        } else {
            $path = $storage_path;
        }

        # Get file name
        $extension = $file->getClientOriginalExtension();
        $mime_type = $file->getClientMimeType();
        $file_type = $this->getMediaType($extension);
        if ($file_name == '') {
            $slug_name = str_replace('.' . $extension, '', trim($file->getClientOriginalName()));
            $file_name = Str::slug($slug_name);
        } else {
            $file_name = Str::slug(trim($file_name));
        }
        $filename_origin = $file_name;
        $has_file_name = $this->_model->where('name', $file_name)->exists();
        $index = 2;
        while (intval($has_file_name) > 0) {
            $file_name = $filename_origin . '-' . $index;
            $has_file_name = $this->_model->where('name', $file_name)->where('extension', $extension)->exists();
            $index++;
        }

        # Get file path and source
        $file_path = Storage::putFileAs($path, $file, $file_name . '.' . $extension);
        $source = url('/') . Storage::url($file_path);
        if ($file_type == 'image') {
            $url = $source;
        } else {
            $url = url('vendor/laracms/lib/thumbs/' . $file_type . '.png');
        }

        # Get media author
        $author = !Auth::guest() ? Auth::id() : 0;

        # Store data
        $media = [
            'name' => $file_name,
            'extension' => $extension,
            'size' => $file_size,
            'mime_type' => $mime_type,
            'type' => $file_type,
            'url' => $url,
            'source' => $source,
            'alt' => $file_name,
            'description' => '',
            'location' => $file_path,
            'folder' => $month_folder,
            'author' => $author,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $media_id = $this->createGetId($media);
        $media['media_id'] = $media_id;
        return $media;
    }

    /**
     * Get media type
     */
    public function getMediaType($type)
    {
        // image, audio, video, xml, json
        $image = ['JPE','JPEG','JPG','PNG', 'GIF', 'SVG', 'ICO', 'WEBP'];
        $video = ['WEBM', 'MKV', 'FLV', 'VOB', 'OGV', 'OGG', 'DRC', 'GIFV', 'MNG', 'AVI', 'MOV', 'QT', 'WMV', 'YUV', 'RM', 'RMVB', 'ASF', 'AMV', 'MP4', 'M4P', 'M4V', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'SVI', '3GP', '3G2', 'MXF', 'ROQ', 'NSV', 'F4V', 'F4P', 'F4A', 'F4B'];
        $audio = ['3GP','AA','AAC','AAX','ACT','AIFF','AMR','APE','AU','AWB','DCT','DSS','DVF','FLAC','GSM','IKLAX','IVS','M4A','M4B','M4P','MMF','MP3','MPC','MSV','OGG','OPUS','RA','RAW','SLN','TTA','VOX','WAV','WMA','WV','WEBM'];
        $xml = ['XML', 'XSD', 'DTD'];
        $json = ['JSON'];
        $res = 'other';
        if(in_array(strtoupper($type), $image)){
            $res = 'image';
        }
        if(in_array(strtoupper($type), $audio)){
            $res = 'audio';
        }
        if(in_array(strtoupper($type), $video)){
            $res = 'video';
        }
        if(in_array(strtoupper($type), $xml)){
            $res = 'xml';
        }
        return $res;
    }
}
