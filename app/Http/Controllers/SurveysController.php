<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SurveysController extends Controller
{
    private function normalizeJobNumber($jobnumber,$tot)
    {
        $jnumber = (string)$jobnumber;
        
        $len = strlen($jnumber);
        
        for ($n = $len; $n < $tot; $n++) {
            $jnumber = '0' . $jnumber;
        }
        
        return $jnumber;
    }
    
    private function invertDate($date)
    {  
        $parts = explode('-',$date);
        
        return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    }
    
    public function index()
    {
        $fsurveys = DB::table('surveys')
                ->join('surveytypes','surveytypes.id','=','surveys.surveytype_id')
                ->join('clients','clients.id','=','surveys.client_id')
                ->get();
        
        $surveys = array();
        
        foreach ($fsurveys as $cursurvey) {
            $mysurvey = $cursurvey;
            $mysurvey->surveydate = $this->invertDate($mysurvey->surveydate);
            
            $surveys[] = $mysurvey;
        }
        
        return view('surveys', ['surveys' => $surveys]);
    }
    
    public function getJob($job_number)
    {
        $survey = array(
            'id'                                =>  '',
            'jobnumber'                         =>  '',
            'ukasnumber'                        =>  '',
            'surveytype_id'                     =>  '',
            'client_id'                         =>  '',
            'surveydate'                        =>  '',
            'reinspectionof'                    =>  '',
            'siteaddress'                       =>  '',
            'sitedescription'                   =>  '',
            'scope'                             =>  '',
            'agreed_excluded_areas'             =>  '',
            'deviations_from_standard_methods'  =>  '',
            'lab_id'                            =>  '', 
            'issued_to'                         =>  '',
            'othersdates'                       =>  array(),
            'urgency'                           =>  'Standard',
        );
        
        $title = '';
        $jnumber = $job_number;
        
        if (is_numeric($job_number)) {
            $fsurvey = DB::table('surveys')
                    ->where('jobnumber','=',$job_number)
                    ->get()
                    ->first();
            
            $date_pieces = explode('-',$fsurvey->surveydate);
            $surveydate = $date_pieces[2] . '/' . $date_pieces[1] . '/' . $date_pieces[0];
            
            $myothersdates = explode('|',$fsurvey->othersdates);
            
            if (!empty($myothersdates)) {
                for ($k = 0; $k < count($myothersdates); $k++) {
                    $myothersdates[$k] = trim($myothersdates[$k]);
                    
                    if (empty($myothersdates[$k])) {
                        continue;
                    }
                    
                    $date_pieces = explode('-',$myothersdates[$k]);
                    $myothersdates[$k] = $date_pieces[2] . '/' . $date_pieces[1] . '/' . $date_pieces[0];
                }
            }
            
            $survey = array(
                'id'                                =>  $fsurvey->id,
                'jobnumber'                         =>  $fsurvey->jobnumber,
                'ukasnumber'                        =>  $fsurvey->ukasnumber,
                'surveytype_id'                     =>  $fsurvey->surveytype_id,
                'client_id'                         =>  $fsurvey->client_id,
                'surveydate'                        =>  $surveydate,
                'reinspectionof'                    =>  $fsurvey->reinspectionof,
                'siteaddress'                       =>  $fsurvey->siteaddress,
                'sitedescription'                   =>  $fsurvey->sitedescription,
                'scope'                             =>  $fsurvey->scope,
                'agreed_excluded_areas'             =>  $fsurvey->agreed_excluded_areas,
                'deviations_from_standard_methods'  =>  $fsurvey->deviations_from_standard_methods,
                'lab_id'                            =>  $fsurvey->lab_id,
                'issued_to'                         =>  $fsurvey->issued_to,
                'othersdates'                       =>  $myothersdates,
                'urgency'                           =>  $fsurvey->urgency,
            );
        } else {
            $fsurvey = DB::table('surveys')
                    ->get()
                    ->last();
            
            $jnumber = 1;
            
            if ($fsurvey !== null) {    
                $jnumber = 1 + $fsurvey->jobnumber;
            }
            
            $survey['jobnumber'] = $jnumber;
        }
        
        $jnumber = $this->normalizeJobNumber($jnumber,5);
        $survey['jobnumber'] = $this->normalizeJobNumber($survey['jobnumber'],5);
        $title = 'Job Number ' . (empty($survey['ukasnumber']) ? $jnumber : $survey['ukasnumber']);
        
        $surveys = DB::table('surveys')
                    ->get();
        
        $surveytypes = DB::table('surveytypes')
                    ->get();
        
        $clients = DB::table('clients')
                    ->get();
        
        $surveyors = DB::table('surveyors')
                    ->get();
        
        $labs = DB::table('labs')
                    ->get();
        
        $survey_surveyors = array();
        
        if (is_numeric($job_number)) {
            $survey_surveyors = DB::table('surveys_surveyors')
                                ->select('surveyor_id')
                                ->where('survey_id','=',$survey['id'])
                                ->get();
        }
        
        $passed_data = array(
            'job_number'        =>  $jnumber,
            'title'             =>  $title,
            'survey'            =>  $survey,
            'surveys'           =>  $surveys,
            'surveytypes'       =>  $surveytypes,
            'clients'           =>  $clients,
            'surveyors'         =>  $surveyors,
            'surveysurveyors'   =>  $survey_surveyors,
            'labs'              =>  $labs,
        );
        
        return view('survey', $passed_data);
    }
    
    public function saveJob(Request $request)
    { 
        $job_number = $request->input('jobnumber');
        
        $fsurvey = DB::table('surveys')
                    ->where('jobnumber','=',$job_number)
                    ->get()
                    ->first();
        
        $ukasnumber = $request->input('ukas_number');
        $reinspectionof = $request->input('reinspectionof');
        $surveytype_id = $request->input('surveytype_id');
        $client_id = $request->input('client_id');
        $surveyors = $request->input('surveyors');
        $surveydate = $request->input('surveydate');
        $lab_id = $request->input('lab_id');
        $issued_to = $request->input('issued_to');
        
        $date_pieces = explode('/',$surveydate);
        $surveydate = $date_pieces[2] . '-' . $date_pieces[1] . '-' . $date_pieces[0];
        
        $agreed_excluded_areas = $request->input('agreed_excluded_areas');
        $deviations_from_standard_methods = $request->input('deviations_from_standard_methods');
        $siteaddress = $request->input('siteaddress');
        $sitedescription = $request->input('sitedescription');
        $scope = $request->input('scope');
        
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        $othersdates = $request->input('othersdates');
        
        $urgency = $request->input('urgency');
        
        $data = array(
            'ukasnumber'                        =>  $ukasnumber,
            'reinspectionof'                    =>  $reinspectionof,
            'surveytype_id'                     =>  $surveytype_id,
            'client_id'                         =>  $client_id,
            'surveydate'                        =>  $surveydate,
            'agreed_excluded_areas'             =>  $agreed_excluded_areas,
            'deviations_from_standard_methods'  =>  $deviations_from_standard_methods,
            'siteaddress'                       =>  $siteaddress,
            'sitedescription'                   =>  $sitedescription,
            'scope'                             =>  $scope,
            'lab_id'                            =>  $lab_id,
            'issued_to'                         =>  $issued_to,
            'updated_at'                        =>  $modify_date,
            'othersdates'                       =>  (empty($othersdates) ? '' : implode('|',$othersdates)),
            'urgency'                           =>  $urgency,
        );
        
        if ($fsurvey === null) {
            $data['jobnumber'] = $job_number;
            $data['created_at'] = $creation_date;
            
            DB::table('surveys')
                ->insert($data);
        } else {
            DB::table('surveys')
                ->where('jobnumber', '=', $job_number)
                ->update($data);
        }
        
        $added_survey = DB::table('surveys')
                    ->where('jobnumber','=',$job_number)
                    ->get()
                    ->first();
        
        DB::table('surveys_surveyors')
            ->where('survey_id','=',$added_survey->id)
            ->delete();
        
        foreach ($surveyors as $cursurveyor) {
            $toinsert = array(
                'survey_id'     =>  $added_survey->id,
                'surveyor_id'   =>  $cursurveyor,
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            );
                    
            DB::table('surveys_surveyors')
                ->insert($toinsert);
        }
    }
    
    public function exportJobInfo(Request $request)
    { 
        $server_folder = public_path() . '/csv';
        
        if (!file_exists($server_folder)) {
            mkdir($server_folder);
            chmod($server_folder,0777);
        }
        
        $job_number = $request->input('job_number');
        $job_number = $this->normalizeJobNumber($job_number,5);     
        
        $fjob = DB::table('surveys')
                ->where('jobnumber','=',$job_number)
                ->get()
                ->first();

        $job_number = $fjob->ukasnumber;
        
        $surveytype_id = $request->input('surveytype_id');
        $client_id = $request->input('client_id');
        $siteaddress = $request->input('siteaddress');
        $sitedescription = $request->input('sitedescription');
        
        $sitedescription = preg_replace('/[\r\n]+/',' ', $sitedescription);
        
        $lab_id = $request->input('lab_id');
        $issued_to = $fjob->issued_to;
        
        $sitemock = explode("\n",$siteaddress);
        $siteaddress_parts = array();
        
        for ($k = 0; $k < count($sitemock); $k++) {
            $sitemock[$k] = trim($sitemock[$k]);
            
            if (empty($sitemock[$k])) {
                continue;
            }
            
            $siteaddress_parts[] = $sitemock[$k];
        }
        
        $urgency = $request->input('urgency');
        
        $surveytype = DB::table('surveytypes')
                ->where('id','=',$surveytype_id)
                ->get()
                ->first();
        
        $client = DB::table('clients')
                ->where('id','=',$client_id)
                ->get()
                ->first();
        
        $lab = DB::table('labs')
                ->where('id','=',$lab_id)
                ->get()
                ->first();
        
        $headers = array(
            'Job Number', 
            'Survey Type ID',
            'Survey Type Name',
            'Client Name',
            'Client Company Name',
            'Client Address 1',
            'Client Address 2',
            'Client City',
            'Client Postcode',
            'Site Address 1',
            'Site Address 2',
            'Site Address Town',
            'Site Address Postcode',
            'Site Description',
            'Lab ID',
            'Lab Name',
            'Lab Building',
            'Lab Address',
            'Lab City',
            'Lab Postcode',
            'Issued To',
            'Urgency',
        );
        
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
        
        $row = array(
            $job_number,
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
            $sitedescription,
            $lab->id,
            $lab->company,
            $lab->building,
            $lab->address,
            $lab->town,
            $lab->postcode,
            $issued_to,
            $urgency,
        );
        
        for ($k = 0; $k < count($headers); $k++) {
            $headers[$k] = '"' . $headers[$k] . '"';
        }
        
        for ($k = 0; $k < count($row); $k++) {
            $row[$k] = '"' . trim($row[$k]) . '"';
        }
        
        $str = implode(',',$headers);
        $str .= "\n";
        $str .= implode(',',$row);
        
        $relative_filename = $job_number . '.csv';
        $filename = $server_folder . '/' . $relative_filename;
        
        file_put_contents($filename, $str);
        
        return $relative_filename;
    }
    
    public function getFloors($ukas_number)
    {
        $fjob = DB::table('surveys')
                ->where('ukasnumber','=',$ukas_number)
                ->get()
                ->first();
        
        $ifloors = DB::table('inspections')
                ->where('inspections.survey_id','=',$fjob->id)
                ->select('floor_id')
                ->distinct()
                ->get();
        
        $floors = array();
        
        if (!empty($ifloors)) {
            $floors_ids = array();
        
            foreach ($ifloors as $curfloor) {
                $floors_ids[] = $curfloor->floor_id;
            }
            
            $floors = DB::table('floors')
                ->whereIn('id', $floors_ids)
                ->orderBy('menu','asc')
                ->get();
        }
        
        $passed_data = array(
            'title'         =>  'Inspections for Job Number ' . $ukas_number,
            'job_number'    =>  $fjob->jobnumber,
            'ukasnumber'    =>  $ukas_number,
            'nfloors'       =>  count($floors),
            'floors'        =>  $floors,
        );

        return view('inspections', $passed_data);
    }
    
    public function getInspections($ukas_number, $floor)
    {
        $fjob = DB::table('surveys')
                ->where('ukasnumber','=',$ukas_number)
                ->get()
                ->first();
        
        $inspections = DB::table('inspections AS insp')
                ->join('surveys','surveys.id','=','insp.survey_id')
                ->join('floors','floors.id','=','insp.floor_id')
                ->leftJoin('inspections AS ref','ref.id','=','insp.referral')
                ->where('surveys.ukasnumber','=',$ukas_number)
                ->where('floors.code','=',$floor)
                ->select('insp.*','ref.inspection_number AS referral_number')
                ->get();
        
        $refinspections = DB::table('inspections AS insp')
                ->join('surveys','surveys.id','=','insp.survey_id')
                ->leftJoin('inspections AS ref','ref.id','=','insp.referral')
                ->where('surveys.ukasnumber','=',$ukas_number)
                ->select('insp.*','ref.inspection_number AS referral_number')
                ->get();
        
        $rooms = DB::table('rooms')
                ->get();
        
        $products = DB::table('products')
                ->get();
        
        $extents = DB::table('extents')
                ->get();
        
        $surface_treatments = DB::table('surface_treatments')
                ->get();
        
        $passed_data = array(
            'title'             =>  'Inspections for Job Number ' . $fjob->ukasnumber,
            'job_number'        =>  $fjob->jobnumber,
            'ukasnumber'        =>  $ukas_number,
            'floor'             =>  $floor,
            'inspections'       =>  $inspections,
            'refinspections'    =>  $refinspections,
            'rooms'             =>  $rooms,
            'products'          =>  $products,
            'extents'           =>  $extents,
            'treatments'        =>  $surface_treatments,
        );
        
        return view('insptable', $passed_data);
    }
    
    public function saveInspections(Request $request)
    {
        $inspection_id = $request->input('inspection_id');
        
        $job_number = $request->input('job_number');
        
        $inspection_number = $request->input('inspection_number');
        $referenced = $request->input('referenced');
        $building = $request->input('building');
        $floor_id = $request->input('floor_id');
        $room_id = $request->input('room_id');
        $room_name = $request->input('room_name');
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $extent_of_damage = $request->input('extent_of_damage');
        $surface_treatment = $request->input('surface_treatment');
        $accessible = $request->input('accessible');
        $presumed = $request->input('presumed');
        $results = $request->input('results');
        $comments = $request->input('comments');
        $material_location = $request->input('material_location');
        $recommendations = $request->input('recommendations');
        $recommendationsNotes = $request->input('recommendationsNotes');
        $photo = $request->input('photo');
        $accessibility = $request->input('accessibility');
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        $fjob = DB::table('surveys')
                ->where('jobnumber','=',$job_number)
                ->get()
                ->first();
        
        $filename = $request->file('picture');
        
        if (!empty($filename)) {
            $inspections_folder = '/tablet';
            $fullpath = public_path() . $inspections_folder;

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            $job_folder = $inspections_folder . '/' . $fjob->ukasnumber;
            $fullpath .= '/' . $fjob->ukasnumber;

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            $pictures_folder = $job_folder . '/pictures';
            $fullpath .= '/pictures';

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            if (file_exists($filename) and is_readable($filename)) {
                move_uploaded_file($filename, $fullpath . '/' . $photo);
            }
        }
        
        $photo_to_save = "";
        if (empty($photo) or ("undefined" == $photo)) {
            $photo_to_save = "";
        } else {
            $photo_to_save = '/' . $fjob->ukasnumber . '/pictures/' . $photo;
        }
        
        $data = array(
            'inspection_number'     =>  $inspection_number,
            'referral'              =>  (!empty($referenced) ? $referenced : "NULL"),
            'building'              =>  trim($building),
            'room_name'             =>  trim($room_name),
            'quantity'              =>  trim($quantity),
            'accessible'            =>  $accessible,
            'presumed'              =>  $presumed,
            'results'               =>  trim($results),
            'comments'              =>  trim($comments),
            'material_location'     =>  trim($material_location),
            'recommendations'       =>  $recommendations,
            'recommendationsNotes'  =>  trim($recommendationsNotes),
            'photo'                 =>  $photo_to_save,
            'accessibility'         =>  $accessibility,
            'updated_at'            =>  $modify_date,
        );
        
        $data['floor_id'] = (empty($floor_id) ? "NULL" : $floor_id);
        $data['room_id'] = (empty($room_id) ? "NULL" : $room_id);
        $data['product_id'] = (empty($product_id) ? "NULL" : $product_id);
        $data['extent_of_damage'] = (empty($extent_of_damage) ? "NULL" : $extent_of_damage);
        $data['surface_treatment'] = (empty($surface_treatment) ? "NULL" : $surface_treatment);
        
        $myquery = 'UPDATE inspections SET ';
        foreach ($data as $key => $value) {
            if (("0" == $value) or ("1" == $value)) {
                $myquery .= '`' . $key . '` = ' . $value . ',';
            } else if ('NULL' != $value) {
                $myquery .= '`' . $key . '` = "' . $value . '",';
            } else {
                $myquery .= '`' . $key . '` = NULL,';
            }
        }
             
        $myquery = substr($myquery, 0, strlen($myquery) - 1);
        
        $myquery .= ' WHERE id = ' . $inspection_id;
        
        DB::statement($myquery);
    }
    
    public function saveInspection(Request $request)
    {
        $inspection_id = $request->input('inspection_id');
        
        $job_number = $request->input('job_number');
        
        $inspection_number = $request->input('inspection_number');
        $referenced = $request->input('referenced');
        $building = $request->input('building');
        $floor = $request->input('floor');
            
        $selfloor = DB::table('floors')
            ->where('code','=',$floor)
            ->get()
            ->first();

        $floor_id = $selfloor->id;
        
        $room_id = $request->input('room_id');
        $room_name = $request->input('room_name');
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $extent_of_damage = $request->input('extent_of_damage');
        $surface_treatment = $request->input('treatment');
        $accessible = $request->input('accessible');
        $presumed = $request->input('presumed');
        $results = $request->input('results');
        $comments = $request->input('comments');
        $material_location = $request->input('material_location');
        $recommendations = $request->input('recommendations');
        $recommendationsNotes = $request->input('recommendationsNotes');
        $photo = $request->input('photo');
        $accessibility = $request->input('accessibility');
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        $fjob = DB::table('surveys')
                ->where('jobnumber','=',$job_number)
                ->get()
                ->first();
        
        $filename = $request->file('picture');
        
        if (!empty($filename)) {
            $inspections_folder = '/tablet';
            $fullpath = public_path() . $inspections_folder;

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            $job_folder = $inspections_folder . '/' . $fjob->ukasnumber;
            $fullpath .= '/' . $fjob->ukasnumber;

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            $pictures_folder = $job_folder . '/pictures';
            $fullpath .= '/pictures';

            if (!file_exists($fullpath)) {
                mkdir($fullpath);
            }

            if (file_exists($filename) and is_readable($filename)) {
                move_uploaded_file($filename, $fullpath . '/' . $photo);
            }
        }
        
        $data = array(
            'inspection_number'     =>  $inspection_number,
            'building'              =>  trim($building),
            'room_name'             =>  trim($room_name),
            'quantity'              =>  trim($quantity),
            'accessible'            =>  $accessible,
            'presumed'              =>  $presumed,
            'results'               =>  trim($results),
            'comments'              =>  trim($comments),
            'material_location'     =>  trim($material_location),
            'recommendations'       =>  $recommendations,
            'recommendationsNotes'  =>  trim($recommendationsNotes),
            'photo'                 =>  (!empty($photo) ? ('/' . $fjob->ukasnumber . '/pictures/' . $photo) : ''),
            'accessibility'         =>  $accessibility,
            'updated_at'            =>  $modify_date,
        );
        
        $data['referral'] = (empty($referenced) ? "NULL" : $referenced);
        $data['floor_id'] = (empty($floor_id) ? "NULL" : $floor_id);
        $data['room_id'] = (empty($room_id) ? "NULL" : $room_id);
        $data['product_id'] = (empty($product_id) ? "NULL" : $product_id);
        $data['extent_of_damage'] = (empty($extent_of_damage) ? "NULL" : $extent_of_damage);
        $data['surface_treatment'] = (empty($surface_treatment) ? "NULL" : $surface_treatment);
        
        if ('new' == $inspection_id) {
            $data['survey_id'] = $fjob->id;
            $data['inspection_date'] = date('Y-m-d',time());
            $data['created_at'] = date('Y-m-d H:i:s',time());
            
            $myquery = 'INSERT INTO inspections SET ';
            foreach ($data as $key => $value) {
                if (("0" == $value) or ("1" == $value)) {
                    $myquery .= '`' . $key . '` = ' . $value . ',';
                } else if ('NULL' != $value) {
                    $myquery .= '`' . $key . '` = "' . $value . '",';
                } else {
                    $myquery .= '`' . $key . '` = NULL,';
                }
            }

            $myquery = substr($myquery, 0, strlen($myquery) - 1);

            DB::statement($myquery);
            
            $addedinsp = DB::table('inspections')
                ->where('survey_id','=',$fjob->id)
                ->where('inspection_number','=',$inspection_number)
                ->get()
                ->first();
            
            $inspection_id = $addedinsp->id;
        } else {
            $myquery = 'UPDATE inspections SET ';
            foreach ($data as $key => $value) {
                if (("0" == $value) or ("1" == $value)) {
                    $myquery .= '`' . $key . '` = ' . $value . ',';
                } else if ('NULL' != $value) {
                    $myquery .= '`' . $key . '` = "' . $value . '",';
                } else {
                    $myquery .= '`' . $key . '` = NULL,';
                }
            }

            $myquery = substr($myquery, 0, strlen($myquery) - 1);

            $myquery .= ' WHERE id = ' . $inspection_id;

            DB::statement($myquery);
        }
        
        if (!empty($referenced)) {
            $mydata = array('results' => $results);

            DB::table('inspections')
                ->where('referral', $inspection_id)
                ->update($mydata);
        }
    }
    
    public function showImport($job_number)
    {
        $fjob = DB::table('surveys')
                ->where('jobnumber','=',$job_number)
                ->get()
                ->first();
        
        $passed_data = array(
            'title'         =>  'Import Inspections for Job Number ' . $fjob->ukasnumber,
            'job_number'    =>  $job_number,
            'ukasnumber'    =>  $fjob->ukasnumber,
        );
        
        return view('import', $passed_data);
    }
    
    public function importCSV(Request $request)
    {
        $filename = $request->file('filename');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return array();
        }
        
        $content = file_get_contents($filename);
        
        $lines = preg_split('/[\r\n]{1,2}(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/',$content);
 
        if (count($lines) == 1) {
            $lines = explode("\n",$content);
        }
        
        $csv = array();
        
        foreach($lines as $row) {
            $csv[] = str_getcsv($row);
        }
        
        $first_line_inspections = 2;
        
        if ('Report Number' == $csv[0][0]) {            
            $first_line_inspections = 1;
        }
        
        $ukasnumber = $csv[$first_line_inspections][0];
        
        $parts = explode("-",$ukasnumber);
        
        $number = ((2 == count($parts)) ? $parts[1] : $parts[0]);
        
        if ('Report Number' == $csv[1][0]) {            
            $site_description = $csv[0][0];
            
            $mdate = date('Y-m-d H:i:s',time());
            
            $mydata = array(
                'sitedescription' =>  $site_description,
                'updated_at'      =>  $mdate,
            );
            
            DB::table('surveys')
                ->where('ukasnumber', '=', $number)
                ->update($mydata);
        }
        
        $survey_id = DB::table('surveys')
                    ->where('ukasnumber','=',$number)
                    ->get()
                    ->first();
        
        foreach ($csv as $i => $line) {
            if ($i < $first_line_inspections) {
                continue;
            }
            
            if (empty($line[0])) {
                break;
            }
            
            $insp_referral = null;
            
            if (!empty($line[2])) {
                $insp_referral = DB::table('inspections')
                        ->where([
                            ['inspection_number','=', $line[2]],
                            ['survey_id', '=', $survey_id->id]
                        ])->get()
                        ->first();
            }
            
            $creation_date = date('Y-m-d H:i:s',time());
            $modify_date = date('Y-m-d H:i:s',time());
            
            $floor = DB::table('floors')
                    ->where('name','=',$line[6])
                    ->get()
                    ->first();
            
            $myroom = $line[7];
            if (strlen($myroom) < 2) {
                $myroom = '0' . $myroom;
            }
            
            $room = DB::table('rooms')
                    ->where('name','=',$myroom)
                    ->get()
                    ->first();
            
            $room_name = $line[8];
            
            $product = null;
            
            if (!empty($line[9])) {
                $product = DB::table('products')
                        ->where('name','=',$line[9])
                        ->get()
                        ->first();
            }
            
            $myextent = $line[10];
            
            $extent = DB::table('extents')
                    ->where('name','=',$line[11])
                    ->get()
                    ->first();
            
            $surface_treatment = DB::table('surface_treatments')
                    ->where('description','=',$line[12])
                    ->get()
                    ->first();
            
            $curinsp = DB::table('inspections')
                        ->where([
                            ['inspection_number','=', $line[1]],
                            ['survey_id', '=', $survey_id->id]
                        ])->get()
                        ->first();
        
            $data = array(
                'inspection_number'     =>  $line[1],
                'survey_id'             =>  $survey_id->id,
                'building'              =>  $line[5],
                'room_name'             =>  $room_name,
                'quantity'              =>  $myextent,
                'accessible'            =>  ((('Inaccessible' == $line[3]) or ('Limited Access' == $line[3])) ? '0' : '1'),
                'presumed'              =>  (('Presumed' == $line[3]) ? '1' : '0'),
                'observation'           =>  (('Observation' == $line[3]) ? '1' : '0'),
                'reinspection'          =>  (('Reinspection' == $line[3]) ? '1' : '0'),
                'results'               =>  "",
                'photo'                 =>  ('/' . $number . '/pictures/' . $line[13]),
                'comments'              =>  $line[14],
                'material_location'     =>  $line[15],
                'recommendations'       =>  (empty($line[16]) ? '' : $line[16]),
                'recommendationsNotes'  =>  (empty($line[17]) ? '' : $line[17]),
                'inspection_date'       =>  (empty($line[18]) ? '' : $line[18]),
                'accessibility'         =>  (empty($line[21]) ? '' : $line[21]),
                'created_at'            =>  $creation_date,
                'updated_at'            =>  $modify_date,
            );
            
            if ('Clear' == $data['recommendations']) {
                $data['recommendations'] = '';
            }
            
            if (empty($line[13])) {
                $data['photo'] = '';
            }
            
            if ($floor !== null) {
                $data['floor_id'] = $floor->id;
            }
            
            if ($room !== null) {
                $data['room_id'] = $room->id;
            }
            
            if ($product !== null) {
                $data['product_id'] = $product->id;
            }
            
            if ($extent !== null) {
                $data['extent_of_damage'] = $extent->id;
            }
            
            if ($surface_treatment !== null) {
                $data['surface_treatment'] = $surface_treatment->id;
            }
            
            if ($insp_referral !== null) {
                $data['referral'] = $insp_referral->id;
            }
            
            if ($curinsp === null) {
                DB::table('inspections')
                    ->insert($data);
            } else {
                DB::table('inspections')
                    ->where([
                            ['inspection_number','=', $line[1]],
                            ['survey_id', '=', $survey_id->id]
                    ])
                    ->update($data);
            }
        }
        
        $inspections = DB::table('inspections')
            ->where('survey_id', $survey_id->id)
            ->get();

        return $inspections;
    }
    
    public function uploadFile(Request $request)
    {
        $filename = $request->file('filename');
        $originalFileName = strtolower($filename->getClientOriginalName());
        $comments = $request->input('comments');
        $jobnumber = $request->input('jobnumber');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
 
        $survey_id = DB::table('surveys')
                    ->select('id','ukasnumber')
                    ->where('jobnumber','=',$jobnumber)
                    ->get()
                    ->first();
        
        $full_dest_path = public_path() . '/files/';
        
        $ext = substr($originalFileName, -3);
        
        $finalname = str_replace(' ','_',$originalFileName);
        
        if (strpos($originalFileName,$survey_id->ukasnumber . '_') === false) {
            $finalname = $survey_id->ukasnumber . '_' . $finalname;
        }
        
        $old_name = $finalname;
        
        if ('pdf' == $ext) {
            $old_name = str_replace('.pdf','_old.pdf',$old_name);
        }
        
        $filename->move($full_dest_path,$old_name);
        
        $oldfile = $full_dest_path . '/' . $old_name;
        $newfile = $full_dest_path . '/' . $finalname;
        
        if ('pdf' == $ext) {
            shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . $newfile . ' ' . $oldfile);
            unlink($oldfile);
        }
        
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());

        $data = array(
            'survey_id'         =>  $survey_id->id,
            'comments'          =>  $comments,
            'path'              =>  $finalname,
            'created_at'        =>  $creation_date,
            'updated_at'        =>  $modify_date,
        );

        $retId = DB::table('files')
            ->insertGetId($data);
        
        $arr = array('id' => $retId, 'name' => $finalname, 'comments' => $comments);

        return json_encode($arr);
    }
    
    public function removeFile(Request $request)
    {
        $fileid = $request->input('id');
 
        $file = DB::table('files')
                    ->select('id','path')
                    ->where('id','=',$fileid)
                    ->get()
                    ->first();
        
        $fullpath = public_path() . '/files/' . $file->path;
        
        if (file_exists($fullpath) and ! is_dir($fullpath)) {
            unlink($fullpath);
        }
        
        DB::table('files')
            ->where('id','=',$fileid)
            ->delete();

        return '1';
    }
    
    public function exportInspections(Request $request)
    {
        $ukas_number = $request->input('job_number');
        $floor = $request->input('floor');
        
        $survey = DB::table('surveys')
                ->join('surveytypes','surveytypes.id','=','surveys.surveytype_id')
                ->where('surveys.ukasnumber','=',$ukas_number)
                ->select('surveys.sitedescription AS sitedescription','surveytypes.surveytype AS surveytype')
                ->get()
                ->first();
        
        $inspections = DB::table('inspections AS insp')
                ->join('surveys','surveys.id','=','insp.survey_id')
                ->leftJoin('inspections AS ref','ref.id','=','insp.referral')
                ->join('floors','floors.id','=','insp.floor_id')
                ->join('rooms','rooms.id','=','insp.room_id')
                ->leftJoin('products','products.id','=','insp.product_id')
                ->leftJoin('extents','extents.id','=','insp.extent_of_damage')                
                ->leftJoin('surface_treatments','surface_treatments.id','=','insp.surface_treatment')
                ->where('surveys.ukasnumber','=',$ukas_number)
                ->where('floors.code','=',$floor)
                ->select('insp.*','ref.inspection_number AS referral_number','floors.name AS floor_name','rooms.name AS room_code','products.name AS product_name','extents.name AS damextent','surface_treatments.description AS surftreatment')
                ->orderBy('insp.created_at','asc')
                ->get();
        
        $surveyor = DB::table('surveys')
                ->join('surveys_surveyors','surveys_surveyors.survey_id','=','surveys.id')
                ->join('surveyors','surveyors.id','=','surveys_surveyors.surveyor_id')
                ->where('surveys.ukasnumber','=',$ukas_number)
                ->select('surveyors.name AS name','surveyors.surname AS surname')
                ->get()
                ->first();
        
        $inits = $surveyor->name[0] . $surveyor->surname[0];
     
        $server_folder = public_path() . '/csv';
        
        if (!file_exists($server_folder)) {
            mkdir($server_folder);
            chmod($server_folder,0777);
        }
        
        $lines = array();
        $lines[] = array($survey->sitedescription,"","","","","","","","","","","","","","","","","","","");
        $lines[] = array(
            "Report Number",
            "Inspection Number",
            "Referred Inspection Number",
            "Access",
            "Inspection Type",
            "Building",
            "Floor",
            "Room",
            "Room Name",
            "Product Type",
            "Extent Measurement",
            "Extent of Damage",
            "Surface Treatment",
            "Photo",
            "Comments",
            "Material Location",
            "Recommendations",
            "Recommendation Notes",
            "Date",
            "Surveyor",
            "Full path",
            "Accessibility"
        );
        
        foreach ($inspections as $curinsp) {
            $access = 'Sample taken';
            
            if (1 == $curinsp->observation) {
                $access = 'Observation';
            }
            
            if (1 == $curinsp->presumed) {
                $access = 'Presumed';
            }
            
            if (1 == $curinsp->reinspection) {
                $access = 'Reinspection';
            }
            
            if (empty($curinsp->accessible)) {
                $access = 'Inaccessible';
            }
            
            if (!empty($curinsp->referral)) {
                $access = 'Referred';
            }
            
            $pict = str_replace('/' . $ukas_number . '/pictures/','',$curinsp->photo);
            
            $lines[] = array(
                $inits . '-' . $ukas_number,
                $curinsp->inspection_number,
                $curinsp->referral_number,
                $access,
                $survey->surveytype,
                $curinsp->building,
                $curinsp->floor_name,
                $curinsp->room_code,
                $curinsp->room_name,
                $curinsp->product_name,
                $curinsp->quantity,
                $curinsp->damextent,
                $curinsp->surftreatment,
                $pict,
                $curinsp->comments,
                $curinsp->material_location,
                $curinsp->recommendations,
                $curinsp->recommendationsNotes,
                $curinsp->inspection_date,
                $inits,
                '/storage/emulated/0/SurveyFiles/' . $ukas_number . '/Pictures/' . $pict,
                (empty($curinsp->accessibility) ? '' : $curinsp->accessibility),
            );
        }
        
        $rows = array();
        
        for ($k = 0; $k < count($lines); $k++) {
            for ($j = 0; $j < count($lines[$k]); $j++) {
                $lines[$k][$j] = '"' . $lines[$k][$j] . '"';
            }
            
            $rows[$k] = implode(',',$lines[$k]);
        }
        
        $relative_filename = $ukas_number . '_' . $floor . '_inspections.csv';
        $filename = $server_folder . '/' . $relative_filename;
        
        file_put_contents($filename, implode("\n",$rows));
        
        return $relative_filename;
    }
    
    public function deleteInspection(Request $request)
    {
        $id = $request->input('id');
        
        DB::table('inspections')
            ->where('id','=',$id)
            ->delete();

        return '1';
    }
    
    public function showIssues($job_number)
    {
        $title = '';
        $jnumber = $job_number;
        
        $survey = DB::table('surveys')
                ->where('surveys.jobnumber','=',$job_number)
                ->get()
                ->first();
        
        $issues = DB::table('reports_issues')
                ->where('survey_id','=',$survey->id)
                ->orderBy('revision', 'asc')
                ->get()
                ->all();
        
        $title = 'Issues for Report ' . $survey->ukasnumber;
        
        $passed_data = array(
            'job_number'    =>  $jnumber,
            'title'         =>  $title,
            'issues'        =>  $issues,
            'ukasnumber'    =>  $survey->ukasnumber,
        );
        
        return view('reports_issues', $passed_data);
    }
    
    public function showIssue($ukasnumber,$revision)
    {
        $title = '';
        $jnumber = $ukasnumber;
        
        $survey = DB::table('surveys')
                ->where('surveys.ukasnumber','=',$ukasnumber)
                ->get()
                ->first();
        
        $last = DB::table('reports_issues')
                ->where('survey_id','=',$survey->id)
                ->get()
                ->last();
        
        $new_revision = 1;
        
        if ($last !== null) {
            $new_revision = 1 + $last->revision;
        }
        
        $issue = DB::table('reports_issues')
                ->where('survey_id','=',$survey->id)
                ->where('revision','=',$revision)
                ->get()
                ->first();
        
        if ($issue !== null) {
            $issue->authors = explode('|',$issue->authors);
            $issue->surveyors = explode('|',$issue->surveyors);
            $issue->issued_to = explode('|',$issue->issued_to);
            $issue->date_completed = date('d/m/Y',strtotime($issue->date_completed));
            $issue->date_checked = date('d/m/Y',strtotime($issue->date_checked));
            $issue->date_authorised = date('d/m/Y',strtotime($issue->date_authorised));
            $issue->date_issued = date('d/m/Y',strtotime($issue->date_issued));
        } else {
            $issue = new \stdClass();
            $issue->survey_id = $survey->id;
            $issue->revision = $new_revision;
            $issue->authors = array();
            $issue->authors_signatures = array();
            $issue->date_completed = date('d/m/Y',time());
            $issue->surveyors = array();
            $issue->surveyors_signatures = array();
            $issue->date_checked = date('d/m/Y',time());
            $issue->quality_check = '';
            $issue->date_authorised = date('d/m/Y',time());
            $issue->date_issued = date('d/m/Y',time());
            $issue->issued_to = array();
        }
        
        $title = 'Report ' . $jnumber . ' - Issue ' . $issue->revision;
        
        $surveyors = DB::table('surveyors')
                    ->get();
        
        $passed_data = array(
            'title'         =>  $title,
            'issue'         =>  $issue,
            'survey'        =>  $survey,
            'surveyors'     =>  $surveyors,
        );
        
        return view('report_issue', $passed_data);
    }
    
    public function saveSurveyReportRevision(Request $request)
    {
        $survey_id = $request->input('survey_id');
        $revision = $request->input('revision');
        
        $authors = explode('|',$request->input('authors'));
        
        $auths = array();
        
        foreach ($authors as $curauthor) {
            if (empty($curauthor)) {
                continue;
            }
            
            $auths[] = $curauthor;
        }
        
        $authors_signatures = '';
        
        if (!empty($authors)) {
            $auth_signs = array();
            
            foreach ($authors as $curauthor) {
                if (empty($curauthor)) {
                    continue;
                }
                
                $name = strtolower(str_replace(' ','_',$curauthor));
                
                $auth_signs[] = $name . '.jpg';
            }
            
            $authors_signatures = implode('|',$auth_signs);
        }
        
        $surveyors = explode('|',$request->input('surveyors'));
        
        $survs = array();
        
        foreach ($surveyors as $cursurveyor) {
            if (empty($cursurveyor)) {
                continue;
            }
            
            $survs[] = $cursurveyor;
        }
        
        $surveyors_signatures = '';
        
        if (!empty($surveyors)) {
            $surv_signs = array();
            
            foreach ($surveyors as $cursurveyor) {
                if (empty($cursurveyor)) {
                    continue;
                }
                
                $name = strtolower(str_replace(' ','_',$cursurveyor));
                
                $surv_signs[] = $name . '.jpg';
            }
            
            $surveyors_signatures = implode('|',$surv_signs);
        }
        
        $quality_signature = '';
        
        if (!empty($request->input('quality_check'))) {
            $name = strtolower(str_replace(' ','_',$request->input('quality_check')));
            $quality_signature = $name . '.jpg';
        }
        
        $issued_tos = explode('|',$request->input('issued_tos'));
        $issueds = array();
        
        foreach ($issued_tos as $curissued) {
            if (empty($curissued)) {
                continue;
            }
            
            $issueds[] = $curissued;
        }
        
        $create_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        $date_completed = '';
        if (!empty($request->input('date_completed'))) {
            $parts = explode('/',$request->input('date_completed'));
            
            $date_completed = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        
        $date_authorised = '';
        if (!empty($request->input('date_authorised'))) {
            $parts = explode('/',$request->input('date_authorised'));
            
            $date_authorised = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        
        $date_issued = '';
        if (!empty($request->input('date_issued'))) {
            $parts = explode('/',$request->input('date_issued'));
            
            $date_issued = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        
        $date_checked = '';
        if (!empty($request->input('date_checked'))) {
            $parts = explode('/',$request->input('date_checked'));
            
            $date_checked = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        }
        
        $data = array(
            'survey_id'                         =>  $survey_id,
            'revision'                          =>  $revision,
            'authors'                           =>  (empty($auths) ? '' : implode('|',$auths)),
            'authors_signatures'                =>  $authors_signatures,
            'surveyors'                         =>  (empty($survs) ? '' : implode('|',$survs)),
            'surveyors_signatures'              =>  $surveyors_signatures,
            'date_completed'                    =>  $date_completed,
            'date_issued'                       =>  $date_issued,
            'date_authorised'                   =>  $date_authorised,
            'issued_to'                         =>  (empty($issueds) ? '' : implode('|',$issueds)),
            'date_checked'                      =>  $date_checked,
            'quality_check'                     =>  $request->input('quality_check'),
            'quality_signature'                 =>  $quality_signature,
            'updated_at'                        =>  $modify_date,
        );      
        
        $exist = DB::table('reports_issues')
                ->where('survey_id','=',$survey_id)
                ->where('revision','=',$revision)
                ->get()
                ->first();
        
        if ($exist == null) {
            $data['created_at'] = $create_date;
            
            DB::table('reports_issues')
                    ->insert($data);
        } else {
            DB::table('reports_issues')
                    ->where('survey_id','=',$survey_id)
                    ->where('revision','=',$revision)
                    ->update($data);
        }
        
        return 1;
    }
    
    public function deleteSurveyReportRevision(Request $request)
    {
        $id = $request->input('id');
            
        DB::table('reports_issues')
            ->where('id',$id)
            ->delete();
        
        return 1;
    }
}
