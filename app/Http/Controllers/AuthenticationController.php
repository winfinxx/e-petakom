<?php

namespace App\Http\Controllers;

use App\Models\Authentication as Authentication;
use App\Models\Committee as Committee;
use App\Models\Coordinator as Coordinator;
use App\Models\Dean as Dean;
use App\Models\Lecturer as Lecturer;
use App\Models\Student as Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        // Validator

        $messages = [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $validated_data = $request->validate($rules, $messages);

        $data = $request->input();

        //SELECT ELOQUENT
        $check = Authentication::where('username', $request->username)->exists();

        if ($check) {
            $Authentication = Authentication::where('username', $request->username)
                ->get()
                ->first();

            $role = $Authentication->role;
            $username = $Authentication->username;
            $password = $Authentication->password;

            if ($password == $data['password']) {
                // Save into session
                Session::put('role', $role);
                Session::put('logged_user', $username); //put the data and in session

                // if ($role == 'Dean') {
                //     return redirect('dean-profile');
                // } elseif ($role == 'Students') {
                //     return redirect('students-profile');
                // } elseif ($role == 'Lecturer') {
                //     return redirect('lecturer-profile');
                // } elseif ($role == 'Committee') {
                //     return redirect('committee-profile');
                // } elseif ($role == 'Coordinator') {
                //     return redirect('coordinator-profile');
                // }

            } else {
                // custom back validator message
                $custom_msg = [
                    'password' => 'Wrong password entered',
                ];

                // return back with custom error message
                return redirect()->back()->withInput()->withErrors($custom_msg);
            }
        } else {
            // custom back validator message
            $custom_msg = [
                'username' => 'Username does not exist',
            ];

            // return back with custom error message
            return redirect()->back()->withInput()->withErrors($custom_msg);
        }

        // return $check
    }

    public function register(Request $request)
    {
        // Validator

        $messages = [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'role.required' => 'Role is required',
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required | email',
        ];

        $validated_data = $request->validate($rules, $messages);

        $data = $request->input();

        $username = $request->username;
        $Authentication = new Authentication;
        $currentdt = date('d-m-Y H:i:s');

        //Insert
        $Authentication->username = $request->username;
        $Authentication->password = $request->password;
        $Authentication->role = $request->role;
        // $Authentication->name = $request->name;
        // $Authentication->email = $request->email;
        // $Authentication->phone = $request->phone;
        // $Authentication->created_at = $currentdt;
        // $Authentication->updated_at = $currentdt;

        $result = $Authentication->save();
        $role = $Authentication->role;

        //SELECT ELOQUENT
        $check = Authentication::where('username', $request->username)->exists();

        if ($check) {
            // custom back validator message
            $custom_msg = [
                'username' => 'Username already exist',
            ];

            // return back with custom error message
            return redirect()->back()->withInput()->withErrors($custom_msg);

        } elseif ($request->role == 'Dean') {
            $dean = new Dean;
            $dean->username = $username;
            // DECLARE VARIABLE FROM THE INPUT REQUEST
            $dean_first_name = $request->name;
            $dean_email = $request->email;
            $dean_mobile_no = $request->phone;
            // SAVE THE VARIABLE INTO THE DATABASE
            $dean->dean_first_name = $dean_first_name;
            $dean->dean_email = $dean_email;
            $dean->dean_mobile_no = $dean_mobile_no;
            $dean->save();
            return redirect('login');

        } elseif ($request->role == 'Student') {
            $student = new Student;
            $student->username = $username;
            // DECLARE VARIABLE FROM THE INPUT REQUEST
            $student_first_name = $request->name;
            $student_email = $request->email;
            $student_mobile_no = $request->phone;
            // SAVE THE VARIABLE INTO THE DATABASE
            $student->student_first_name = $student_first_name;
            $student->email = $student_email;
            $student->phone = $student_mobile_no;
            $student->save();
            return redirect('login');

        } elseif ($request->role == 'Lecturer') {
            $lecturer = new Lecturer;
            $lecturer->username = $username;
            // DECLARE VARIABLE FROM THE INPUT REQUEST
            $lecturer_first_name = $request->name;
            $lecturer_email = $request->email;
            $lecturer_mobile_no = $request->phone;
            // SAVE THE VARIABLE INTO THE DATABASE
            $lecturer->lecturer_first_name = $lecturer_first_name;
            $lecturer->email = $lecturer_email;
            $lecturer->phone = $lecturer_mobile_no;
            $lecturer->save();
            return redirect('login');

        } elseif ($request->role == 'Coordinator') {
            $coordinator = new Coordinator;
            $coordinator->username = $username;
            // DECLARE VARIABLE FROM THE INPUT REQUEST
            $coordinator_first_name = $request->name;
            $coordinator_email = $request->email;
            $coordinator_mobile_no = $request->phone;
            // SAVE THE VARIABLE INTO THE DATABASE
            $coordinator->coordinator_first_name = $coordinator_first_name;
            $coordinator->email = $coordinator_email;
            $coordinator->phone = $coordinator_mobile_no;
            $coordinator->save();
            return redirect('login');

        } elseif ($request->role == 'Committee') {
            $committee = new Committee;
            // DECLARE VARIABLE FROM THE INPUT REQUEST
            $committee_first_name = $request->name;
            $committee_email = $request->email;
            $committee_mobile_no = $request->phone;
            // SAVE THE VARIABLE INTO THE DATABASE
            $committee->committee_first_name = $committee_first_name;
            $committee->email = $committee_email;
            $committee->phone = $committee_mobile_no;
            $committee->save();
            return redirect('login');
        }

    }

    public function reset_pass(Request $request) {
        
        // validation
        $messages = [
            'required' => 'required',
            'between' => 'must :min - :max letter',
            'min' => 'minimum :min letter',
            'confirmed' => 'Password did not match',
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validated_data = $request->validate($rules, $messages);

        $data = $request->input();

        // SELECT ELOQUENT
        $check = Authentication::where('username', $request->username)->exists();

        if ($check) {
            $Authentication = Authentication::where('username', $request->username)->get()->first();
            $Authentication->password = $request->password;
            $Authentication->save();

            $custom_msg = [
                'success' => 'Password successfully changed',
            ];

            return redirect()->back()->withInput()->withErrors($custom_msg);

        } else {
            // custom back validator message
            $custom_msg = [
                'username' => 'Username does not exist',
            ];

            // return back with custom error message
            return redirect()->back()->withInput()->withErrors($custom_msg);
        }
    }

    public function logout(Request $request) {
        $request->session()->flush();
        return redirect('login');
    }
}