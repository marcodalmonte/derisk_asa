<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RemovalsController extends Controller
{
    private function cleanText($text)
    {
        $clean = $text;
        
        $clean = str_replace("\t\n","\n",$clean);
        $clean = str_replace("\n"," ",$clean);
        $clean = preg_replace("/<font color=\"#365f91\">(.+?)<\/font>/is","<bcolored>$1</bcolored>",$clean);
        $clean = preg_replace("/<font (.+?)>(.+?)<\/font>/is","$2",$clean);
        $clean = preg_replace("/<bcolored><b>(.+?)<\/b><\/bcolored>/is","<bcolored>$1</bcolored>",$clean);
        $clean = preg_replace("/<bcolored>(.+?)<br>(.+?)<\/bcolored>/is","<bcolored>$1 $2</bcolored>",$clean);
        $clean = preg_replace("/<li><br>(.+?)<\/li>/is","<li>$1</li>",$clean);
        $clean = preg_replace("/<li>(\s+)<br>(.+?)<\/li>/is","<li>$1</li>",$clean);
        $clean = preg_replace("/<li>(.+?)<br><\/li>/is","<li>$1</li>", $clean);
        $clean = preg_replace("/<li>(.+?)<br>(\s+)<\/li>/is","<li>$1</li>", $clean);
        $clean = preg_replace("/<li>(.+?)<br>(.+?)<\/li>/is","<li>$1 $2</li>", $clean);
        $clean = preg_replace("/<li><br>(.+?)<br>(.+?)<br><\/li>/is","<li>$1 $2</li>",$clean);
        $clean = preg_replace("/<li><br>(.+?)<br>(.+?)<br>(\s+)<\/li>/is","<li>$1 $2</li>",$clean);
        $clean = preg_replace("/(\s+)/is"," ",$clean);
        $clean = preg_replace("/<p (.+?)>(.+?)<\/p>/is","<p>$2</p>",$clean);
        $clean = preg_replace("/<p><font (.+?)>(.+?)<\/font><\/p>/is","<p>$2</p>",$clean);
        $clean = str_replace("<p><br>","<br>",$clean);
        $clean = str_replace("<br></p>","<br>",$clean);
        $clean = str_replace("<li><p>","<li>",$clean);
        $clean = str_replace("</p></li>","</li>",$clean);
        $clean = preg_replace("/(.+?)<style(.+?)>(.+?)<\/style>(.+?)<p>(.+?)/is","<p>$5",$clean);
        $clean = strip_tags($clean,"<br><b><bcolored><span><ul><li><p>");
        $clean = preg_replace("/<bcolored>(.+?)<\/bcolored>/is","<font color=\"#365f91\">$1</font>",$clean);
        $clean = preg_replace("/(.+?)<br><br>/is","$1",$clean);
        $clean = preg_replace("/<\/p><br><p>/is","</p><cbr></p>",$clean);
        $clean = str_replace("<br>"," ",$clean);
        $clean = str_replace("<cbr>","<br>",$clean);
        $clean = str_replace("</ul>","</ul><br>",$clean);
        $clean = preg_replace("/(.+?)<br><\/p>(.+?)/is","$1<br><p>$2",$clean);
        $clean = preg_replace("/<\/b>(\s+)<p>(.+?)/is","</b></p><br><p>$2",$clean);
        $clean = preg_replace("/<p>(\s+)<\/p>/is","",$clean);
        $clean = preg_replace("/.(\s+)<p>/is",".</p><br><p>",$clean);
        $clean = str_replace("</p.","</p>",$clean);
        $clean = str_replace("</p></p>","</p>",$clean);
        $clean = preg_replace("/<\/p>(\s+)<\/p>/is","</p>",$clean);
        $clean = preg_replace("/<li.<\/p><br><p>(.+?)<\/p>(\s+)<\/li>/is","<li>$1</li>",$clean);
        $clean = str_replace("<br.</p><br>","<br><br>",$clean);
        $clean = preg_replace("/<br>(\s+)<\/p><br>/is","<br><br>",$clean);
        $clean = preg_replace("/(\s+)<\/b>(\s+)<\/p>/is","</b></p>",$clean);
        $clean = preg_replace("/(\s+)<\/p>/is","</p>",$clean);
        $clean = preg_replace("/(\s+)<ul>/is","<ul>",$clean);
        $clean = preg_replace("/<p>(.+?)<\/p>/is","<span>$1</span>",$clean);
        $clean = preg_replace("/<b>(.+?)<\/b>/is","<span style=\"font-weight:bold;\">$1</span>",$clean);
        $clean = preg_replace("/<span><font (.+?)>(.+?)<\/font><\/span>/is","<font $1>$2</font><br>",$clean);
        $clean = preg_replace("/<span><span style=\"font-weight:bold;\">(.+?)<\/span><\/span>/is","<span style=\"font-weight:bold;\">$1</span>",$clean);
        $clean = str_replace("span><span","span><br><br><span",$clean);
        $clean = str_replace("span><br><span","span><br><br><span",$clean);
        $clean = str_replace("span><br><font","span><br><br><font",$clean);
        $clean = str_replace("span><ul","span><br><ul",$clean);
        $clean = preg_replace("/font>(\s+)<span/is","font><br><br><span",$clean);
        $clean = preg_replace("/font><span/is","font><br><br><span",$clean);
        $clean = preg_replace("/span><font/is","span><br><br><font",$clean);
        $clean = preg_replace("/ul><br><span/is","ul><br><br><span",$clean);
        $clean = preg_replace("/ul><br><font/is","ul><br><br><font",$clean);
        $clean = preg_replace("/ul>(\s+)<br><span/is","ul><br><br><span",$clean);
        $clean = preg_replace("/ul>(\s+)<br><font/is","ul><br><br><font",$clean);
        $clean = preg_replace("/ul><br>(\s+)<span/is","ul><br><br><span",$clean);
        $clean = preg_replace("/ul><br>(\s+)<font/is","ul><br><br><font",$clean);
        $clean = preg_replace("/span>(\s+)<span/is","span><br><span",$clean);
        $clean = str_replace("</p>","<br>",$clean);
        
        return $clean;
    }
    
    public function index()
    {
        $fremovals = DB::table('removals')
                ->get();
        
        $removals = array();
        
        foreach ($fremovals as $fremoval) {
            $myrem = $fremoval;
            
            if (!empty($myrem->print_date)) {
                $myrem->print_date = date('d/m/Y',strtotime($fremoval->print_date));
            }
            
            $myrem->surveys = explode('|',$fremoval->surveys);
            
            $myrem->client_name = DB::table('surveys')
                    ->join('clients','clients.id','=','surveys.client_id')
                    ->select('clients.companyname AS client_name')
                    ->where('surveys.id','=',$myrem->surveys[0])
                    ->get()
                    ->first()
                    ->client_name;
            
            $myrem->address = explode("\n",$myrem->address);
            
            $myrem->print_date = date('d/m/Y',strtotime($myrem->created_at));
            
            if (!isset($removals[$myrem->project_ref])) {
                $removals[$myrem->project_ref] = array();
            }
            
            $removals[$myrem->project_ref][] = $myrem;
        }
        
        $dataToPass = array(
            'removals'  =>  $removals,
        );
        
        return view('removals', $dataToPass);
    }
    
    public function getRemoval($spec)
    {
        $removal = array(
            'id'                        =>  '',
            'area'                      =>  '',
            'address'                   =>  '',
            'surveys'                   =>  array(),
            'prepared_for'              =>  '',
            'project_ref'               =>  '',
            'prepared_by'               =>  '',
            'prepared_by_signature'     =>  '',
            'preparation_date'          =>  '',
            'approved_by'               =>  '',
            'approved_by_signature'     =>  '',
            'approval_date'             =>  '',
            'preliminaries'             =>  '',
            'site_picture'              =>  '',
            'map_picture'               =>  '',
            'floor_plans'               =>  '',
            'access_routes'             =>  '',
            'bulk_analysis_certificate' =>  '',
            'general_requirements'      =>  '',
            'tender_submission'         =>  '',
            'comments'                  =>  '',
            'print_date'                =>  '',
            'include_access_routes'     =>  '',
        );      
        
        if ('new' != $spec) {        
            $fremoval = DB::table('removals')
                    ->where('id','=',$spec)
                    ->get()
                    ->first();
            
            $prepdate = date('d/m/Y',strtotime($fremoval->preparation_date));
            $appdate = date('d/m/Y',strtotime($fremoval->approval_date));
            $print_date = date('d/m/Y',strtotime($fremoval->created_at));
            
            $removal = array(
                'id'                        =>  $fremoval->id,
                'area'                      =>  $fremoval->area,
                'address'                   =>  $fremoval->address,
                'surveys'                   =>  explode(';',$fremoval->surveys),
                'prepared_for'              =>  $fremoval->prepared_for,
                'project_ref'               =>  $fremoval->project_ref,
                'prepared_by'               =>  $fremoval->prepared_by,
                'prepared_by_signature'     =>  $fremoval->prepared_by_signature_path,
                'preparation_date'          =>  $prepdate,
                'approved_by'               =>  $fremoval->approved_by,
                'approved_by_signature'     =>  $fremoval->approved_by_signature_path,
                'approval_date'             =>  $appdate,
                'preliminaries'             =>  $fremoval->preliminaries,
                'site_picture'              =>  $fremoval->site_picture_path,
                'map_picture'               =>  $fremoval->map_picture_path,
                'floor_plans'               =>  $fremoval->floor_plans_path,
                'access_routes'             =>  $fremoval->access_routes_path,
                'bulk_analysis_certificate' =>  $fremoval->bulk_analysis_certificate_path,
                'general_requirements'      =>  $fremoval->general_requirements,
                'tender_submission'         =>  $fremoval->tender_submission,
                'comments'                  =>  $fremoval->comments,
                'print_date'                =>  $print_date,
                'include_access_routes'     =>  $fremoval->include_access_routes,
            );
        }
        
        $fsurveys = DB::table('surveys')
                ->select('id AS id','ukasnumber AS ukasnumber')
                ->get();
        
        $surveys = array();
        
        foreach ($fsurveys as $fcur) {
            $surveys[] = array('id' => $fcur->id, 'ukasnumber' => $fcur->ukasnumber,);
        }
        
        $areas = DB::table('removals_areas')
                    ->where('removal_id','=',$spec)
                    ->get()
                    ->all();
        
        if (!empty($areas)) {
            for ($n = 0; $n < count($areas); $n++) {
                $areas[$n]->inspections = DB::table('removals_inspections')
                        ->where('area_id','=',$areas[$n]->id)
                        ->get()
                        ->all();
            }
        }
        
        $title = 'New Removal';
        
        $dataToPass = array(
            'title'         =>  $title,
            'removal'       =>  $removal,
            'surveys'       =>  $surveys,
            'areas'         =>  $areas,
        );
        
        return view('removal', $dataToPass);
    }
    
    public function getSurveyAddress(Request $request)
    {
        $survey_id = $request->get('survey_id');
        
        $selsurvey = DB::table('surveys')
                    ->select('siteaddress')
                    ->where('id','=',$survey_id)
                    ->get()
                    ->first();
        
        return str_replace("\n"," ",$selsurvey->siteaddress);
    }
    
    public function saveRemoval(Request $request)
    {
        $id = $request->input('id');
        $surveys = $request->input('surveys');
        $project_ref = $request->input('project_ref');
        $area = $request->input('area');
        $address = $request->input('address');
        $prepared_for = $request->input('prepared_for');
        $prepared_by = $request->input('prepared_by');
        $prepared_by_signature = $request->input('prepared_by_signature');
        $preparation_date = $request->input('preparation_date');
        $approved_by = $request->input('approved_by');
        $approved_by_signature = $request->input('approved_by_signature');
        $approval_date = $request->input('approval_date');
        $preliminaries = $this->cleanText($request->input('preliminaries'));
        $site_picture = $request->input('site_picture');
        $map_picture = $request->input('map_picture');
        $floor_plans = $request->input('floor_plans');
        $access_routes = $request->get('access_routes');
        $bulk_analysis_certificate = $request->input('bulk_analysis_certificate');
        $general_requirements = $this->cleanText($request->input('general_requirements'));
        $tender_submission = $this->cleanText($request->input('tender_submission'));
        $include_access_routes = $request->get('include_access_routes');
        $revision_comments = $request->input('revision_comments');
        $new_revision = $request->input('new_revision');
        
        $issue_date = time();
        $creation_date = date('Y-m-d H:i:s',$issue_date);
        $modify_date = date('Y-m-d H:i:s',$issue_date);
        
        $parts = explode('/',$preparation_date);
        
        $preparation_date = date('Y-m-d H:i:s',strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]));
        
        if (!empty($approval_date)) {
            $parts = explode('/',$approval_date);

            $approval_date = date('Y-m-d H:i:s',strtotime($parts[2] . '-' . $parts[1] . '-' . $parts[0]));
        }
        
        $new = false;
        
        if (('new' == $id) or ('yes' == $new_revision)) {
            if ('new' == $id) {
                $new = true;
            }
            
            $id = DB::table('removals')
                ->insertGetId([
                    'surveys'                           =>  implode(';',$surveys),
                    'project_ref'                       =>  $project_ref,
                    'area'                              =>  $area,
                    'address'                           =>  $address,
                    'prepared_for'                      =>  $prepared_for,
                    'prepared_by'                       =>  $prepared_by,
                    'prepared_by_signature_path'        =>  str_replace('/removals/','/',$prepared_by_signature),
                    'preparation_date'                  =>  $preparation_date,
                    'approved_by'                       =>  $approved_by,
                    'approved_by_signature_path'        =>  str_replace('/removals/','/',$approved_by_signature),
                    'approval_date'                     =>  $approval_date,
                    'preliminaries'                     =>  $preliminaries,
                    'site_picture_path'                 =>  str_replace('/removals/','/',$site_picture),
                    'map_picture_path'                  =>  str_replace('/removals/','/',$map_picture),
                    'floor_plans_path'                  =>  str_replace('/removals/','/',$floor_plans),
                    'access_routes_path'                =>  str_replace('/removals/','/',$access_routes),
                    'bulk_analysis_certificate_path'    =>  str_replace('/removals/','/',$bulk_analysis_certificate),
                    'general_requirements'              =>  $general_requirements,
                    'tender_submission'                 =>  $tender_submission,
                    'include_access_routes'             =>  $include_access_routes,
                    'comments'                          =>  $revision_comments,
                    'created_at'                        =>  $creation_date,
                    'updated_at'                        =>  $modify_date,
                ]);
            
            $inspections = array();
            
            if ($new) {
                // If it is a new removal, I copy the information from the inspections table
                
                // Extracting the buildings (might be only one)
                $buildings = DB::table('inspections')
                        ->select('building')
                        ->whereIn('survey_id', $surveys)
                        ->whereNotIn('results', array('','Non-asbestos'))
                        ->distinct()
                        ->get();
                
                foreach ($buildings as $curbuilding) {
                    // For each building I check the floors having asbestos
                    
                    $floors = DB::table('inspections')
                        ->select('floor_id')
                        ->whereIn('survey_id', $surveys)
                        ->whereNotIn('results', array('','Non-asbestos'))
                        ->where('building','=',$curbuilding->building)
                        ->distinct()
                        ->get();
                    
                    foreach ($floors as $curfloor) {
                        $floorname = DB::table('floors')
                            ->select('name')
                            ->where('id','=',$curfloor->floor_id)
                            ->get()
                            ->first()
                            ->name;
                        
                        // For each floor I check the room having asbestos
                        
                        $rooms = DB::table('inspections')
                            ->select('room_name')
                            ->whereIn('survey_id', $surveys)
                            ->whereNotIn('results', array('','Non-asbestos'))
                            ->where('floor_id','=',$curfloor->floor_id)
                            ->distinct()
                            ->get();

                        foreach ($rooms as $curroom) {
                            // For each room I create an area having building, floors and room
                            
                            $area_id = DB::table('removals_areas')
                                ->insertGetId([
                                    'removal_id'    =>  $id,
                                    'building'      =>  $curbuilding->building,
                                    'name'          =>  $floorname . ' ' . $curroom->room_name,
                                    'text'          =>  '',
                                    'created_at'    =>  $creation_date,
                                    'updated_at'    =>  $modify_date,
                                ]);
                            
                            // Create a record in the inspections array in 3 dimensions
                            
                            $inspections[$curbuilding->building][$curfloor->floor_id][$curroom->room_name] = 
                                    DB::table('inspections')
                                        ->join('floors','floors.id','=','inspections.floor_id')
                                        ->join('rooms','rooms.id','=','inspections.room_id')
                                        ->join('products','products.id','=','inspections.product_id')
                                        ->join('extents','extents.id','=','inspections.extent_of_damage')
                                        ->join('surface_treatments','surface_treatments.id','=','inspections.surface_treatment')
                                        ->whereIn('survey_id', $surveys)
                                        ->whereNotIn('results', array('','Non-asbestos'))
                                        ->where('inspections.building','=',$curbuilding->building)
                                        ->where('inspections.floor_id','=',$curfloor->floor_id)
                                        ->where('inspections.room_name','=',$curroom->room_name)
                                        ->select('inspections.inspection_number AS inspection_no',
                                                'floors.name AS floor_name',
                                                'inspections.comments AS comments',
                                                'inspections.material_location AS material_location',
                                                'inspections.recommendations AS recommendations',
                                                'inspections.results AS results',
                                                'inspections.quantity AS quantity',
                                                'extents.name AS extent_of_damage',
                                                'products.name AS product',
                                                'surface_treatments.description AS surface_treatment',
                                                'inspections.photo AS photo'
                                            )
                                        ->orderBy('inspections.id','asc')
                                        ->get()
                                        ->all();
                            
                            if (!empty($inspections[$curbuilding->building][$curfloor->floor_id][$curroom->room_name])) {
                                for ($n = 0; $n < count($inspections[$curbuilding->building][$curfloor->floor_id][$curroom->room_name]); $n++) {
                                    $inspections[$curbuilding->building][$curfloor->floor_id][$curroom->room_name][$n]->area_id = $area_id;
                                }
                            }                            
                        }
                    }
                }
                        
                // Copy the positive inspections in the removal sections
                foreach ($inspections as $build => $floor_inspections) {
                    foreach ($floor_inspections as $fl => $rooms_inspections) {
                        foreach ($rooms_inspections as $troom => $insps) {
                            foreach ($insps as $curinsp) {
                                $comms = $curinsp->comments;
                                if (!empty($comms) and !empty($curinsp->material_location)) {
                                    $comms .= "\n";
                                }
                                $comms .= $curinsp->material_location;                            

                                DB::table('removals_inspections')
                                    ->insert([
                                        'area_id'               =>  $curinsp->area_id,
                                        'room'                  =>  $troom,
                                        'comment'               =>  $comms,
                                        'inspection_no'         =>  $curinsp->inspection_no,
                                        'result'                =>  $curinsp->results,
                                        'product'               =>  $curinsp->product,
                                        'quantity'              =>  $curinsp->quantity,
                                        'extent_of_damage'      =>  $curinsp->extent_of_damage,
                                        'surface_treatment'     =>  $curinsp->surface_treatment,
                                        'recommendation'        =>  $curinsp->recommendations,
                                        'picture_path'          =>  $curinsp->photo,
                                        'created_at'            =>  $creation_date,
                                        'updated_at'            =>  $modify_date,
                                    ]);
                            }
                        }
                    }
                }
            } else if ('yes' == $new_revision) {
                // If it is a new revision, I create new records from the previous revision
            }
        } else {
            DB::table('removals')
                ->where('id','=',$id)
                ->update([
                    'surveys'                           =>  implode(';',$surveys),
                    'project_ref'                       =>  $project_ref,
                    'area'                              =>  $area,
                    'address'                           =>  $address,
                    'prepared_for'                      =>  $prepared_for,
                    'prepared_by'                       =>  $prepared_by,
                    'prepared_by_signature_path'        =>  str_replace('/removals/','/',$prepared_by_signature),
                    'preparation_date'                  =>  $preparation_date,
                    'approved_by'                       =>  $approved_by,
                    'approved_by_signature_path'        =>  str_replace('/removals/','/',$approved_by_signature),
                    'approval_date'                     =>  $approval_date,
                    'preliminaries'                     =>  $preliminaries,
                    'site_picture_path'                 =>  str_replace('/removals/','/',$site_picture),
                    'map_picture_path'                  =>  str_replace('/removals/','/',$map_picture),
                    'floor_plans_path'                  =>  str_replace('/removals/','/',$floor_plans),
                    'access_routes_path'                =>  str_replace('/removals/','/',$access_routes),
                    'bulk_analysis_certificate_path'    =>  str_replace('/removals/','/',$bulk_analysis_certificate),
                    'general_requirements'              =>  $general_requirements,
                    'tender_submission'                 =>  $tender_submission,
                    'include_access_routes'             =>  $include_access_routes,
                    'comments'                          =>  $revision_comments,
                    'updated_at'                        =>  $modify_date,
                ]);
        }
        
        return $id;
    }
    
    public function importPicture(Request $request)
    {
        $filename = $request->file('filename');
        $project_ref = $request->input('project_ref');
        
        $project_ref = str_replace('/','-',$project_ref);
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
        
        $folder_name = public_path() . '/removals/' . $project_ref;
        
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        
        $originalFileName = strtolower($filename->getClientOriginalName());
        
        $filename->move($folder_name,$originalFileName);
        
        return '"/' . $project_ref . '/' . $originalFileName . '"';
    }
    
    public function saveRemovalAreaTitle(Request $request)
    {
        $area_id = $request->input('area_id');
        $name = $request->input('name');
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        DB::table('removals_areas')
                ->where('id','=',$area_id)
                ->update([
                    'name'          =>  $name,
                    'updated_at'    =>  $modify_date,
                ]);
        
        return 1;
    }
    
    public function saveRemovalAreaText(Request $request)
    {
        $area_id = $request->input('area_id');
        $text = $this->cleanText($request->input('text'));
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        DB::table('removals_areas')
                ->where('id','=',$area_id)
                ->update([
                    'text'          =>  $text,
                    'updated_at'    =>  $modify_date,
                ]);
        
        return 1;
    }
    
    public function saveRemovalInspection(Request $request)
    {
        $inspection_id = $request->input('removal_inspection_id');
        $room = $request->input('room');
        $extent = $request->input('extent');
        $product = $request->input('product');
        $surface_treatment = $request->input('surface_treatment');
        $result = $request->input('result');
        $damage = $request->input('damage');
        $comment = $request->input('comment');
        $recommendation = $request->input('recommendation');
        
        $modify_date = date('Y-m-d H:i:s',time());
        
        DB::table('removals_inspections')
                ->where('id','=',$inspection_id)
                ->update([
                    'room'              =>  $room,
                    'comment'           =>  $comment,
                    'result'            =>  $result,
                    'product'           =>  $product,
                    'quantity'          =>  $extent,
                    'extent_of_damage'  =>  $damage,
                    'surface_treatment' =>  $surface_treatment,
                    'recommendation'    =>  $recommendation,
                    'updated_at'        =>  $modify_date,
                ]);
        
        return 1;
    }
}
