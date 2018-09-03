<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RaSettingsController extends Controller
{
    public function index()
    {
        $rasets = DB::table('rasettings')->get();
        
        $rasettings = array(
            'sender_name'       =>  '',
            'sender_email'      =>  '',
            'receiver_name'     =>  '',
            'receiver_email'    =>  '',
            'email_subject'     =>  '',
            'email_text'        =>  '',
        );
        
        foreach ($rasets as $raset) {
            $rasettings[$raset->rakey] = $raset->ravalue;
        }
        
        $title = 'Fire Risk Assessments - Settings';
        
        $passed_data = array(
            'title'         =>  $title,
            'rasettings'    =>  $rasettings,   
        );
        
        return view('rasettings', $passed_data);
    }
    
    public function saveSettings(Request $request)
    {
        $rasets = DB::table('rasettings')->get();
        
        $rasettings = array();
        
        foreach ($rasets as $raset) {
            $rasettings[trim($raset->rakey)] = trim($raset->ravalue);
        }
        
        $keys = array_keys($rasettings);
        
        $options = array(
            'sender_name',
            'sender_email',
            'receiver_name',
            'receiver_email',
            'email_subject',
            'email_text',
        );
        
        $issue_date = time();
        
        $creation_date = date('Y-m-d H:i:s',$issue_date);
        $modify_date = date('Y-m-d H:i:s',$issue_date);
        
        foreach ($options as $curopt) {
            $info = array(
                'rakey'         =>  $curopt,
                'ravalue'       =>  $request->get($curopt),
                'updated_at'    =>  $modify_date,
            );
            
            if (in_array($curopt, $keys)) {
                DB::table('rasettings')
                    ->where('rakey','=',$curopt)
                    ->update($info);
            } else {
                $info['created_at'] = $creation_date;
                
                DB::table('rasettings')->insert($info);
            }
        }
        
        return 1;
    }
}
