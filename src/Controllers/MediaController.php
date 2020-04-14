<?php
namespace Laracms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Laracms\Repositories\Contracts\MediaInterface;

class MediaController extends Controller
{
    /**
     * @var MediaInterface
     */
    protected $mediaRepository;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(MediaInterface $mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
    }

    function index()
    {
        $data = [
            'media' => $this->mediaRepository->paginate(120),
            'filter' => $this->mediaRepository->folders()
        ];
        return view('laracms::media.index', $data);
    }

    function upload(Request $request){
        $file = $request->file('file');
        $media = $this->media_upload($file, 'public/uploads', true);
        return $media;
    }

    function lazy(Request $request){
        $offset = $request->offset;
        $limit = $request->limit;
        $media_type = $request->media_type;
        $media_date = $request->media_date;
        $media_search = $request->media_search;
        $data = $this->get_media_lazy($media_type, $media_date, $media_search, $offset, $limit);
        return response()->json($data);
    }

    function filter(Request $request){
        $media_type = $request->media_type;
        $media_date = $request->media_date;
        $media_search = $request->media_search;
        $data = $this->get_media_filter($media_type, $media_date, $media_search);
        return $data;
    }

    function single(Request $request){
        $media_id = $request->media_id;
        $data = $this->get_media_by_id($media_id);
        return response()->json($data);
    }

    function update(Request $request){
        $media_id = $request->media_id;
        $media_alt = $request->media_alt;
        $media_description = $request->media_description;
        $media = ['media_alt' => $media_alt, 'media_description' => $media_description];
        $result = $this->update_media($media_id, $media);
        if($result === true){
            return 'true';
        }else{
            return 'false';
        }
    }

    function delete(Request $request){
        $media_id = $request->media_id;
        $result = $this->delete_media($media_id);
        if($result === true){
            return 'true';
        }else{
            return 'false';
        }
    }

    function deleteMultiple(Request $request){
        $media_ids = $request->media_ids;
        if(count($media_ids) > 0){
            $flag = 'false';
            foreach($media_ids as $media_id){
                $result = $this->delete_media($media_id);
                if($result === true){
                    $flag = 'true';
                }
            }
            return $flag;
        }else{
            return 'false';
        }
    }

    protected function get_media_item($media_location, $media_name, $media_extension){
        $data = Media::where('media_name', $media_name)->where('media_extension', $media_extension)->where('media_location', $media_location)->first();
        return $data;
    }
    
    protected function media_upload($file, $storage_path, $use_date_folder = false, $file_name = ''){
        $max_size = $file->getMaxFilesize();
        $file_size = $file->getClientSize();
        if($file_size > $max_size){
            return false;
        }else{
            $storage_path = trim($storage_path, '/');
            $date_folder = date('Y-m-d');
            if($use_date_folder == true){
                $path = $storage_path . '/' . $date_folder;
            }else{
                $path = $storage_path;
            }
            $extension = $file->getClientOriginalExtension();
            $mime_type = $file->getClientMimeType();
            $file_type = $this->get_media_type($extension);
            if($file_name == ''){
                $slug_name = str_replace('.' . $extension, '', trim($file->getClientOriginalName()));
                $file_name = str_slug($slug_name);
            }else{
                $file_name = str_slug(trim($file_name));
            }
            $filename_origin = $file_name;
            $get_file_name = Media::where('media_name', $file_name)->count();
            $file_index = 2;
            while($get_file_name > 0){
                $file_name = $filename_origin . '-' . $file_index;
                $get_file_name = Media::where('media_name', $file_name)->where('media_extension', $extension)->count();
                $file_index++;
            }
            $width = 0;
            $height = 0;
            $media_name = $file_name;
            if($file_type == 'image' || $file_type == 'icon'){
                list($width, $height) = getimagesize($file);
                if($width < $height){
                    $media_style = 'portrait';
                }else{
                    $media_style = 'landscape';
                }
                $file_name = $file_name . '_size_' . $width . 'x' . $height;
            }
            $file_path = Storage::putFileAs($path, $file, $file_name . '.' . $extension);
            $media_source = url('/') . Storage::url($file_path);
            $media_style = 'landscape';
            $media_path = str_replace($file_name . '.' . $extension, '', $media_source);
            if($file_type == 'image' || $file_type == 'icon'){
                $media_url = $media_source;
            }else{
                $media_url = url('/contents/images/media_thumbs/' . $file_type . '.png');
            }
            $media_author = !Auth::guest() ? Auth::user()->id : 0;
            $media = [
                'media_name' => $media_name,
                'media_extension' => $extension,
                'media_width' => $width,
                'media_height' => $height,
                'media_style' => $media_style,
                'media_size' => $file_size,
                'mime_type' => $mime_type,
                'media_type' => $file_type,
                'media_source' => $media_source,
                'media_url' => $media_url,
                'media_alt' => $media_name,
                'media_description' => '',
                'media_path' => $media_path,
                'media_location' => $path,
                'media_folder' => $date_folder,
                'media_author' => $media_author,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $media_id = Media::insertGetId($media);
            $media['media_id'] = $media_id;
            return $media;
        }
    }

    protected function get_media($limit = 100){
        if($limit == 0){
            $data = Media::orderBy('media_id', 'DESC')->get();
        }else{
            $data = Media::orderBy('media_id', 'DESC')->offset(0)->limit($limit)->get();
        }
        return $data;
    }

    protected function media_date_filter(){
        $data = Media::distinct()->select('media_folder')->orderBy('media_folder', 'DESC')->get();
        return $data;
    }

    protected function get_media_lazy($media_type, $media_date, $media_search, $offset, $limit){
        $type_operator = '=';
        if($media_type == 'all'){
            $media_type = '';
            $type_operator = '!=';
        }
        $date_operator = '=';
        if($media_date == 'all'){
            $media_date = '';
            $date_operator = '!=';
        }
        $search_operator = 'like';
        if($media_search == ''){
            $search_operator = '!=';
        }
        $data = Media::where('media_type', $type_operator, $media_type)->where('media_folder', $date_operator, $media_date)->where('media_name', $search_operator, '%' . $media_search . '%')->orderBy('media_id', 'DESC')->offset($offset)->limit($limit)->get();
        return $data;
    }

    protected function get_media_filter($media_type, $media_date, $media_search, $limit = 100){
        $type_operator = '=';
        if($media_type == 'all'){
            $media_type = '';
            $type_operator = '!=';
        }
        $date_operator = '=';
        if($media_date == 'all'){
            $media_date = '';
            $date_operator = '!=';
        }
        $search_operator = 'like';
        if($media_search == ''){
            $search_operator = '!=';
        }
        $data = Media::where('media_type', $type_operator, $media_type)->where('media_folder', $date_operator, $media_date)->where('media_name', $search_operator, '%' . $media_search . '%')->orderBy('media_id', 'DESC')->offset(0)->limit($limit)->get();
        return $data;
    }

    protected function get_media_by_id($media_id){
        $data = Media::where('media_id', $media_id)->first();
        return $data;
    }

    protected function get_media_by_name($media_name){
        $data = Media::where('media_name', $media_name)->first();
        return $data;
    }

    protected function update_media($media_id, $media){
        $get_media = Media::where('media_id', $media_id)->first();
        if(User::find(Auth::id())->has_role('admin') || $get_media->media_author == Auth::id()){
            Media::where('media_id', $media_id)->update($media);
            return true;
        }else{
            return false;
        }
    }

    protected function delete_media($media_id){
        $get_media = Media::where('media_id', $media_id)->first();
        if(User::find(Auth::id())->has_role('admin') || $get_media->media_author == Auth::id()){
            $origin_path = url('/storage/');
            $media_path = str_replace($origin_path, '', $get_media->media_source);
            $delete_path = '/public/' . $media_path;
            Storage::delete($delete_path);
            Media::where('media_id', $media_id)->delete();
            return true;
        }else{
            return false;
        }
    }

    protected function get_media_type($type){
        // image, audio, video, document, other
        $image = ['JPE','JPEG','JPG','PNG', 'GIF', 'SVG', 'ICO'];
        $icon = ['ICO'];
        $video = ['WEBM', 'MKV', 'FLV', 'VOB', 'OGV', 'OGG', 'DRC', 'GIFV', 'MNG', 'AVI', 'MOV', 'QT', 'WMV', 'YUV', 'RM', 'RMVB', 'ASF', 'AMV', 'MP4', 'M4P', 'M4V', 'MPG', 'MP2', 'MPEG', 'MPE', 'MPV', 'SVI', '3GP', '3G2', 'MXF', 'ROQ', 'NSV', 'F4V', 'F4P', 'F4A', 'F4B'];
        $audio = ['3GP','AA','AAC','AAX','ACT','AIFF','AMR','APE','AU','AWB','DCT','DSS','DVF','FLAC','GSM','IKLAX','IVS','M4A','M4B','M4P','MMF','MP3','MPC','MSV','OGG','OPUS','RA','RAW','SLN','TTA','VOX','WAV','WMA','WV','WEBM'];
        $document = ['DOC', 'DOCX', 'XLS', 'XLSX', 'PDF', 'HTM', 'HTML', 'TXT'];
        $file = ['ANI','BMP','CAL','CGM','FAX','JBG','IMG','MAC','PBM','PCD','PCX','PCT','PGM','PPM','PSD','RAS','TGA','TIFF','WMF','AI'];
        $compress = ['RAR','ZIP','GZIP'];
        $javascript = ['JS'];
        $css = ['CSS'];
        $sql = ['SQL'];
        $xml = ['XML', 'XSD', 'DTD'];
        $res = 'other';
        if(in_array(strtoupper($type), $icon)){
            $res = 'icon';
        }
        if(in_array(strtoupper($type), $image)){
            $res = 'image';
        }
        if(in_array(strtoupper($type), $audio)){
            $res = 'audio';
        }
        if(in_array(strtoupper($type), $video)){
            $res = 'video';
        }
        if(in_array(strtoupper($type), $document)){
            $res = 'document';
        }
        if(in_array(strtoupper($type), $file)){
            $res = 'file';
        }
        if(in_array(strtoupper($type), $compress)){
            $res = 'compress';
        }
        if(in_array(strtoupper($type), $javascript)){
            $res = 'javascript';
        }
        if(in_array(strtoupper($type), $css)){
            $res = 'css';
        }
        if(in_array(strtoupper($type), $sql)){
            $res = 'sql';
        }
        if(in_array(strtoupper($type), $xml)){
            $res = 'xml';
        }
        return $res;
    }
}