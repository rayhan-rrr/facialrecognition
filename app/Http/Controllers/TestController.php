<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\User;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function uploadtest()
    {
        $folderPath = "././../public/test/";
        // if(is_dir($file)) {
        //   echo ("$file is a directory");
        // } else {
        //   echo ("$file is not a directory");
        // }

        $img = $_POST['capured_image'];
      
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        
        $file = $folderPath . $fileName;
        file_put_contents($file, $image_base64);
        //upload complete

        echo "Done!";
    }
}
