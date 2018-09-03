<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabletController extends Controller
{    
    public function index()
    {
        
    }
    
    public function uploadDataFromTablet(Request $request)
    {
        $server_folder = public_path() . '/tablet';
        
        if (!file_exists($server_folder)) {
            mkdir($server_folder);
            chmod($server_folder,0777);
        }
        
        $jobnumber_folder = '';
        $pictures_folder = '';
        $csv_folder = '';
        $signatures_folder = '';
        $reports_folder = '';
        
        header('Status: 200');
        
        $jobnumber = $request->input('job_number');
        
        /*
         * If the job number is sent correctly, I check its folder and create 
         * them if they do not exist
         */
        if (!empty($jobnumber)) {
            $jobnumber_folder = $server_folder . '/' . $jobnumber;
            $pictures_folder = $server_folder . '/' . $jobnumber . '/pictures';
            $csv_folder = $server_folder . '/' . $jobnumber . '/csv';
            $signatures_folder = $server_folder . '/' . $jobnumber . '/signatures';
            $reports_folder = $server_folder . '/' . $jobnumber . '/reports';
            
            if (!file_exists($jobnumber_folder)) {
                mkdir($jobnumber_folder);
                chmod($jobnumber_folder, 0777);
            }
            
            if (!file_exists($pictures_folder)) {
                mkdir($pictures_folder);
                chmod($pictures_folder, 0777);
            }
            
            if (!file_exists($csv_folder)) {
                mkdir($csv_folder);
                chmod($csv_folder, 0777);
            }
            
            if (!file_exists($signatures_folder)) {
                mkdir($signatures_folder);
                chmod($signatures_folder, 0777);
            }
            
            if (!file_exists($reports_folder)) {
                mkdir($reports_folder);
                chmod($reports_folder, 0777);
            }
        }
        
        // 0 is when something is missing from the data to be sent
        $response = '0';
        
        $files = $request->allFiles();
        
        // If files or the job number are not sent, the script cannot work
        if (empty($jobnumber) or empty($files)) {
            return $response;
        }
        
        $bads_http = array();
        
        /* Upload pictures in HTTP */
        foreach ($files as $key => $curfile) {
            $selfolder = $jobnumber_folder;
            
            $filename = $request->file($key);
            $originalFileName = $filename->getClientOriginalName();

            if ('csv' == substr($originalFileName, -3)) {
                $selfolder = $csv_folder;
            }
            
            if ('signature' == substr($originalFileName, 0, 9)) {
                $selfolder = $signatures_folder;
                $initials = substr($originalFileName, 7, 3);
                $originalFileName = strtolower($initials) . substr($originalFileName, -4);
            }
            
            if ('jpg' == substr($originalFileName, -3)) {
                $selfolder = $pictures_folder;
            }
            
            if ('pdf' == substr($originalFileName, -3)) {
                $selfolder = $reports_folder;
            }
            
            $ok = true;
            
            try {
                $filename->move($selfolder,$originalFileName);
            } catch (Exception $e) {
                $ok = false;
            }
            
            if ('signature' == substr($key, 0, 9)) {
                continue;
            }
            
            $sel_index = 'csv';
            $type = '';
            
            if ('picture' == substr($key, 0, 7)) {
                $sel_index = 'p' . substr($key, 8);
                $type = 'picture';
            } else if ('csv' == substr($key, 0, 3)) {
                $sel_index = 'c' . substr($key, 4);
                $type = 'csv';
            } else if ('pdf' == substr($key, 0, 3)) {
                $sel_index = 'd' . substr($key, 4);
                $type = 'csv';
            }

            if ($ok === false) {
                $bads_http[] = $sel_index;
                continue;
            }            
        }
        
        $response = '5';
        if (!empty($bads_http)) {
            $response = '3';
        } 
        
        // Send email to surveys@deriskuk.com
                
        $receiver = array(
            'name'  =>  'Surveys',
            'email' =>  'surveys@deriskuk.com',
        );
        
        $additional_headers = array(
            'From: deriskukltd@gmail.com',
            'Reply-To: deriskukltd@gmail.com',
            'X-Mailer: PHP/' . phpversion(),
        );
        
        $headers = implode("\n",$additional_headers);
        
        $subject = 'New Job Number sent to server';
        
        $body = 'Job Number ' . $jobnumber . ' has been sent to the server with its files from the tablet';
        
        mail($receiver['name'] . '<' . $receiver['email'] . '>', $subject, $body, $headers);
        
        return $response;
    }
}
