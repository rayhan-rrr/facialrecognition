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
                    
                    <div class="col-md-12">
                        <h4>Your Photos:</h4>
                        @if($user->image_file1)
                            <div class="col-md-4 float-left">
                                <img src="/images/{{ $user->image_file1 }}" width="250">
                            </div>
                        @else
                            <h5>No photo uploaded yet!</h5>
                        @endif

                        @if($user->image_file2)
                            <div class="col-md-4 float-left">
                                <img src="/images/{{ $user->image_file2 }}" width="250">
                            </div>
                        
                        @endif

                       @if($user->image_file3)
                            <div class="col-md-4 float-left">
                                <img src="/images/{{ $user->image_file3 }}" width="250">
                            </div>
                        
                        @endif

                        <br><br>
                        <br><br>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection
