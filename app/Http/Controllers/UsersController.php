<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->get();
        
        return view('users', ['users' => $users]);
    } 
    
    public function getUser($username)
    {
        $found = array(
            'id'            =>  '',
            'name'          =>  '',
            'surname'       =>  '',
            'email'         =>  '',
            'password'      =>  '',
            'usertype'      =>  '1',
            'qualification' =>  '',
            'assessor'      =>  '0',
            'reviewer'      =>  '0',
            'disabled'      =>  '0',
        );
        
        $title = 'New User';
        
        if ('new' != $username) {
            $ufound = DB::table('users')
                    ->where('email','=',$username)
                    ->get()
                    ->first();
            
            $found['name'] = $ufound->name;
            $found['surname'] = $ufound->surname;
            $found['email'] = $ufound->email;
            $found['usertype'] = $ufound->usertype;
            $found['qualification'] = $ufound->qualification;
            $found['assessor'] = $ufound->assessor;
            $found['reviewer'] = $ufound->reviewer;
            $found['disabled'] = $ufound->disabled;
            
            
            $title = 'Details of ' . $ufound->name . ' ' . $ufound->surname;
        }
        
        $passed_data = array(
            'username'  =>  $username,
            'title'     =>  $title,
            'user'      =>  $found,
        );
        
        return view('user', $passed_data);
    }
    
    public function changePassword($username)
    {
        $found = array(
            'email'         =>  $username,
            'password'      =>  '',
        );
        
        $ufound = DB::table('users')
                ->where('email','=',$username)
                ->get()
                ->first();

        $title = 'Change password for ' . $ufound->name . ' ' . $ufound->surname;
        
        $passed_data = array(
            'username'  =>  $username,
            'title'     =>  $title,
            'user'      =>  $found,
        );

        return view('changepassword', $passed_data);
    }
    
    public function saveUser(Request $request)
    {       
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $password = $request->input('password');
        $confpassword = $request->input('confpassword');
        $usertype = $request->input('usertype');
        $qualification = $request->input('qualification');
        $assessor = $request->input('assessor');
        $reviewer = $request->input('reviewer');
        $disabled = $request->input('udisabled');
        
        $found = DB::table('users')
                ->where('email','=',$email)
                ->get();
        
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        if (0 < $found->count()) {           
            $data = array(
                'name'          =>  $name,
                'surname'       =>  $surname,
                'usertype'      =>  $usertype,
                'qualification' =>  $qualification,
                'assessor'      =>  $assessor,
                'reviewer'      =>  $reviewer,
                'disabled'      =>  $disabled,
                'updated_at'    =>  $modify_date,
            );
            
            DB::table('users')
                ->where('email','=',$email)
                ->update($data);
        } else {
            $data = array(
                'name'          =>  $name,
                'surname'       =>  $surname,
                'email'         =>  $email,
                'password'      =>  bcrypt($password),
                'usertype'      =>  $usertype,
                'qualification' =>  $qualification,
                'assessor'      =>  $assessor,
                'reviewer'      =>  $reviewer,
                'disabled'      =>  $disabled,
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            );
            
            DB::table('users')
                ->insert($data);
        }
    }
    
    public function savePassword(Request $request)
    {       
        $email = $request->input('email');
        $password = $request->input('password');
        $confpassword = $request->input('confpassword');
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        if ($password == $confpassword) {
            $data = array(
                'password'      =>  bcrypt($password),
                'updated_at'    =>  $modify_date,
            );
            
            DB::table('users')
                ->where('email',$email)
                ->update($data);
        }
    }
    
    public function deleteUser(Request $request)
    {       
        $email = $request->input('email');
            
        DB::table('users')
            ->where('email',$email)
            ->delete();
    }
}
