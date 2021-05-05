<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use App\User;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    /**
     * Show the varified page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function varified()
    {
        $user = Auth::user();
        return view('varified', compact('user'));
    }

    public function uploadBase()
    {
        $folderPath = "././../public/images/";
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

        $user = Auth::user();

        $client = new Client([
            'verify' => false
        ]);

        $url = 'https://api.kairos.com/enroll';

        try {

          $response = $client->request('POST', $url, [
                'multipart' => [
                    [
                        'name' => 'subject_id',
                        'contents' => 'user'. $user->id,
                    ],
                    [
                        'name' => 'gallery_name',
                        'contents' => 'users',
                    ],
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'image',
                        'contents' => fopen($file, 'r'),
                        'filename' => $fileName,
                    ]
                ],
                'headers'  => [
                    'app_id' => '15b79648',
                    'app_key' => '176bb45679ebe3c67da27f7bcb8fce55'
                ]
            ]);

        } catch (RequestException $e) {

            return 'Gateway Connection Error';

        }

        echo $response->getBody();

        $returnData = json_decode($response->getBody());

        $userData = User::find($user->id);

        if (!$userData->subject_id) {

            $userData->subject_id = 'user'. $user->id;
            $userData->image_file1 = $fileName;
            $userData->face_id1 = $returnData->face_id;
            $userData->gallery_name = 'users';
            $userData->save();
        
        } else if(!$userData->image_file2){

            $userData->image_file2 = $fileName;
            $userData->face_id2 = $returnData->face_id;
            $userData->save();
        
        } else if(!$userData->image_file3){

            $userData->image_file3 = $fileName;
            $userData->face_id3 = $returnData->face_id;
            $userData->save();
        }
        

        return redirect()->route('home')->withStatus('Successfully captured!');
    }

    public function uploadVarify()
    {
        $folderPath = "././../public/images/";
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

        $user = Auth::user();

        $client = new Client([
            'verify' => false
        ]);

        $url = 'https://api.kairos.com/verify';

        try {

          $response = $client->request('POST', $url, [
                'multipart' => [
                    [
                        'name' => 'subject_id',
                        'contents' => 'user'. $user->id,
                    ],
                    [
                        'name' => 'gallery_name',
                        'contents' => 'users',
                    ],
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'image',
                        'contents' => fopen($file, 'r'),
                        'filename' => $fileName,
                    ]
                ],
                'headers'  => [
                    'app_id' => '15b79648',
                    'app_key' => '176bb45679ebe3c67da27f7bcb8fce55'
                ]
            ]);

        } catch (RequestException $e) {

            return 'Gateway Connection Error';

        }

        echo $response->getBody();

        $returnData = json_decode($response->getBody());
        if(isset($returnData->Errors)){
            $error = $returnData->Errors;
            return redirect()->route('home')->withError($error[0]->Message);
        }

        $imageData = $returnData->images;

        $confidence = $imageData[0]->transaction->confidence;

        $confidence = $confidence * 100;

        if ($confidence >= 60) {

            return redirect()->route('varified')->withStatus('Successfully varified with confidence: '. $confidence .'%');
        
        }

        return redirect()->route('home')->withError('Varification failed with confidence: '. $confidence .'%');
        

        
    }


}
