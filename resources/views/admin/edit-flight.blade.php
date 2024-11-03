@extends('layouts.app')

@section('styles')
    <!--- Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">Edit Flight</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Flight</li>
            </ol>
        </div>
    </div>
    <!-- /breadcrumb -->
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    @if (session()->has('success'))
        <div class="alert alert-success" id="Message">
            {{ session()->get('success') }}
        </div>
    @endif
    <form action="{{ route('flights.update', $flight->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Add this line to specify the HTTP method -->
    
        <!-- Flight Name -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="flight_name" class="form-label">Flight Name</label>
                <input type="text" class="form-control" id="flight_name" name="flight_name" value="{{ old('flight_name', $flight->flight_name) }}" required>
            </div>
        </div>
    
        <!-- Takeoff Location -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="takeoff_location" class="form-label">Takeoff Location</label>
                <input type="text" class="form-control" id="takeoff_location" name="takeoff_location" value="{{ old('takeoff_location', $flight->takeoff_location) }}" required>
            </div>
        </div>
    
        <!-- Landing Location -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="landing_location" class="form-label">Landing Location</label>
                <input type="text" class="form-control" id="landing_location" name="landing_location" value="{{ old('landing_location', $flight->landing_location) }}" required>
            </div>
        </div>
    
        <!-- Operating Days -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="operating_days" class="form-label">Operating Days</label>
                <select class="form-control" id="operating_days" name="operating_days[]" multiple required>
                    @php
                        $operatingDays = explode(',', $flight->operating_days); // Assuming operating_days is a comma-separated string
                    @endphp
                    <option value="Monday" {{ in_array('Monday', $operatingDays) ? 'selected' : '' }}>Monday</option>
                    <option value="Tuesday" {{ in_array('Tuesday', $operatingDays) ? 'selected' : '' }}>Tuesday</option>
                    <option value="Wednesday" {{ in_array('Wednesday', $operatingDays) ? 'selected' : '' }}>Wednesday</option>
                    <option value="Thursday" {{ in_array('Thursday', $operatingDays) ? 'selected' : '' }}>Thursday</option>
                    <option value="Friday" {{ in_array('Friday', $operatingDays) ? 'selected' : '' }}>Friday</option>
                    <option value="Saturday" {{ in_array('Saturday', $operatingDays) ? 'selected' : '' }}>Saturday</option>
                    <option value="Sunday" {{ in_array('Sunday', $operatingDays) ? 'selected' : '' }}>Sunday</option>
                </select>
                <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple days.</small>
            </div>
        </div>
    
        <!-- Submit Button -->
        <div class="row mt-4">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    




    </div>
@endsection

@section('modal')
@endsection

@section('scripts')
    <!-- Form-layouts js -->
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <!--Internal  Select2 js -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  

    <script>
        setTimeout(function() {
            document.getElementById('Message').style.display = 'none';
        }, 3000);
    </script>
@endsection
