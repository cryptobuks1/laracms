@extends('laracms::app')
@section('title', 'Cài đặt chung')
@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Thông tin trang</h3>
        </div>
        <form action="{{ route('admin.settings.update') }}" method="post">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tên website</label>
                            <input type="text" name="setting[site_name]" value="{{ @$site_name }}" class="form-control" placeholder="Tên website">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Khẩu hiệu</label>
                            <input type="text" name="setting[site_description]" value="{{ @$site_description }}" class="form-control" placeholder="Khẩu hiệu">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Địa chỉ email</label>
                            <input type="email" name="setting[site_email]" value="{{ @$site_email }}" class="form-control" placeholder="Địa chỉ email">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="setting[site_phone]" value="{{ @$site_phone }}" class="form-control" placeholder="Số điện thoại">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
@stop
@push('css')

@endpush
@push('js')

@endpush