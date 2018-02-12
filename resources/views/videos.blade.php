@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default col-md-12">
                <div class="card-header">Videos</div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        @if ($errors->any())
                                <div class="alert alert-danger">
                                        <ul>
                                        @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                        @endforeach
                                        </ul>
                                </div>
                        @endif

                <div class="card-body">
		<a href="/home">< Back</a><BR>
			<div id="video_preview">
				<video width="640" height="480" controls>
			 	<source src="{{$url}}" type="video/mp4">
				</video>
			</div>
			<div id="video_text">
				<B>Title: </B> {{$video->title}}<BR>
				<B>Keywords: </B> {{$keywords}}<BR>
				<B>Location: </B> 
					@if (!empty($location))
						{{$location->location}}
					@endif
			</div>
                </div>
            </div>

	    <div class="card card-default col-md-12">
                <div class="card-body">
			<div class="card-header">Add Keywords</div>
			<form action="/add_keywords" method="post">
                        {{csrf_field()}}

			Keywords (separated by comma): <BR>
                        <input type="text" name="keywords" id="keywords"><BR><BR>
                        <input type="hidden" name="video_id" id="video_id" value="{{$video->id}}">

			<input type="submit" value="Add Keywords" name="submit">



			</form>
                </div>
	    </div>
		@if (empty($location))
	    <div class="card card-default col-md-12">
                <div class="card-body">
			<div class="card-header">Add Location</div>
			<form action="/add_location" method="post">
                        {{csrf_field()}}

			Location: <BR>
                        <input type="text" name="location" id="location"><BR><BR>
                        <input type="hidden" name="video_id" id="video_id" value="{{$video->id}}">

			<input type="submit" value="Add Location" name="submit">



			</form>
                </div>
            </div>
		@endif
        </div>
    </div>
</div>
@endsection
