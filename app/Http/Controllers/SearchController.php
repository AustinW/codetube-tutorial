<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Video;

class SearchController extends Controller
{
    public function index(Request $request) {
        if (!$request->q) {
            return redirect('/');
        }

        $channels = Channel::search($request->q)->take(3)->get();
        $videos = Video::search($request->q)->where('visible', true)->get();

        dd(Video::all()->each(function(Video $video) {
            var_dump($video->toSearchableArray());
        }));

        return view('search.index', [
            'channels' => $channels,
            'videos' => $videos,
        ]);
    }
}
