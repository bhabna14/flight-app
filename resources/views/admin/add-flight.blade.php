@extends('layouts.app')

@section('styles')
    <!--- Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">Add Flight</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Flight</li>
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
    <form action="{{ route('store') }}" method="post" enctype="multipart/form-data">
        @csrf
        
        <!-- Flight Name -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="flight_name" class="form-label">Flight Name</label>
                <input type="text" class="form-control" id="flight_name" name="flight_name" required>
            </div>
        </div>

        <!-- Takeoff Location -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="takeoff_location" class="form-label">Takeoff Location</label>
                <input type="text" class="form-control" id="takeoff_location" name="takeoff_location" required>
            </div>
        </div>

        <!-- Landing Location -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="landing_location" class="form-label">Landing Location</label>
                <input type="text" class="form-control" id="landing_location" name="landing_location" required>
            </div>
        </div>

        <!-- Operating Days -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="operating_days" class="form-label">Operating Days</label>
                <select class="form-control" id="operating_days" name="operating_days[]" multiple required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
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
        $(document).ready(function() {
            $("#addInput").click(function() {
                $("#show_item").append(` <div class="row input-wrapper">
                                                    <div class="col-md-6" >
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Children name</label>
                                                            <input type="text" class="form-control" name="childrenname[]" id="exampleInputPassword1" placeholder="Enter Children name">
                                                        </div>
                                                        
                                                    </div>
                                                

                                                    <div class="col-md-6">
                                                        <div class="form-group mt-4">
                                                            <button class="btn btn-danger removeInput" id="addInput">Remove</button>
                                                        </div>
                                                        
                                                    </div>
                                                </div>`);
            });

            $(document).on('click', '.removeInput', function() {
                $(this).closest('.input-wrapper')
            .remove(); // Use closest() to find the closest parent div with class input-wrapper and remove it
            });
        });


        $(document).ready(function() {
            $("#adddoc").click(function() {
                $("#show_doc_item").append(` <div class="row input-wrapper_doc">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Select ID Proof</label>
                                                            <select name="idproof[]" class="form-control" id="">
                                                                <option value="adhar">Adhar Card</option>
                                                                <option value="voter">Voter Card</option>
                                                                <option value="pan">Pan Card</option>
                                                                <option value="DL">DL</option>
                                                                <option value="health card">Health Card</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Number</label>
                                                            <input type="text" name="idnumber[]" class="form-control" id="exampleInputPassword1" placeholder="Enter Number">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputPassword1">Upload Document</label>
                                                            <input type="file" name="uploadoc[]" class="form-control" id="exampleInputPassword1" placeholder="">
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                            <div class="form-group">
                                                                <button class="btn btn-danger remove_doc" >Remove</button>
                                                            </div>
                                                            
                                                    </div>
                                                </div>`);
            });

            $(document).on('click', '.remove_doc', function() {
                $(this).closest('.input-wrapper_doc')
            .remove(); // Use closest() to find the closest parent div with class input-wrapper and remove it
            });
        });
    </script>

    <script>
        function addressFunction() {
            if (document.getElementById("same").checked) {
                document.getElementById("peraddress").value = document.getElementById("preaddress").value;
                document.getElementById("perpost").value = document.getElementById("prepost").value;
                document.getElementById("perdistri").value = document.getElementById("predistrict").value;
                document.getElementById("perstate").value = document.getElementById("prestate").value;
                document.getElementById("percountry").value = document.getElementById("precountry").value;
                document.getElementById("perpincode").value = document.getElementById("prepincode").value;
                document.getElementById("perlandmark").value = document.getElementById("prelandmark").value;

            } else {
                document.getElementById("peraddress").value = "";
                document.getElementById("perpost").value = "";
                document.getElementById("perdistri").value = "";
                document.getElementById("perstate").value = "";
                document.getElementById("percountry").value = "";
                document.getElementById("perpincode").value = "";
                document.getElementById("perlandmark").value = "";
            }
        }
    </script>
    <script>
        setTimeout(function() {
            document.getElementById('Message').style.display = 'none';
        }, 3000);
    </script>
@endsection
