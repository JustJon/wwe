@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default card-left col-md-5">
                <div class="card-header">Videos</div>

                <div class="card-body">
		@if (count($vids) == 0)
			No videos to display<BR><BR>
			Why not try uploading one?
		@else 
			@foreach ($vids as $vid)
			<a href="video/{{$vid['id']}}">{{$vid['title'] }}</a><BR>
			@endforeach
		@endif
                </div>
            </div>

	<div class="card card-default card-left col-md-6">
                <div class="card-header">Upload Video</div>

                <div class="card-body">
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

		    <form action="upload" method="post" enctype="multipart/form-data">
			{{csrf_field()}}
			Video file: <BR> 
			<input type="file" name="fileToUpload" id="fileToUpload">
			<BR><BR>
			Video title: <BR>
			<input type="text" name="title" id="title" maxlength="80">

			<hr>
			Metadata (optional):<BR><BR>
			Keywords (separated by comma): <BR>
			<input type="text" name="keywords" id="keywords"><BR><BR>

			Location: <BR>
			<input type="text" name="location" id="location">

			<BR><BR><BR>
			<input type="submit" value="Upload Video" name="submit">
			
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
