<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\Email;

class UserController extends Controller
{
    public function index()
    {
        $auth = auth()->user();

        // Admin sees all users, others see only users they created
        $users = $auth->role === 'Admin'
            ? User::latest()->get()                  // Admin → all users
            : User::where('user_id', $auth->id)      // Normal user → only users created by self
            ->latest()
            ->get();

        return view('user.index', compact('users'));
    }


    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'id_card'       => 'nullable|string|max:50',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image',
            'email'         => 'required|email|unique:users,email',
            'role'          => 'required|in:Admin,Seller,Buyer,Investor,Advisor,Corporate,Partner',
            'password'      => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name         = $request->name;
        $user->father_name  = $request->father_name;
        $user->id_card      = $request->id_card;
        $user->phone_number = $request->phone_number;
        $user->address      = $request->address;
        $user->email        = $request->email;
        $user->role         = $request->role;
        $user->password     = Hash::make($request->password);
        $user->user_id = auth()->id(); // track who created this user

        // 🔹 Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_photos'), $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('success', 'User saved successfully!');
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'id_card'       => 'nullable|string|max:50',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image',
            'email'         => 'required|email|unique:users,email,' . $id,
            'role'          => 'required|in:Admin,Seller,Buyer,Investor,Advisor,Corporate,Partner',
            'password'      => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name         = $request->name;
        $user->father_name  = $request->father_name;
        $user->id_card      = $request->id_card;
        $user->phone_number = $request->phone_number;
        $user->address      = $request->address;
        $user->email        = $request->email;
        $user->role         = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 🔹 Profile Photo Update
        if ($request->hasFile('profile_photo')) {

            // old image delete
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_photos'), $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted and email sent.');
    }

    // Profile Methods

    public function profile()
    {
        $user = Auth::user(); // logged-in user
        return view('profile', compact('user'));
    }

    public function showProfile($id)
    {
        $user = User::findOrFail($id); // get user by ID
        return view('profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'father_name'   => 'nullable|string|max:255',
            'phone_number'  => 'nullable|string|max:20',
            'id_card'       => 'nullable|string|max:50',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name         = $request->name;
        $user->father_name  = $request->father_name;
        $user->phone_number = $request->phone_number;
        $user->id_card      = $request->id_card;
        $user->address      = $request->address;

        // Password update (optional)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Profile photo update
        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $imageName = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_photos'), $imageName);
            $user->profile_photo = 'uploads/profile_photos/' . $imageName;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }
}
