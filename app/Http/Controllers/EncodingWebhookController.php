<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Models\Video;

class EncodingWebhookController extends Controller
{
    public function handle(Request $request) {
        
        Log::info('Webhook', ['request' => $request]);
        
        $event = camel_case($request->event);
        
        if (method_exists($this, $event)) {
            return $this->{$event}($request);
        }
    }
    
    protected function videoEncoded(Request $request) {
        $video = $this->getVideoByFilename($request->original_filename);
        
        $video->processed = true;
        $video->video_id = $request->encoding_ids[0];
        
        $video->save();
        
        return $video;
    }
    
    protected function encodingProgress(Request $request) {
        $video = $this->getVideoByFilename($request->original_filename);
        
        $video->processed_percentage = $request->progress;
        
        $video->save();
        
        return $video;
    }
    
    protected function getVideoByFilename($filename) {
        return Video::where('video_filename', $filename)->firstOrFail();
    }
}
