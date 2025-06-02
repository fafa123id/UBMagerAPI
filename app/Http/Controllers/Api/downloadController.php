<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class downloadController extends Controller
{
    public function download($file){
        $fileInS3 = "file/".$file;
        if (Storage::disk('s3')->exists($fileInS3)) {
            return Storage::disk('s3')->download($fileInS3,'test.png');
        }
        return view('404');
    }
}
