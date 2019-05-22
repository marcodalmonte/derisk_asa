<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Contracts\Logging\Log;

class TabletController extends Controller
{    
    public function index()
    {
        
    }
    
    /**
     * Returns the available surveytypes in JSON format.
     */
    public function pullSurveyTypes()
    {
        $surveytypes = DB::table('surveytypes')
                ->orderBy('surveytype','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['surveytypes' => $surveytypes]);
    }
    
    /**
     * Returns the available labs in JSON format.
     */
    public function pullLabs()
    {
        $labs = DB::table('labs')
                ->orderBy('company','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['labs' => $labs]);
    }
    
    /**
     * Returns the available rooms in JSON format.
     */
    public function pullRooms()
    {
        $rooms = DB::table('rooms')
                ->orderBy('name','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['rooms' => $rooms]);
    }
    
    /**
     * Returns the available floors in JSON format.
     */
    public function pullFloors()
    {
        $floors = DB::table('floors')
                ->orderBy('menu','ASC')
                ->orderBy('code','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['floors' => $floors]);
    }
    
    /**
     * Returns the available products in JSON format.
     */
    public function pullProducts()
    {
        $products = DB::table('products')
                ->orderBy('name','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['products' => $products]);
    }
    
    /**
     * Returns the available extents in JSON format.
     */
    public function pullExtents()
    {
        $extents = DB::table('extents')
                ->orderBy('code','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['extents' => $extents]);
    }
    
    /**
     * Returns the available surface treatments in JSON format.
     */
    public function pullSurfaceTreatments()
    {
        $surface_treatments = DB::table('surface_treatments')
                ->orderBy('code','ASC')
                ->get();
        
        // Return the information via json
        return response()->json(['surface_treatments' => $surface_treatments]);
    }
    
    /**
     * Returns the information about a ukas number in JSON format.
     */
    public function pullJobInformation(Request $request)
    {
        $ukasnumber = $request->input('ukasnumber');    
        
        $job = DB::table('surveys')
                ->where('ukasnumber','=',$ukasnumber)
                ->first();
        
        $job->sitedescription = preg_replace('/[\r\n]+/',' ', $job->sitedescription);
        
        // Try to split the address
        $sitemock = explode("\n",$job->siteaddress);
        $siteaddress_parts = array();
        
        for ($k = 0; $k < count($sitemock); $k++) {
            $sitemock[$k] = trim($sitemock[$k]);
            
            if (empty($sitemock[$k])) {
                continue;
            }
            
            $siteaddress_parts[] = $sitemock[$k];
        }
        
        $surveytype = DB::table('surveytypes')
                ->where('id','=',$job->surveytype_id)
                ->first();
        
        $client = DB::table('clients')
                ->where('id','=',$job->client_id)
                ->first();
        
        $lab = DB::table('labs')
                ->where('id','=',$job->lab_id)
                ->first();
        
        $postcode = $siteaddress_parts[count($siteaddress_parts) - 1];
        $town = $siteaddress_parts[count($siteaddress_parts) - 2];
        $address1 = $siteaddress_parts[0];
        $address2 = '';
        
        if (4 == count($siteaddress_parts)) {
            $address2 = $siteaddress_parts[1];
        } else if (5 == count($siteaddress_parts)){
            $address2 = $siteaddress_parts[2];
            $address1 .= ', ' . $siteaddress_parts[1];
        }
        
        $respjob = array(
            $job->ukasnumber,
            $surveytype->id,
            $surveytype->surveytype,
            $client->name,
            $client->companyname,
            $client->address1,
            $client->address2,
            $client->city,
            $client->postcode,
            $address1,
            $address2,
            $town,
            $postcode,
            $job->sitedescription,
            $lab->id,
            $lab->company,
            $lab->building,
            $lab->address,
            $lab->town,
            $lab->postcode,
            $job->issued_to,
            $job->urgency,
        );
        
        // Return the information via json
        return response()->json($respjob);
    }
    
    /**
     * Returns all the inspections of a job number in JSON format.
     * If a building is sent in request, just the inspections of that building
     * are sent back.
     * If a floor code is sent in request, just the inspections of that building
     * are sent back.
     * A combination of building and floor can be requested.
     */
    public function pullInspections(Request $request)
    {
        $ukasnumber = $request->input('ukasnumber');
        $jobbuilding = $request->input('building');
        $jobfloor = $request->input('floor');
        $insp = $request->input('inspection');
        
        $job = DB::table('surveys')
                ->where('ukasnumber','=',$ukasnumber)
                ->first();

        $inspections = DB::table('inspections')
                ->where('survey_id','=',$job->survey_id);

        // In case only a particular building is requested
        if (!empty($jobbuilding)) {
            $inspections = $inspections->where('building','=',$jobbuilding);
        }

        // In case only a particular floor is requested
        if (!empty($jobfloor)) {
            $floor = DB::table('floors')
                ->where('code','=',$jobfloor)
                ->first();

            $inspections = $inspections->where('floor_id','=',$floor->id);
        }
        
        // In case only a particular inspection is requested
        if (!empty($insp)) {
            $inspections = $inspections->where('inspection_number','=',$insp);
        }

        $inspections = $inspections->get();
        
        $respjob = array();
        
        foreach ($inspections as $inspection) {
            if (!empty($inspection->referral)) {
                $ref = DB::table('inspections')
                    ->where('survey_id','=',$job->survey_id)
                    ->where('id','=',$inspection->id)
                    ->first();

                $reffloor = DB::table('floors')
                    ->where('id','=',$ref->floor_id)
                    ->first();

                $inspection->referral = $reffloor->code . '-' . $ref->inspection_number;
            } else {
                $inspection->referral = '';
            }

            $respjob[] = array(
                'inspection_number' => $inspection->inspection_number,
                'building' => $inspection->building,
                'floor' => $inspection->floor_id,
                'room' => $inspection->room_id,
                'room_name' => $inspection->room_name,
                'product' => (empty($inspection->product_id) ? '' : $inspection->product_id),
                'quantity' => (empty($inspection->quantity) ? '' : $inspection->quantity),
                'extent_of_damage' => (empty($inspection->extent_of_damage) ? '' : $inspection->extent_of_damage),
                'surface_treatment' => (empty($inspection->surface_treatment) ? '' : $inspection->surface_treatment),
                'referral' => $inspection->referral,
                'accessibility' => $inspection->accessibility,
                'accessible' => (($inspection->accessible) ? 1 : 0),            
                'presumed' => (($inspection->presumed) ? 1 : 0),
                'observation' => (($inspection->observation) ? 1 : 0),
                'reinspection' => (($inspection->reinspection) ? 1 : 0),
                'material_location' => (empty($inspection->material_location) ? '' : $inspection->material_location),
                'recommendations' => (empty($inspection->recommendations) ? '' : $inspection->recommendations),
                'recommendationsNotes' => (empty($inspection->recommendationsNotes) ? '' : $inspection->recommendationsNotes),
                'photo' => (empty($inspection->photo) ? '' : $inspection->photo),
            );
        }
        
        // Return the information via json
        return response()->json($respjob);
    }
    
    public function pushJobInformation(Request $request)
    {
        $ukasnumber = $request->input('ukasnumber');
        $site_description = $request->input('site_description');
        
        if (empty($ukasnumber)) {
            return response()->json(['updated' => 0]);
        }
        
        $mdate = date('Y-m-d H:i:s',time());
        
        $mydata = array(
            'sitedescription' =>  $site_description,
            'updated_at'      =>  $mdate,
        );

        DB::table('surveys')
            ->where('ukasnumber', '=', $ukasnumber)
            ->update($mydata);
        
        // Create all the folders
        $server_folder = public_path() . '/tablet';
        $jobnumber_folder = $server_folder . '/' . $ukasnumber;
        $pictures_folder = $jobnumber_folder . '/pictures';
        $signatures_folder = $jobnumber_folder . '/signatures';
        $reports_folder = $jobnumber_folder . '/reports';
        
        if (!file_exists($server_folder)) {
            mkdir($server_folder);
            chmod($server_folder,0777);
        }
        
        if (!file_exists($jobnumber_folder)) {
            mkdir($jobnumber_folder);
            chmod($jobnumber_folder,0777);
        }
        
        if (!file_exists($pictures_folder)) {
            mkdir($pictures_folder);
            chmod($pictures_folder,0777);
        }
        
        if (!file_exists($signatures_folder)) {
            mkdir($signatures_folder);
            chmod($signatures_folder,0777);
        }
        
        if (!file_exists($reports_folder)) {
            mkdir($reports_folder);
            chmod($reports_folder,0777);
        }
        
        return response()->json(['updated' => 1]);
    }
    
    /**
     * Returns a response to the upload request
     * 0 => Nothing uploaded because the job number is empty or not files sent
     * 1 => File uploaded
     * 2 => Problems uploading some files
     */
    public function pushInspections(Request $request)
    {
        header('Status: 200');
        
        $ukasnumber = $request->input('ukasnumber');
        
        if (empty($ukasnumber)) {
            return response()->json(['uploaded' => 0]);
        }
        
        $files = $request->allFiles();
        
        if (empty($files)) {
            return response()->json(['uploaded' => 0]);
        }
        
        $server_folder = public_path() . '/tablet';
        $jobnumber_folder = $server_folder . '/' . $ukasnumber;
        $csv_folder = $jobnumber_folder . '/csv';
        $pictures_folder = $jobnumber_folder . '/pictures';
        $signatures_folder = $jobnumber_folder . '/signatures';
        $reports_folder = $jobnumber_folder . '/reports';
        
        $response_status = 1;
        
        /* Upload files in HTTP */
        foreach ($files as $key => $file) {
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
            
            try {
                $filename->move($selfolder,$originalFileName);
            } catch (Exception $e) {
                Log::error('Problems uploading files for Job ' . $ukasnumber);
                Log::error($e->getTraceAsString());     
                $response_status = 2;
                break;
            }
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
        
        $subject = 'Job Number ' . $ukasnumber . ' sent to server';
        
        $body = "Job Number ' . $ukasnumber . ' has been sent to the server.";
        
        if ($response_status == 2) {
            $body .= "\nThere have been problems with some files, check logs for more info.";
        }
        
        mail($receiver['name'] . '<' . $receiver['email'] . '>', $subject, $body, $headers);
        
        return response()->json(['uploaded' => $response_status]);
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
