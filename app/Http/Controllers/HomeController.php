<?php

namespace wwe\Http\Controllers;

use Illuminate\Http\Request;
use wwe\Videos;
use wwe\keywords;
use wwe\locations;
use wwe\video_locations;
use wwe\video_keywords;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    	public function index(Request $request)
    	{

		$vids = Videos::all();

        	return view('home', compact('vids'));
    	}


	public function upload(Request $request) 
	{
		//dd(request()->all());
		$validator = $request->validate([
               		'fileToUpload'    => 'required|max:5000|mimes:mp4,mp4s,m4v',  
                	'title' => 'required',
                	'location' => 'nullable',
                	'keywords' => 'nullable',
                ]);


		$file = $request->file('fileToUpload');
		$filename = $file->store('file');

		$insertId = Videos::insertGetId([
			'title' => $request->input('title'),
			'filename' => $filename
		]);

		if ($request->input('keywords') !== NULL)
		{
			$this->createKeywords($insertId, $request->input('keywords'));
		}

		if ($request->input('location') !== NULL)
		{
			$this->createLocation($insertId, $request->input('location'));
		}

		return redirect('home')->with('status', 'File successfully uploaded');

	}

	private function createKeywords($video_id, $keywords)
	{
		$keyword_list=explode(',', $keywords);
		foreach ($keyword_list as $keyword) {
			$key = keywords::where('keyword', '=', $keyword)->first();
			if (!empty($key->id)) {
				$key_id = $key['id'];
			} else {
                		$key_id = keywords::insertGetId([
                        		'keyword' => $keyword
                		]);
			}
			$new = video_keywords::insert([
					'video_id' => $video_id,
					'keywords_id' => $key_id
			]);
		}
		
		return;
	}

	private function createLocation($video_id, $location)
	{
		$loc = locations::where('location', '=', $location)->first();
		if (!empty($loc->id)) {
			$loc_id = $loc['id'];
		} else {
			$loc_id = locations::insertGetId([
				'location' => $location
			]);     
		}
		$new = video_locations::insert([
			'video_id' => $video_id,
			'locations_id' => $loc_id
		]);

                return;

	}


	public function video($video_id) 
	{
		$video = Videos::where('videos.id', '=', $video_id)->first();
		$keyword_list = Video_Keywords::where('video_keywords.video_id', '=', $video_id)
			->leftjoin('keywords', 'keywords.id', '=', 'video_keywords.keywords_id')
			->get();
		$location = Video_Locations::where('video_locations.video_id', '=', $video_id)
			->leftjoin('locations', 'locations.id', '=', 'video_locations.locations_id')
			->first();
		if (empty($video)) {
			return redirect('/home');
		}

		$url = Storage::url($video->filename);
		$keywords = '';
		foreach ($keyword_list as $keyword) {
			$keywords .= $keyword->keyword.', ';
		}
		$keywords = substr($keywords, 0,  -2);

		return view('videos', compact('video', 'keywords', 'location', 'url'));
	}


	public function addKeywords(Request $request)
	{
                $validator = $request->validate([
                        'keywords' => 'required',
			'video_id' => 'required'
                ]);

		if ($request->input('keywords') !== NULL)
                {
                        $this->createKeywords($request->input('video_id'), $request->input('keywords'));
                }

		return redirect()->back()->with('status', 'Keywords successfully added');
	}

        public function addLocation(Request $request)
        {
                $validator = $request->validate([
                        'location' => 'required',
                        'video_id' => 'required'
                ]);

                if ($request->input('location') !== NULL)
                {
                        $this->createLocation($request->input('video_id'), $request->input('location'));
                }

                return redirect()->back()->with('status', 'Location successfully added');
        }
}
