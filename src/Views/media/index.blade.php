@extends('laracms::app')
@section('title', 'Thư viện hình ảnh')
@section('header')
    <a id="add_new_media_button" href="javascript:void(0);" class="btn btn-xs btn-primary text-white">Tải lên</a>
@stop
@section('content')
    <div class="media_area">
        <div class="media_upload" id="drag_drop_upload">
            <form action="{{ route('admin.media.upload') }}" method="POST" class="dropzone" id="dropzone_form" enctype="multipart/form-data">
                @csrf
                <div class="drop_zone_text">Thả hoặc chọn tệp để tải lên</div>
                <div class="or">hoặc</div>
                <input type="file" accept="image/*,audio/*,video/*,.json,.xml,.xsd,.dtd" name="file[]" id="media_input" multiple>
                <button type="button" class="btn btn-secondary" id="btn_select_files">Chọn tệp</button>
                <p>Kích thước tối đa: {{ @ini_get('upload_max_filesize') }}</p>
            </form>
        </div>
        <div class="media_filter">
            <div class="media_filter_default active">
                <div class="form-inline">
                    <div class="form-group">
                        <select id="filter_by_type" class="form-control">
                            <option value="all">Tất cả</option>
                            <option value="image">Images</option>
                            <option value="audio">Audio</option>
                            <option value="video">Video</option>
                            <option value="json">JSON files</option>
                            <option value="xml">XML files</option>
                        </select>
                    </div>
                    <div class="form-group ml-2">
                        <select id="filter_by_date" class="form-control">
                            <option value="all">Tất cả các ngày</option>
                            @foreach($filter as $value)
                            <option value="{{ $value }}">{{ date('d-m-Y', strtotime($value)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group ml-2">
                        <button type="button" id="media_multi_select" class="btn btn-secondary">Chọn nhiều</button>
                    </div>
                    <div class="form-group ml-auto">
                        <input type="text" id="filter_by_search" class="form-control" placeholder="Tìm kiếm...">
                    </div>
                </div>
            </div>
            <div class="media_filter_select">
                <div class="form-inline">
                    <div class="form-group">
                        <button type="button" id="cancel_multi_select" class="btn btn-secondary">Bỏ chọn</button>
                    </div>
                    <div class="form-group ml-2">
                        <button type="button" id="delete_multi_select" class="btn btn-primary">Xóa các mục đã chọn</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="media_show">
            <div class="form-row upload-panel-files" id="upload_image_preview">
                @if(count($media) > 0)
                @foreach($media as $value)
                <div class="col-3 col-md-2 col-lg-1 upload_file_preview">
                    <div class="media_file" media-id="{{ $value->id }}" title="{{ $value->name . '.' . $value->extension }}">
                        <div class="media_file_selected"><i class="fas fa-check"></i></div>
                        <div class="img_wrapper">
                            <div class="img_show">
                                <div class="img_thumbnail">
                                    <div class="img_centered">
                                        <img src="{{ $value->url }}" class="{{ $value->style }}" alt="{{ $value->alt }}" draggable="false">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-md-12"><div class="media_no_result">Không có dữ liệu.</div></div>
                @endif
                <span class="upload-note"></span>
            </div>
        </div>
    </div>
    <div class="modal in" id="media_modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg modal_fullwidth" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thư viện hình ảnh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-8 col_media_info_left">
                            <div class="media_file_image">
                                <img src="" alt="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-5 col-md-4 col_media_info_right">
                            <div class="media_info">
                                <div class="media_info_top">
                                    <div><b>Tên: </b> <span class="info_media_name"></span></div>
                                    <div><b>kích thước: </b> <span class="info_media_size"></span></div>
                                    <div><b>Kiểu: </b> <span class="info_media_type"></span></div>
                                </div>
                                <div class="media_info_bottom">
                                    <input type="hidden" id="input_media_id">
                                    <div class="form-group">
                                        <label>Đường dẫn</label>
                                        <input type="text" disabled="disabled" class="form-control form-control-sm input_media_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Thẻ alt</label>
                                        <input type="text" class="form-control form-control-sm input_media_alt">
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả</label>
                                        <textarea class="form-control form-control-sm input_media_description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <span class="float-left" id="save_media_result"><i class="fas fa-check"></i> Lưu thành công</span>
                                        <a href="javascript:void(0)" class="float-right" id="delete_media" close-modal="true">Xóa vĩnh viễn</a>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="delete_media_url" value="{{ route('admin.media.delete_multiple') }}">
@stop
@push('css')
    <link rel="stylesheet" text="text/css" href="{{ asset('vendor/laracms/lib/media.css') }}">
@endpush
@push('js')
<script type="text/javascript" src="{{ asset('vendor/laracms/lib/dmuploader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/laracms/lib/media.js') }}"></script>
@endpush