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

    /**
     * Get list media
     */
    public function index()
    {
        $data = [
            'media' => $this->mediaRepository->getMedia(120),
            'filter' => $this->mediaRepository->folders()
        ];
        return view('laracms::media.index', $data);
    }

    /**
     * Upload media
     */
    public function upload(Request $request){
        $file = $request->file('file');
        $media = $this->mediaRepository->upload($file, 'public/uploads', true);
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
}