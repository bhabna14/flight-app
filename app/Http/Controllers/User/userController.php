<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bankdetail;
use App\Models\Childrendetail;
use App\Models\Addressdetail;
use App\Models\IdcardDetail;
use App\Models\SebayatFamily;
use App\Models\SocialMedia;
use App\Models\TypeOfSeba;

use PDF;
use DB;
use Illuminate\Support\Facades\Hash;


class userController extends Controller
{
    //

    public function userlogin(){
        return view("login");
    }
    public function userauthenticate(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'phonenumber' => 'required', // Ensure exactly 10 digits
            'otp' => 'required',
        ]);
    
        // Construct the full phone number with the country code
        $phonenumber =  $request->input('phonenumber');
    
        // Debugging purpose - Check the full phone number
        // dd($phonenumber);
    
        // Retrieve the user based on the full phone number
        $user = User::where('phonenumber', $phonenumber)->first();
    
        // Check if the user exists and if the OTP matches
        if ($user && $user->otp === $request->input('otp')) {
            // Check if the user's application status is approved
            if ($user->application_status == "approved") {
                // Log in the user
                Auth::guard('users')->login($user);
                return redirect()->intended('/user/dashboard');
            } else {
                // Redirect back with an error message if the account is not activated
                return redirect()->back()->withInput()->withErrors(['login_error' => 'Your account is not activated']);
            }
        } else {
            // Redirect back with an error message if the phone number or OTP is invalid
            return redirect()->back()->withInput()->withErrors(['login_error' => 'Invalid phone number or OTP']);
        }
    }
    


    public function dashboard(){
        return view('user.dashboard');
    }
    public function userlogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function userregister()
    {
        return view('register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         $request->validate([
             'first_name' => 'required|string|max:250',
             'last_name' => 'required|string|max:250',
             'phonenumber' => 'required',
             'healthcard' => 'required',
             
         ]);
     
         // Check if the phone number already exists
         $mobileNumberExists = User::where('phonenumber', $request->phonenumber)->exists();
         if ($mobileNumberExists) {
             return redirect()->back()->withInput()->withErrors(['login_error' => 'Phone Number already exists.']);
         } else {
             $user = new User();
     
             // Get the global increment number (total count of users + 1)
             $globalIncrement = User::count() + 1;
     
             // Format the increment to be 4 digits, e.g., 0001, 0002, etc.
             $formattedIncrement = str_pad($globalIncrement, 4, '0', STR_PAD_LEFT);
     
             // Generate the user_id based on the health card number and the formatted increment number
             $user->user_id = $request->healthcard . '#' . $formattedIncrement;
     
             // Assign other attributes
             $user->name = $request->first_name . ' ' . $request->last_name;
             $user->first_name = $request->first_name;
             $user->last_name = $request->last_name;
             $user->phonenumber = '+91' . $request->phonenumber;
             $user->email = $request->email;
             $user->healthcard = $request->healthcard;
             $user->role = 'user';
             $user->status = 'active';
             $user->application_status = 'pending';
             $user->added_by = 'user';
             $user->otp = '234234';
     
             // Save the user
             $user->save();
     
             return redirect('/')->with('success', 'Registered successfully.');
         }
     }
     
    

    public function sebayatregister(){

        // $user_id = Auth::user()->user_id;
        $user_id = Auth::guard('users')->user()->user_id;
      
        $userinfo = User::where('user_id', $user_id)->first();
        $bankinfo = Bankdetail::where('user_id', $user_id)->first();
        $childinfos = Childrendetail::where('user_id', $user_id)
                                            ->where('status','active')->get();
        $iddetails = IdcardDetail::where('user_id', $user_id)->where('status','active')->get();
        $address = Addressdetail::where('user_id', $user_id)->first();
        $bankinfo = Bankdetail::where('user_id', $user_id)->first();
        $familyinfo = SebayatFamily::where('user_id', $user_id)->first();
        $socialmedia = SocialMedia::where('user_id', $user_id)->first();
        $typeofsebas = TypeOfSeba::where('user_id', $user_id)->where('status','active')->get();
        
               
        return view('user.sebayatregister',compact('userinfo','bankinfo','childinfos','iddetails','address','bankinfo','familyinfo','socialmedia','typeofsebas'));
        // return view('user.sebayatregister', ['user' => $user]);
        
        // return view('user.sebayatregister');
    }




    // app/Http/Controllers/UserController.php

public function updateuserinfo(Request $request, $user_id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'last_name' => 'nullable|string|max:255',
        'phonenumber' => 'nullable|string|max:15',
        'dob' => 'nullable|date',
        'bloodgrp' => 'nullable|string|max:10',
        'qualification' => 'nullable|string|max:255',
        'healthcard' => 'nullable|string|max:255',
        'templeid' => 'nullable|string|max:255',
        'userphoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'datejoin' => 'nullable|date',
    ]);

    // Attempt to find the user
    $user = User::where('user_id', $user_id)->first(); // Use first() instead of find()

    if (!$user) {
        // Handle the case where the user is not found
        return redirect()->back()->with('error', 'User not found.');
    }

    // Handle the file upload if a new file is provided
    if ($request->hasFile('userphoto')) {
        // Delete the old photo if it exists
        if ($user->userphoto && Storage::exists('public/assets/uploads/userphoto/' . $user->userphoto)) {
            Storage::delete('public/assets/uploads/userphoto/' . $user->userphoto);
        }

        // Store the new photo
        $photoPath = $request->file('userphoto')->store('public/assets/uploads/userphoto');
        $validatedData['userphoto'] = basename($photoPath);
    }

    // Update the user's information
    $user->update($validatedData);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'User information updated successfully!');
}

     public function updateFamilyInfo(Request $request, $user_id)
     {
         $user_id = urldecode($user_id);
        //  dd($user_id);
     
         // Validate the request data
         $request->validate([
             'fathername' => 'nullable|string|max:255',
             'mothername' => 'nullable|string|max:255',
             'spouse' => 'nullable|string|max:255',
             'fatherphoto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'motherphoto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'spousephoto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             'marital' => 'required|string|in:married,unmarried',
         ]);
     
         // Prepare the data for update or create
         $data = [
             'fathername' => $request->input('fathername'),
             'mothername' => $request->input('mothername'),
             'spouse' => $request->input('spouse'),
             'marital' => $request->input('marital'),
         ];
     
         // Handle father's photo upload if provided
         if ($request->hasFile('fatherphoto')) {
             $fatherphoto = $request->file('fatherphoto')->store('family_photos', 'public');
             $data['fatherphoto'] = $fatherphoto;
         } else {
             // Don't overwrite if the fatherphoto field is not being uploaded
             unset($data['fatherphoto']);
         }
     
         // Handle mother's photo upload if provided
         if ($request->hasFile('motherphoto')) {
             $motherphoto = $request->file('motherphoto')->store('family_photos', 'public');
             $data['motherphoto'] = $motherphoto;
         } else {
             unset($data['motherphoto']);
         }
     
         // Handle spouse's photo upload if provided
         if ($request->hasFile('spousephoto')) {
             $spousephoto = $request->file('spousephoto')->store('family_photos', 'public');
             $data['spousephoto'] = $spousephoto;
         } else {
             unset($data['spousephoto']);
         }
     
         // Use updateOrCreate to update the existing record or create a new one if not found
         SebayatFamily::updateOrCreate(
             ['user_id' => $user_id], // Condition to check if the record exists
             $data // Data to update or insert
         );
     
         return redirect()->back()->with('success', 'Family information updated successfully.');
     }
     
     
     public function updatechildInfo(Request $request)
     {
         // Get all the input data
         $userId = $request->input('user_id');
         $names = $request->input('childrenname');
         $dobs = $request->input('dob');
         $genders = $request->input('gender');
         $photos = $request->file('childphoto');
     
         // Loop through the children info and save each child's details
         foreach ($names as $key => $name) {
             $child = new Childrendetail(); // Assuming you're using Childrendetail model
     
             // Assign values to the model attributes
             $child->user_id = $userId;
             $child->childrenname = $name;
             $child->dob = $dobs[$key];
             $child->gender = $genders[$key];
     
             // Handle file upload for child photo if exists
             if (isset($photos[$key])) {
                 $fileName = time() . '_' . $photos[$key]->getClientOriginalName();
                 $photos[$key]->move(public_path('uploads/children'), $fileName);
                 $child->childphoto = $fileName;
             }
     
             // Set the status to 'active'
             $child->status = 'active';
     
             // Save the child info to the database
             $child->save();
         }
     
         return redirect()->back()->with('success', 'Children Information Updated Successfully');
     }
     

     
     public function updateChildStatus($id)
     {
         try {
             $affected = Childrendetail::where('id', $id)
                          ->update(['status' => 'deleted']);
     
             if ($affected) {
                 return redirect()->back()->with('success', 'Data deleted successfully.');
             } else {
                 return redirect()->back()->with('error', 'Data not found or already deleted.');
             }
         } catch (\Exception $e) {
             // Log the error message or handle the exception as needed
             return redirect()->back()->with('error', 'An error occurred while deleting data.');
         }
     }
     
 
     public function updateIdInfo(Request $request)
     {
         $userId = $request->input('user_id');
         $idProofs = $request->input('idproof');
         $idNumbers = $request->input('idnumber');
         $uploadDocs = $request->file('uploadoc');
     
         foreach ($idProofs as $key => $idProof) {
             $iddetail = new IdcardDetail(); // Assuming you're using IdDetail model
     
             $iddetail->user_id = $userId;
             $iddetail->idproof = $idProof;
             $iddetail->idnumber = $idNumbers[$key];
     
             // Handle file upload if exists
             if (isset($uploadDocs[$key])) {
                 $fileName = time() . '_' . $uploadDocs[$key]->getClientOriginalName();
                 $uploadDocs[$key]->move(public_path('uploads/id_docs'), $fileName);
                 $iddetail->uploadoc = $fileName;
             }
     
             // Set the status to 'active'
             $iddetail->status = 'active';
     
             // Save the ID card info to the database
             $iddetail->save();
         }
     
         return redirect()->back()->with('success', 'ID Card Information Updated Successfully');
     }
     
     public function updateIdstatus($id)
     {
         // Update the status field to 'deleted'
         $affected = IdcardDetail::where('id', $id)
             ->update(['status' => 'deleted']);
     
         // Redirect back with a success message
         return redirect()->back()->with('success', 'Data status updated to deleted successfully.');
     }
     

     public function updateAddressInfo(Request $request, $user_id)
     {
         // Validate the input
         $request->validate([
             'preaddress' => 'required|string|max:255',
             'prepost' => 'required|string|max:255',
             'predistrict' => 'required|string|max:255',
             'prestate' => 'required|string|max:255',
             'precountry' => 'required|string|max:255',
             'prepincode' => 'required|string|max:10',
             'prelandmark' => 'nullable|string|max:255',
             'peraddress' => 'nullable|string|max:255',
             'perpost' => 'nullable|string|max:255',
             'perdistri' => 'nullable|string|max:255',
             'perstate' => 'nullable|string|max:255',
             'percountry' => 'nullable|string|max:255',
             'perpincode' => 'nullable|string|max:10',
             'perlandmark' => 'nullable|string|max:255',
         ]);
 
         // Update or create address information
         Addressdetail::updateOrCreate(
             ['user_id' => $user_id],
             [
                 'preaddress' => $request->input('preaddress'),
                 'prepost' => $request->input('prepost'),
                 'predistrict' => $request->input('predistrict'),
                 'prestate' => $request->input('prestate'),
                 'precountry' => $request->input('precountry'),
                 'prepincode' => $request->input('prepincode'),
                 'prelandmark' => $request->input('prelandmark'),
                 'peraddress' => $request->input('peraddress'),
                 'perpost' => $request->input('perpost'),
                 'perdistri' => $request->input('perdistri'),
                 'perstate' => $request->input('perstate'),
                 'percountry' => $request->input('percountry'),
                 'perpincode' => $request->input('perpincode'),
                 'perlandmark' => $request->input('perlandmark'),
             ]
         );
 
         // Redirect back with success message
         return redirect()->back()->with('success', 'Address information updated successfully.');
     }
     public function updateBankInfo(Request $request, $user_id)
     {
         // Validate the incoming request data
         $validatedData = $request->validate([
             'bankname' => 'required|string|max:255',
             'branchname' => 'required|string|max:255',
             'ifsccode' => 'required|string|max:255',
             'accname' => 'required|string|max:255',
             'accnumber' => 'required|string|max:255',
         ]);
     
         try {
             // Use updateOrCreate to update existing bank info or create new one
             Bankdetail::updateOrCreate(
                 ['user_id' => $user_id], // Criteria for finding the existing record
                 $validatedData // Data to update or create
             );
     
             // Redirect or return response as needed
             return redirect()->back()->with('success', 'Bank information has been updated successfully!');
         } catch (\Exception $e) {
             // Log the exception
             \Log::error('Error updating bank information: ' . $e->getMessage());
     
             // Redirect back with an error message
             return redirect()->back()->with('error', 'An error occurred while updating bank information. Please try again.');
         }
     }
     
 
  

      public function updateSocialMedia(Request $request, $user_id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'facebookurl' => 'nullable|url',
            'instagramurl' => 'nullable|url',
            'youtubeurl' => 'nullable|url',
            'twitterurl' => 'nullable|url',
            'linkedinurl' => 'nullable|url',
            
        ]);

        try {
            // Use updateOrCreate to update existing social media info or create new one
            SocialMedia::updateOrCreate(
                ['user_id' => $user_id], // Criteria for finding the existing record
                $validatedData // Data to update or create
            );

            // Redirect or return response as needed
            return redirect()->back()->with('success', 'Social media information has been updated successfully!');
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error updating social media information: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while updating social media information. Please try again.');
        }
    }

      public function sebayatprofile(){
        $user_id = Auth::guard('users')->user()->user_id;
    
        $userinfo = User::where('user_id', $user_id)->first();
        $bankinfo = Bankdetail::where('user_id', $user_id)->first();
        $childinfos = Childrendetail::where('user_id', $user_id)
                                            ->where('status','active')->get();
        $iddetails = IdcardDetail::where('user_id', $user_id)->where('status','active')->get();
        $address = Addressdetail::where('user_id', $user_id)->first();
        $bankinfo = Bankdetail::where('user_id', $user_id)->first();
        $familyinfo = SebayatFamily::where('user_id', $user_id)->first();
        $socialmedia = SocialMedia::where('user_id', $user_id)->first();
        $typeofsebas = TypeOfSeba::where('user_id', $user_id)->where('status','active')->get();
        
               
        return view('user/sebayatprofile',compact('userinfo','bankinfo','childinfos','iddetails','address','bankinfo','familyinfo','socialmedia','typeofsebas'));

       
        // return view('user/sebayatprofile',compact('userinfo','bankinfo','childinfos','iddetails','address','bankinfo','familyinfo'));

    }
    public function downloadUserImage(Request $request)
    {
       

        $user = Auth::guard('users')->user()->user_id;
        $iddetails = IdcardDetail::where('user_id', $user)->get();
        // Fetch the authenticated user
        $imagePath = asset($iddetails->uploadoc); // Path to user's image

        // Generate PDF with the user's image
        $pdf = PDF::loadView('pdf.user_image', ['imagePath' => $imagePath]);

        // Return PDF as a downloadable response
        return $pdf->download('user_image.pdf');
    }
    public function updateotherInfo(Request $request)
    {
        // Validate request
        $request->validate([
            'typeseba' => 'required|array',
            'beddhaseba' => 'required|array',
        ]);
    
        foreach ($request->typeseba as $index => $type) {
            TypeOfSeba::create([
                'user_id' => $request->user_id,
                'typeseba' => $type,
                'beddhaseba' => $request->beddhaseba[$index],
                'status' => 'active', // Set status to active by default
            ]);
        }
    
        return redirect()->back()->with('success', 'Seba information added successfully!');
    }
    public function updateSebaStatus($id)
    {
        // Find the TypeOfSeba record by ID
        $typeofseba = TypeOfSeba::find($id);
    
        if ($typeofseba) {
            // Update the status to 'deleted'
            $typeofseba->update(['status' => 'deleted']);
    
            return redirect()->back()->with('success', 'Seba information marked as deleted.');
        }
    
        return redirect()->back()->with('error', 'Seba information not found.');
    }
        
}
