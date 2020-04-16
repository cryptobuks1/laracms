@extends('laracms::app')
@section('title', 'Thư viện hình ảnh')
@section('header')
    <a id="add_new_media_button" href="javascript:void(0);" class="btn btn-xs btn-primary text-white">Tải lên</a>
@stop
@section('content')
<form action="{{ route('admin.media.upload') }}" method="post" enctype="multipart/form-data">
    @csrf
	<input type="file" name="file">
	<button type="submit">Submit</button>
</form>
@stop
