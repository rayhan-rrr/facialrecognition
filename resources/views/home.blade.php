@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="col-md-12">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- {{ __('You are logged in!') }} -->
                    @if(!$user->image_file1)
                        <h3>Please upload your photo. Using this photo the system will recognize you.</h3>
                    @else
                        @if(!$user->image_file2 || !$user->image_file3)
                            <h2>Add another image to improve recognition</h2>
                           
                        @endif
                    @endif

                    @if(!$user->image_file3)
                        <form method="POST" action="{{ route('upload.base') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-7 float-left">
                                <div id="camera"></div>
                            </div>

                            <!--FOR THE SNAPSHOT-->
                            
                            <div class="col-md-5 float-left">
                                <h4>Your Captured Photo:</h4>
                                <br>
                                <p class="col-md-9 col-md-offset-3" id="snapShot"></p>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-danger btn-sm" id="btPic" onclick="takeSnapShot()"> Capture Photo</button>
                                <input type="hidden" name="capured_image" id="capured_image">
                                <br>
                                <br>
                                <button type="submit" class="btn btn-primary submitbtn" style="display: none;">Upload Photo</button>
                            </div>
                        </form>
                    @endif
                    
                    
                    @if($user->image_file3)
                    <div class="col-md-12">
                        <h4>Capture and upload your photo to varify yourself</h4>
                        <form method="POST" action="{{ route('upload.varify') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="col-md-7 float-left">
                                <div id="camera"></div>
                            </div>

                            <!--FOR THE SNAPSHOT-->
                            
                            <div class="col-md-5 float-left">
                                <h4>Your Captured Photo:</h4>
                                <br>
                                <p class="col-md-9 col-md-offset-3" id="snapShot"></p>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-danger btn-sm" id="btPic" onclick="takeSnapShot()"> Capture Photo</button>
                                <input type="hidden" name="capured_image" id="capured_image">
                                <br>
                                <br>
                                <button type="submit" class="btn btn-primary submitbtn" style="display: none;">Upload Photo</button>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // CAMERA SETTINGS.
    Webcam.set({
        width: 400,
        height: 350,
        image_format: 'png',
        jpeg_quality: 100
    });
    Webcam.attach('#camera');

    // SHOW THE SNAPSHOT.
    takeSnapShot = function () {
        Webcam.snap(function (data_uri) {
            $("#capured_image").val(data_uri);
            document.getElementById('snapShot').innerHTML = 
                '<img src="' + data_uri + '" width="290px" height="250" />';

            $(".submitbtn").show();
        });
    }
</script>
@endsection
