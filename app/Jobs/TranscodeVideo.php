<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use File, Config;

use \Coconut_Job;

class TranscodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = storage_path() . '/uploads/' . $this->filename;
        
        File::copy(storage_path() . '/coconut/template.conf', storage_path() . '/coconut/' . $this->filename . '.conf');
        $conf = File::get(storage_path() . '/coconut/' . $this->filename . '.conf');
        
        $conf = str_replace('[AWS_ACCESS_KEY]', env('AWS_KEY'), $conf);
        $conf = str_replace('[AWS_SECRET_KEY]', env('AWS_SECRET'), $conf);
        $conf = str_replace('[AWS_BUCKET]', env('AWS_BUCKET'), $conf);
        $conf = str_replace('[VIDEO_FILE]', env('NGROK_URL') . '/video-src/' . $this->filename, $conf);
        
        $justName = pathinfo($this->filename, PATHINFO_FILENAME);
        $videoOutput = $justName . '.mp4';
        $thumbnailOutput = $justName . '_t.jpg';
        
        $conf = str_replace('[WEBHOOK_URL]', env('NGROK_URL') . '/video-hook/' . $justName, $conf);
        
        $conf = str_replace('[OUTPUT_FILE]', $videoOutput, $conf);
        $conf = str_replace('[THUMBNAIL_FILE]', $thumbnailOutput, $conf);
        
        File::put(storage_path() . '/coconut/' . $this->filename . '.conf', $conf);
        
        $job = Coconut_Job::create([
            'api_key' => Config::get('app.coconut.key'),
            'conf' => storage_path() . '/coconut/' . $this->filename . '.conf',
            'source' => env('NGROK_URL') . '/video-src/' . $this->filename,
        ]);

        if($job->{'status'} == 'ok') {
          echo $job->{'id'};
          dd($job);
        } else {
          echo $job->{'error_code'};
          echo $job->{'error_message'};
        }
    }
}
