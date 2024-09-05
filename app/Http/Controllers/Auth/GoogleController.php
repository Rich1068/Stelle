<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
class GoogleController extends Controller
{
    public function googlepage()
    {
        return Socialite::driver('google')
        ->scopes(['openid', 'profile', 'email']) 
        ->redirect();;
    }

    public function googlecallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            // Get first name and last name directly from the user's profile
            $googleUser = $user->user;  // Raw user array from Google
            
            $firstname = $googleUser['given_name'] ?? '';  // Use given_name from Google
            $lastname = $googleUser['family_name'] ?? '';  // Use family_name from Google
            $middlename = '';  // Google doesn't explicitly provide middle name

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                switch ($finduser->role_id) {
                    case 1:
                        return redirect()->intended('super_admin/dashboard');
                    case 2:
                        return redirect()->intended('admin/dashboard');
                    default:
                        return redirect()->intended('user/dashboard');
                }
            } else {

                $file= null;  // Default if no profile picture is uploaded
            
                if ($user->avatar) {
                    // Download the user's profile picture from Google
                    $file = file_get_contents($user->avatar);
                    
                    // Generate a unique filename for the profile picture
                    $filename = Str::uuid() . '.jpg';  // Assuming Google returns a .jpg avatar
                    // Define the path for the profile picture
                    $filepath = 'images/profile_pictures/' . $filename;
                    $filedatabase = 'storage/images/profile_pictures/' . $filename;
                    
                    // Store the profile picture in the specified directory
                    Storage::disk('public')->put($filepath, $file);
                }
                $newUser = User::create([
                    'first_name' => $firstname,
                    'middle_name' => $middlename,  // If available, set middle name
                    'last_name' => $lastname,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'profile_picture' => $filedatabase,
                    'role_id' => '3',
                    'password' => bcrypt('samplepassword12345')
                ]);

                Auth::login($newUser);
                return redirect()->intended('user/dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
