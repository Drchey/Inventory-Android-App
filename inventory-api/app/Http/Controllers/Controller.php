<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($img, $path = 'public')
    {
        if(!$img){
            return null;
        }
        $filename = time().'.png';

        Storage::disk($path)->put($filename, base64_decode($img));

        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}
