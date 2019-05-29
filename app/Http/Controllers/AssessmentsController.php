<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssessmentsController extends Controller
{
    public function index()
    {
        $fshops = DB::table('rashops')
                ->join('clients','clients.id','=','rashops.client_id')
                ->select('clients.id AS client_id','clients.companyname AS client_name','rashops.id AS rashop_id','rashops.name AS rashop_name','rashops.code AS code')
                ->orderBy('rashops.name','asc')
                ->get();
        
        $reports = array();
        
        foreach ($fshops as $fshop) {
            if (empty($reports[$fshop->rashop_id])) {
                $reports[$fshop->rashop_id] = array(
                    'client_id'     =>  $fshop->client_id,
                    'client_name'   =>  $fshop->client_name,
                    'rashop_id'     =>  $fshop->rashop_id,
                    'rashop_name'   =>  $fshop->rashop_name,
                    'shnum'         =>  ((1 == $fshop->client_id) ? ('Shop Number: ' . $fshop->code) : ''),
                    'revisions'     =>  array(),
                );
            }
        }
        
        $freports = DB::table('rareports')
                ->join('rashops','rareports.rashop_id','=','rashops.id')
                ->join('clients','clients.id','=','rashops.client_id')
                ->select('rareports.id AS id','rareports.revision AS revision','rareports.issue_date AS issue_date','clients.id AS client_id','clients.companyname AS client_name','rashops.id AS rashop_id','rashops.name AS rashop_name','rareports.completed AS completed','rareports.created_at AS creation_date')
                ->orderBy('id','asc')
                ->get();
        
        foreach ($freports as $freport) {
            $reports[$freport->rashop_id]['revisions'][] = array(
                'id'            =>  $freport->id,
                'revision'      =>  $freport->revision,
                'issue_date'    =>  (empty($freport->issue_date) ? strtotime($freport->creation_date) : $freport->issue_date),
                'completed'     =>  $freport->completed,
            );
        }
        
        $title = 'Fire Risk Assessments';
        
        $passed_data = array(
            'title'     =>  $title,
            'reports'   =>  $reports,   
        );
        
        return view('fras', $passed_data);
    }
    
    public function getShops()
    {
        $fshops = DB::table('rashops')
                ->join('clients','clients.id','=','rashops.client_id')
                ->select('rashops.id AS id','rashops.name AS name','rashops.code AS code','clients.id AS client_id','clients.companyname AS client','rashops.address1 AS address1','rashops.address2 AS address2','rashops.town AS town','rashops.postcode AS postcode')
                ->orderBy('id','asc')
                ->get();
        
        $shops = array();
        
        foreach ($fshops as $curshop) {            
            $freps = DB::table('rareports')
                    ->where('rashop_id','=',$curshop->id)
                    ->count();
            
            $okdel = ($freps <= 0);
            
            $addr = $curshop->address1;
            
            if (!empty($curshop->address2)) {
                $addr .= "<br/>" . $curshop->address2;
            }
            
            $addr .= "<br/>" . $curshop->town . ' ' . $curshop->postcode;
            
            $shnum = '';
            if (1 == $curshop->client_id) {
                $shnum = 'Shop Number: ' . $curshop->code;
            }
            
            $shops[] = array(
                'id'        =>  $curshop->id,
                'name'      =>  $curshop->name,
                'client_id' =>  $curshop->client_id,
                'client'    =>  $curshop->client,
                'address1'  =>  $curshop->address1,                
                'address2'  =>  $curshop->address2,                
                'town'      =>  $curshop->town,
                'postcode'  =>  $curshop->postcode,
                'shnum'     =>  $shnum,
                'okdel'     =>  ($okdel ? 1 : 0),
            );
        }
        
        $title = 'Fire Risk Assessment - Site Locations';
        
        $passed_data = array(
            'title'         =>  $title,
            'shops'         =>  $shops,
        );
        
        return view('shops', $passed_data);
    }
    
    public function getShop($shop_id)
    {
        $clients = DB::table('clients')
                ->select('id','name')
                ->orderBy('name','asc')
                ->get();
        
        if ('new' == $shop_id) {
            $shop = array(
                'id'        =>  'new',
                'client_id' =>  '',
                'name'      =>  '',
                'address1'  =>  '',
                'address2'  =>  '',
                'town'      =>  '',
                'postcode'  =>  '',
                'code'      =>  '',
            );

            $title = 'Fire Risk Assessment - New Site Location';

            $passed_data = array(
                'title'     =>  $title,
                'clients'   =>  $clients,
                'shop'      =>  $shop,
            );

            return view('shop', $passed_data);
        }
        
        $fshop = DB::table('rashops')
                ->join('clients','clients.id','=','rashops.client_id')
                ->select('rashops.id AS id','rashops.name AS name','clients.id AS client_id','rashops.code AS code','clients.companyname AS client','rashops.address1 AS address1','rashops.address2 AS address2','rashops.town AS town','rashops.postcode AS postcode')
                ->where('rashops.id','=',$shop_id)
                ->get()
                ->first();
        
        $shop = array(
            'id'        =>  $fshop->id,
            'client_id' =>  $fshop->client_id,
            'client'    =>  $fshop->client,
            'name'      =>  $fshop->name,
            'address1'  =>  $fshop->address1,
            'address2'  =>  $fshop->address2,
            'town'      =>  $fshop->town,
            'postcode'  =>  $fshop->postcode,
            'code'      =>  $fshop->code,
        );
        
        $title = 'Fire Risk Assessment - ' . $shop['name'];
        
        $passed_data = array(
            'title'     =>  $title,
            'clients'   =>  $clients,
            'shop'      =>  $shop,
        );
        
        return view('shop', $passed_data);
    }
    
    public function loadFra($shop_chosen,$revision)
    {
        $sections = array();
        $questions = array();
        $answers = array();
        $fra = array(
            'id'                                =>  '',
            'countrylaw'                        =>  'uk',
            'signature'                         =>  '',
            'main_picture'                      =>  '',
            'risk_level_rate'                   =>  '',
            'responsible_person'                =>  '',
            'assessor'                          =>  '',
            'person_to_meet'                    =>  '',
            'use_of_building'                   =>  '',
            'number_of_floors'                  =>  '',
            'construction_type'                 =>  '',
            'max_number_occupants'              =>  '',
            'number_employees'                  =>  '',
            'disabled_occupants'                =>  '',
            'remote_occupants'                  =>  '',
            'hours_operation'                   =>  '',
            'next_date_recommended'             =>  '',
            'executive_summary'                 =>  '',
            'fire_loss_experience'              =>  '',
            'relevant_fire_safety_legislation'  =>  '',
            'comments'                          =>  '',
            'hazard_from_fire'                  =>  '',
            'life_safety'                       =>  '',
            'general_fire_risk'                 =>  '',
            'survey_date'                       =>  '',
            'review_date'                       =>  '',
            'review_by'                         =>  '',
            'review_signature'                  =>  '',
			'text_after_review_table'           =>  '',
            'competence'                        =>  '',
            'guidance_used'                     =>  '',
            'completed'                         =>  0,
        );
        
        $revmax = DB::table('rareports')
                ->where('rashop_id','=',$shop_chosen)
                ->max('revision');
        
        if (empty($revmax)) {
            $revmax = 0;
        }
        
        $myrev = $revision;
        
        $isnew = false;
        
        if ($myrev > $revmax) {
            $myrev = $revmax;
            $isnew = true;
        }
        
        $firerisk = DB::table('rareports')
                ->where('rashop_id','=',$shop_chosen)
                ->where('revision','=',$myrev)
                ->get()
                ->first();
        
        if ($firerisk !== null) {
            $fra = array(
                'id'                                =>  $firerisk->id,
                'countrylaw'                        =>  $firerisk->countrylaw,
                'signature'                         =>  $firerisk->signature,
                'main_picture'                      =>  $firerisk->main_picture,
                'risk_level_rate'                   =>  $firerisk->risk_level_rate,
                'responsible_person'                =>  $firerisk->responsible_person,
                'assessor'                          =>  $firerisk->assessor,
                'person_to_meet'                    =>  $firerisk->person_to_meet,
                'use_of_building'                   =>  $firerisk->use_of_building,
                'number_of_floors'                  =>  $firerisk->number_of_floors,
                'construction_type'                 =>  $firerisk->construction_type,
                'max_number_occupants'              =>  $firerisk->max_number_occupants,
                'number_employees'                  =>  $firerisk->number_employees,
                'disabled_occupants'                =>  $firerisk->disabled_occupants,
                'remote_occupants'                  =>  $firerisk->remote_occupants,
                'hours_operation'                   =>  $firerisk->hours_operation,
                'next_date_recommended'             =>  date('d/m/Y',$firerisk->next_date_recommended),
                'executive_summary'                 =>  $firerisk->executive_summary,
                'fire_loss_experience'              =>  $firerisk->fire_loss_experience,
                'relevant_fire_safety_legislation'  =>  $firerisk->relevant_fire_safety_legislation,
                'comments'                          =>  $firerisk->comments,
                'hazard_from_fire'                  =>  $firerisk->hazard_from_fire,
                'life_safety'                       =>  $firerisk->life_safety,
                'general_fire_risk'                 =>  $firerisk->general_fire_risk,
                'survey_date'                       =>  date('d/m/Y',(empty($firerisk->survey_date) ? time() : $firerisk->survey_date)),
                'review_date'                       =>  date('d/m/Y',(empty($firerisk->review_date) ? time() : $firerisk->review_date)),
                'review_by'                         =>  $firerisk->review_by,
                'review_signature'                  =>  $firerisk->review_signature,
			    'text_after_review_table'           =>  $firerisk->text_after_review_table,
				'competence'                        =>  $firerisk->competence,
				'guidance_used'                     =>  $firerisk->guidance_used,
                'completed'                         =>  $firerisk->completed,
            );
        }
        
        $curshop = DB::table('rashops')
                ->select('id','name','client_id')
                ->where('id','=',$shop_chosen)
                ->get()
                ->first();
        
        if (($firerisk === null) and (1 == $curshop->client_id)) {
            $days = array('Monday:','Tuesday:','Wednesday:','Thursday:','Friday:','Saturday:','Sunday:',);
            
            $fra['hours_operation'] = implode("\n",$days);
            
            $fra['executive_summary'] = 'This Fire Risk Assessment has been carried out on your behalf,';
            $fra['executive_summary'] .= 'being the Responsible Person, as defined in Article 3 of the Fire Regulatory Reform (Fire Safety) Order 2005. ';
            $fra['executive_summary'] .= 'The purpose of this report is to provide an assessment of the risk to life from fire in these premises,';
            $fra['executive_summary'] .= 'and, where appropriate, to make recommendations to ensure compliance with fire safety legislation. ';
            $fra['executive_summary'] .= 'The report does not address the risk to property or business continuity from fire. ';
            $fra['executive_summary'] .= 'A type 1 (non-invasive) FRA was conducted that included inspection of all accessible areas.';
            $fra['executive_summary'] .= "&#10;&#10;";
            $fra['executive_summary'] .= 'Pret, [shop reference] is a retail unit serving food and drinks. The shop is [number of floors / basic';
            $fra['executive_summary'] .= 'description e.g. set over two floors, basement and ground], with a distinct front of house and back of house space. ';
            $fra['executive_summary'] .= 'Front of house is primarily made up of customer seating with a counter, langers, and customer toilets. ';
            $fra['executive_summary'] .= 'Back of house is made up of Pret Kitchen with the supporting ancillary accommodation, staff change, manager\'s office,';
            $fra['executive_summary'] .= 'storage etc.';
            $fra['executive_summary'] .= "&#10;&#10;";
            $fra['executive_summary'] .= 'The shop is provided with [number of] escape routes, both [describe escape], which lead to [fresh air, landlord escape corridor etc]. ';
            $fra['executive_summary'] .= 'Both exits are step free [if not, review acceptability]. The travel distances are within acceptable distances, escape routes';
            $fra['executive_summary'] .= 'are signed, and an enhanced fire detection system is provided.';
            $fra['executive_summary'] .= "&#10;&#10;";
            $fra['executive_summary'] .= 'The shop is provided with an L1 fire alarm system. The unit is [a stand alone unit, with no requirement to link to the';
            $fra['executive_summary'] .= 'landlord OR  linked to the landlord system which is understood to send a fire and fault signal]. ';
            $fra['executive_summary'] .= '[A fire within a neighbouring unit would raise the alarm to the Pret shop via a ‘pulse’ signal from the fire alarm or delete]. ';
            $fra['executive_summary'] .= 'An isolation test key is available for weekly / periodic testing [delete when standalone].';
            $fra['executive_summary'] .= "&#10;&#10;";  
            $fra['executive_summary'] .= 'Emergency lighting is provided throughout, covering the escape routes.';
            $fra['executive_summary'] .= "&#10;&#10;";  
            $fra['executive_summary'] .= 'The evacuation strategy is simultaneous. The fire alarm system supports this strategy.';
        }
        
        if ($firerisk === null) {
            $fra['relevant_fire_safety_legislation'] = 'Regulatory Reform (Fire Safety) Order 2005';
        }
        
        $freviewers = DB::table('users')
                ->where('reviewer','=','1')
                ->get();
        
        $fassessors = DB::table('users')
                ->where('assessor','=','1')
                ->get();
        
        $fsections = DB::table('rasections')
                ->orderBy('id','asc')
                ->get();
        
        foreach ($fsections as $fsection) {
            $sections[$fsection->id] = $fsection;
            $questions[$fsection->id] = array();
        }
        
        $fquestions = DB::table('raquestions')
				->where('client_id',$curshop->client_id)
                ->orderBy('rasection_id','asc')
                ->orderBy('id','asc')
                ->get();
        
        $rareadycomments = array();
        $rareadyrecommendations = array();
        
        foreach ($fquestions as $fquestion) {
            $questions[$fquestion->rasection_id][] = $fquestion;
            $answers[$fquestion->id] = new \stdClass();
            $answers[$fquestion->id]->priority_code = 0;
            $answers[$fquestion->id]->answer = $fquestion->goal;
            $answers[$fquestion->id]->comments = '';
            $answers[$fquestion->id]->recommendation = '';
            $answers[$fquestion->id]->action_by_whom = '';
            $answers[$fquestion->id]->actioned_by = '';
            $answers[$fquestion->id]->date_of_completion = '';
            $answers[$fquestion->id]->info = '';
            
            $tot_comments = DB::table('rareadycomments')
                ->where('raquestion_id','=',$fquestion->id)
                ->where('client_id','=',$curshop->client_id)
                ->count();
            
            if ($tot_comments > 0) {
                $rareadycomments[$fquestion->id] = DB::table('rareadycomments')
                    ->where('raquestion_id','=',$fquestion->id)
                    ->where('client_id','=',$curshop->client_id)
                    ->orderBy('id','asc')
                    ->get();
            } else {
                $rareadycomments[$fquestion->id] = DB::table('rareadycomments')
                    ->where('raquestion_id','=',$fquestion->id)
                    ->where('client_id','=',1)
                    ->orderBy('id','asc')
                    ->get();
            }  
            
            $tot_recomms = DB::table('rareadyrecommendations')
                ->where('raquestion_id','=',$fquestion->id)
                ->where('client_id','=',$curshop->client_id)
                ->count();
            
            if ($tot_recomms > 0) {
                $rareadyrecommendations[$fquestion->id] = DB::table('rareadyrecommendations')
                    ->where('raquestion_id','=',$fquestion->id)
                    ->where('client_id','=',$curshop->client_id)
                    ->orderBy('id','asc')
                    ->get();
            } else {
                $rareadyrecommendations[$fquestion->id] = DB::table('rareadyrecommendations')
                    ->where('raquestion_id','=',$fquestion->id)
                    ->where('client_id','=',1)
                    ->orderBy('id','asc')
                    ->get();
            }
        }
        
        $raothers = DB::table('raothers')
                ->where('rareport_id','=',$fra['id'])
                ->orderBy('rasection_id','asc')
                ->get();
        
        $totreports = DB::table('rareports')
                ->where('rashop_id','=',$shop_chosen)
                ->get()
                ->count();
        
        if ($totreports > 0) {
            $chosen_report = DB::table('rareports')
                ->where('rashop_id','=',$shop_chosen)
                ->where('revision','=',$myrev)
                ->get()
                ->first();
            
            if ($chosen_report === null) {
                $chosen_report = DB::table('rareports')
                    ->where('rashop_id','=',$shop_chosen)
                    ->orderBy('revision','desc')
                    ->get()
                    ->first();
            }
            
            $fanswers = DB::table('raanswers')
                    ->where('rareport_id','=',$chosen_report->id)
                    ->orderBy('raquestion_id','asc')
                    ->orderBy('updated_at','desc')
                    ->get();

            foreach ($fanswers as $fanswer) {
                $answers[$fanswer->raquestion_id] = $fanswer;
                
                if (!empty($answers[$fanswer->raquestion_id]->date_of_completion) and ('0000-00-00' != $answers[$fanswer->raquestion_id]->date_of_completion)) {
                    $answers[$fanswer->raquestion_id]->date_of_completion = date('d/m/Y',strtotime($answers[$fanswer->raquestion_id]->date_of_completion));
                } else {
                    $answers[$fanswer->raquestion_id]->date_of_completion = '';
                }
            }
        }
        
        $title = 'Fire Risk Assessment for ' . $curshop->name;
        
        $passed_data = array(
            'title'                     =>  $title,
            'curshop'                   =>  $curshop,
            'revision'                  =>  $myrev,
            'isnew'                     =>  ($isnew ? 1 : 0),
            'completed'                 =>  $fra['completed'],
            'fra'                       =>  $fra,
            'freviewers'                =>  $freviewers,
            'fassessors'                =>  $fassessors,
            'rasections'                =>  $sections,
            'raquestions'               =>  $questions,
            'raanswers'                 =>  $answers,
            'rareadycomments'           =>  $rareadycomments,
            'rareadyrecommendations'    =>  $rareadyrecommendations,
            'raothers'                  =>  $raothers,
        );
        
        return view('fra', $passed_data);
    }
    
    public function saveShop(Request $request)
    {
        $client_id = $request->input('client_id');
        $name = $request->input('name');
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        $town = $request->input('town');
        $postcode = $request->input('postcode');
        $code = $request->input('code');
        
        $issue_date = time();
        
        $creation_date = date('Y-m-d H:i:s',$issue_date);
        $modify_date = date('Y-m-d H:i:s',$issue_date);
        
        $found = DB::table('rashops')
                ->where('name','=',$name)
                ->get();
        
        if (0 >= $found->count()) {
            DB::table('rashops')->insert([
                'client_id'         =>  $client_id,
                'name'              =>  $name,
                'address1'          =>  $address1,
                'address2'          =>  $address2,
                'town'              =>  $town,
                'postcode'          =>  $postcode,
                'code'              =>  $code,
                'created_at'        =>  $creation_date,
                'updated_at'        =>  $modify_date,
            ]);
        } else {
            DB::table('rashops')
                    ->where('name','=',$name)
                    ->update([
                        'client_id'         =>  $client_id,
                        'name'              =>  $name,
                        'address1'          =>  $address1,
                        'address2'          =>  $address2,
                        'town'              =>  $town,
                        'postcode'          =>  $postcode,
                        'code'              =>  $code,
                        'updated_at'        =>  $modify_date,
                    ]);
        }
        
        return "1";
    }
    
    public function getFra(Request $request)
    {
        $shop_chosen = $request->input('shop_id');
        $revision = $request->input('revision');
        
        $sections = array();
        $questions = array();
        $answers = array();
        
        $fsections = DB::table('rasections')
                ->orderBy('id','asc')
                ->get();
        
        foreach ($fsections as $fsection) {
            $sections[$fsection->id] = $fsection;
            $questions[$fsection->id] = array();
        }
        
        $fquestions = DB::table('raquestions')
                ->orderBy('rasection_id','asc')
                ->orderBy('id','asc')
                ->get();
        
        foreach ($fquestions as $fquestion) {
            $questions[$fquestion->rasection_id][] = $fquestion;
            $answers[$fquestion->id] = array();
        }   
        
        $fanswers = DB::table('raanswers')
                ->join('rareports','rareports.id','=','raanswers.report_id')
                ->where('rareports.rashop_id','=',$shop_chosen)
                ->where('rareports.revision','=',$revision)
                ->orderBy('raanswers.raquestion_id','asc')
                ->orderBy('raanswers.updated_at','desc')
                ->get();
        
        foreach ($fanswers as $fanswer) {
            $answers[$fanswer->raquestion_id][] = $fanswer;
        }
        
        return json_encode($answers);
    }
    
    public function importSignature(Request $request)
    {
        $filename = $request->file('filename');
        $shop_id = $request->input('shop_id');
        
        if (!file_exists($filename) or !is_readable($filename)) {
            return '0';
        }
        
        $fshop = DB::table('rashops')
                ->where('id','=',$shop_id)
                ->get()
                ->first();
        
        $shop_name = str_replace(" ","-",$fshop->name);
		$shop_name = str_replace(",","-",$shop_name);
		$shop_name = str_replace(",-","-",$shop_name);
		$shop_name = str_replace("--","-",$shop_name);
		$shop_name = str_replace("'","",$shop_name);
        
        $folder_name = public_path() . '/fra/' . strtolower($shop_name);
        
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        
        $originalFileName = strtolower($filename->getClientOriginalName());
        
        if ($originalFileName == 'image.jpg') {
            $originalFileName = 'signature.jpg';
        }

		$originalFileName = str_replace(" ","-",$originalFileName);
		$originalFileName = str_replace("..",".",$originalFileName);
		$originalFileName = str_replace(",-","-",$originalFileName);
		$originalFileName = str_replace(",","",$originalFileName);
		$originalFileName = str_replace("--","-",$originalFileName);
		$originalFileName = str_replace("'","",$originalFileName);
		$originalFileName = str_replace(".jpeg.jpg",".jpg",$originalFileName);
        
        $filename->move($folder_name,$originalFileName);
        
        return '"/' . strtolower($shop_name) . '/' . $originalFileName . '"';
    }
    
    public function importReviewSignature(Request $request)
    {
        $filename = $request->file('filename');
        $shop_id = $request->input('shop_id');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
        
        $fshop = DB::table('rashops')
                ->where('id','=',$shop_id)
                ->get()
                ->first();
        
        $shop_name = str_replace(" ","-",$fshop->name);
		$shop_name = str_replace(",","-",$shop_name);
		$shop_name = str_replace(",-","-",$shop_name);
		$shop_name = str_replace("--","-",$shop_name);
		$shop_name = str_replace("'","",$shop_name);
        
        $folder_name = public_path() . '/fra/' . strtolower($shop_name);
        
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        
        $originalFileName = strtolower($filename->getClientOriginalName());
        
        if ($originalFileName == 'image.jpg') {
            $originalFileName = 'review_signature.jpg';
        }

		$originalFileName = str_replace(" ","-",$originalFileName);
		$originalFileName = str_replace("..",".",$originalFileName);
		$originalFileName = str_replace(",-","-",$originalFileName);
		$originalFileName = str_replace(",","",$originalFileName);
		$originalFileName = str_replace("--","-",$originalFileName);
		$originalFileName = str_replace("'","",$originalFileName);
		$originalFileName = str_replace(".jpeg.jpg",".jpg",$originalFileName);
        
        $filename->move($folder_name,$originalFileName);
        
        return '"/' . strtolower($shop_name) . '/' . $originalFileName . '"';
    }
    
    public function importMainPicture(Request $request)
    {
        $filename = $request->file('filename');
        $shop_id = $request->input('shop_id');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
        
        $fshop = DB::table('rashops')
                ->where('id','=',$shop_id)
                ->get()
                ->first();
        
        $shop_name = str_replace(" ","-",$fshop->name);
		$shop_name = str_replace(",","-",$shop_name);
		$shop_name = str_replace(",-","-",$shop_name);
		$shop_name = str_replace("--","-",$shop_name);
		$shop_name = str_replace("'","",$shop_name);
        
        $folder_name = public_path() . '/fra/' . strtolower($shop_name);
        
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        
        $originalFileName = strtolower($filename->getClientOriginalName());
        
        if ($originalFileName == 'image.jpg') {
            $originalFileName = 'main_picture.jpg';
        }

		$originalFileName = str_replace(" ","-",$originalFileName);
		$originalFileName = str_replace("..",".",$originalFileName);
		$originalFileName = str_replace(",-","-",$originalFileName);
		$originalFileName = str_replace(",","",$originalFileName);
		$originalFileName = str_replace("--","-",$originalFileName);
		$originalFileName = str_replace("'","",$originalFileName);
		$originalFileName = str_replace(".jpeg.jpg",".jpg",$originalFileName);
        
        $filename->move($folder_name,$originalFileName);
        
        return '"/' . strtolower($shop_name) . '/' . $originalFileName . '"';
    }
    
    public function importPicture(Request $request)
    {
        $filename = $request->file('filename');
        $shop_id = $request->input('shop_id');
        $picture_id = $request->input('picture_id');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
        
        $fshop = DB::table('rashops')
                ->where('id','=',$shop_id)
                ->get()
                ->first();
        
        $shop_name = str_replace(" ","-",$fshop->name);
		$shop_name = str_replace(",","-",$shop_name);
		$shop_name = str_replace(",-","-",$shop_name);
		$shop_name = str_replace("--","-",$shop_name);
		$shop_name = str_replace("'","",$shop_name);
        
        $folder_name = public_path() . '/fra/' . strtolower($shop_name);
        
        if (!file_exists($folder_name)) {
            mkdir($folder_name, 0777, true);
        }
        
        $originalFileName = strtolower($filename->getClientOriginalName());
        
        if ($originalFileName == 'image.jpg') {
            $originalFileName = 'wrong_' . $picture_id . '.jpg';
        }

		$originalFileName = str_replace(" ","-",$originalFileName);
		$originalFileName = str_replace("..",".",$originalFileName);
		$originalFileName = str_replace(",-","-",$originalFileName);
		$originalFileName = str_replace(",","",$originalFileName);
		$originalFileName = str_replace("--","-",$originalFileName);
		$originalFileName = str_replace("'","",$originalFileName);
		$originalFileName = str_replace(".jpeg.jpg",".jpg",$originalFileName);
        
        $filename->move($folder_name,$originalFileName);
        
        return '"/' . strtolower($shop_name) . '/' . $originalFileName . '"';
    }
    
    public function saveFra(Request $request)
    {                
        $answers = $request->input('answers');
        
        if (empty($answers)) {
            return 0;
        }
        
        $shop_chosen = $request->input('shop_id');
        $revision = $request->input('revision');
        
        $signature = str_replace('/fra/','/',$request->input('signature'));
        
        $review_signature = str_replace('/fra/','/',$request->input('review_signature'));
        
        $risk_level_rate = $request->input('risk_level_rate');        
        
        $main_picture = str_replace('/fra/','/',$request->input('main_picture'));
        
        $responsible_person = $request->input('responsible_person');
        $assessor = $request->input('assessor');
        $person_to_meet = $request->input('person_to_meet');
        $use_of_building = $request->input('use_of_building');
        $number_of_floors = $request->input('number_of_floors');
        $construction_type = $request->input('construction_type');        
        $max_number_occupants = $request->input('max_number_occupants');
        $number_employees = $request->input('number_employees');
        $disabled_occupants = $request->input('disabled_occupants');
        $remote_occupants = $request->input('remote_occupants');
        $hours_operation = $request->input('hours_operation');
        $next_date_recommended = $request->input('next_date_recommended');
        
        if (!empty($next_date_recommended)) {
            $splitted = explode('/',$next_date_recommended);
            $next_date_recommended = strtotime($splitted[2] . '-' . $splitted[1] . '-' . $splitted[0]);
        }

		$competence = $request->input('competence');
		$guidance_used = $request->input('guidance_used');
		$text_after_review_table = $request->input('text_after_review_table');
        
        $survey_date = $request->input('survey_date');
        
        if (!empty($survey_date)) {
            $splitted = explode('/',$survey_date);
            $survey_date = strtotime($splitted[2] . '-' . $splitted[1] . '-' . $splitted[0]);
        }
        
        $review_date = $request->input('review_date');
        
        if (!empty($review_date)) {
            $splitted = explode('/',$review_date);
            $review_date = strtotime($splitted[2] . '-' . $splitted[1] . '-' . $splitted[0]);
        }
        
        $review_by = $request->input('review_by');
        
        $executive_summary = $request->input('executive_summary');
        $fire_loss_experience = $request->input('fire_loss_experience');
        $relevant_fire_safety_legislation = $request->input('relevant_fire_safety_legislation');
        $comments = $request->input('revision_comments');
        
        $hazard_from_fire = $request->input('hazard_from_fire');
        $life_safety = $request->input('life_safety');
        $general_fire_risk = $request->input('general_fire_risk');
        
        $new_revision = $request->input('new_revision');
        
        // Check if there are previous reports on that shop
        $indb = DB::table('rareports')
                ->where('rashop_id','=',$shop_chosen)
                ->count();
        
        // If there are, and it is a new revision, increase the revision
        if (($indb > 0) and ($new_revision == 1)) {
            $revision = 1 + DB::table('rareports')
                ->select('id','revision')
                ->where('rashop_id','=',$shop_chosen)
                ->orderBy('id','desc')
                ->get()
                ->first()
                ->revision;
        } else if (($indb == 0) and ($new_revision == 1)) {
            $revision = 1;
        } else if (empty($new_revision) and empty($indb)) {
            $revision = 1;
        } else {
            $revision = $request->input('revision');
        }
        
        $issue_date = time();
        $creation_date = date('Y-m-d H:i:s',$issue_date);
        $modify_date = date('Y-m-d H:i:s',$issue_date);
        
        $completed = $request->input('completed');
        $country_context = $request->input('country_context');
        
        $datatoadd = array();
        
        if ($new_revision == 1) {
            $datatoadd = array(
                'rashop_id'                         =>  $shop_chosen,
                'revision'                          =>  $revision,
                'comments'                          =>  $comments,
                'countrylaw'                        =>  $country_context,
                'prepared_by'                       =>  $assessor,
                'signature'                         =>  $signature,
                'risk_level_rate'                   =>  $risk_level_rate,
                'main_picture'                      =>  $main_picture,
                'responsible_person'                =>  $responsible_person,
                'assessor'                          =>  $assessor,
                'person_to_meet'                    =>  $person_to_meet,
                'use_of_building'                   =>  $use_of_building,
                'number_of_floors'                  =>  $number_of_floors,
                'construction_type'                 =>  $construction_type,
                'max_number_occupants'              =>  $max_number_occupants,
                'number_employees'                  =>  $number_employees,
                'disabled_occupants'                =>  $disabled_occupants,
                'remote_occupants'                  =>  $remote_occupants,
                'hours_operation'                   =>  $hours_operation,
                'next_date_recommended'             =>  $next_date_recommended,
                'executive_summary'                 =>  $executive_summary,
                'fire_loss_experience'              =>  $fire_loss_experience,
                'relevant_fire_safety_legislation'  =>  $relevant_fire_safety_legislation,
                'hazard_from_fire'                  =>  $hazard_from_fire,
                'life_safety'                       =>  $life_safety,
                'general_fire_risk'                 =>  $general_fire_risk,
                'survey_date'                       =>  $survey_date,
                'review_date'                       =>  $review_date,
                'review_signature'                  =>  $review_signature,
				'text_after_review_table'           =>  $text_after_review_table,
				'competence'                        =>  $competence,
				'guidance_used'                     =>  $guidance_used,
                'completed'                         =>  $completed,
                'created_at'                        =>  $creation_date,
                'updated_at'                        =>  $modify_date,
            );
            
            if (!empty($review_by)) {
                $datatoadd['review_by'] = $review_by;
            }
            
            // Insert new report
            DB::table('rareports')->insert($datatoadd);
        } else {
            $datatoadd = array(
                'rashop_id'                         =>  $shop_chosen,
                'revision'                          =>  $revision,
                'comments'                          =>  $comments,
                'countrylaw'                        =>  $country_context,
                'prepared_by'                       =>  $assessor,
                'signature'                         =>  $signature,
                'risk_level_rate'                   =>  $risk_level_rate,
                'main_picture'                      =>  $main_picture,
                'responsible_person'                =>  $responsible_person,
                'assessor'                          =>  $assessor,
                'person_to_meet'                    =>  $person_to_meet,
                'use_of_building'                   =>  $use_of_building,
                'number_of_floors'                  =>  $number_of_floors,
                'construction_type'                 =>  $construction_type,
                'max_number_occupants'              =>  $max_number_occupants,
                'number_employees'                  =>  $number_employees,
                'disabled_occupants'                =>  $disabled_occupants,
                'remote_occupants'                  =>  $remote_occupants,
                'hours_operation'                   =>  $hours_operation,
                'next_date_recommended'             =>  $next_date_recommended,
                'executive_summary'                 =>  $executive_summary,
                'fire_loss_experience'              =>  $fire_loss_experience,
                'relevant_fire_safety_legislation'  =>  $relevant_fire_safety_legislation,
                'hazard_from_fire'                  =>  $hazard_from_fire,
                'life_safety'                       =>  $life_safety,
                'general_fire_risk'                 =>  $general_fire_risk,
                'survey_date'                       =>  $survey_date,
                'review_date'                       =>  $review_date,
                'review_signature'                  =>  $review_signature,
				'text_after_review_table'           =>  $text_after_review_table,
				'competence'                        =>  $competence,
				'guidance_used'                     =>  $guidance_used,
                'completed'                         =>  $completed,
                'created_at'                        =>  $creation_date,
                'updated_at'                        =>  $modify_date,
            );
            
            if (!empty($review_by)) {
                $datatoadd['review_by'] = $review_by;
            }
            
            // Update the revision report
            DB::table('rareports')
                ->where('rareports.rashop_id','=',$shop_chosen)
                ->where('rareports.revision','=',$revision)
                ->update($datatoadd);
        }
        
        if ($new_revision == 1) {
            $lastreport_id = DB::table('rareports')
                    ->select('id')
                    ->where('rashop_id','=',$shop_chosen)
                    ->where('revision','=',$revision)
                    ->get()
                    ->first()
                    ->id;

            // Insert answers
            foreach ($answers as $curanswer) {                
                $date_of_completion = '';
                
                if (!empty($curanswer[8])) {
                    $pieces = explode('/',$curanswer[8]);
                    $date_of_completion = $pieces[2] . '-' . $pieces[1] . '-' . $pieces[0];
                }
                
                DB::table('raanswers')->insert([
                    'rareport_id'           =>  $lastreport_id,
                    'raquestion_id'         =>  $curanswer[0],
                    'answer'                =>  $curanswer[1],
                    'comments'              =>  $curanswer[2],
                    'recommendation'        =>  $curanswer[3],
                    'picture'               =>  str_replace('/fra/','/',$curanswer[4]),
                    'priority_code'         =>  (empty($curanswer[5]) ? 0 : $curanswer[5]),
                    'action_by_whom'        =>  $curanswer[6],
                    'actioned_by'           =>  $curanswer[7],
                    'date_of_completion'    =>  $date_of_completion,
                    'info'                  =>  (empty($curanswer[9]) ? 0 : 1),
                    'created_at'            =>  $creation_date,
                    'updated_at'            =>  $modify_date,
                ]);
            }
        } else {
            $lastreport_id = DB::table('rareports')
                    ->select('id')
                    ->where('rashop_id','=',$shop_chosen)
                    ->where('revision','=',$revision)
                    ->get()
                    ->first()
                    ->id;
            
            // Update answers
            foreach ($answers as $curanswer) {
                $date_of_completion = '';
                
                if (!empty($curanswer[8])) {
                    $pieces = explode('/',$curanswer[8]);
                    $date_of_completion = $pieces[2] . '-' . $pieces[1] . '-' . $pieces[0];
                }
                
                DB::table('raanswers')
                ->where('rareport_id','=',$lastreport_id)
                ->where('raquestion_id','=',$curanswer[0])
                ->update([
                    'answer'                =>  $curanswer[1],
                    'comments'              =>  $curanswer[2],
                    'recommendation'        =>  $curanswer[3],
                    'picture'               =>  str_replace('/fra/','/',$curanswer[4]),
                    'priority_code'         =>  (empty($curanswer[5]) ? 0 : $curanswer[5]),
                    'action_by_whom'        =>  $curanswer[6],
                    'actioned_by'           =>  $curanswer[7],
                    'date_of_completion'    =>  $date_of_completion,
                    'info'                  =>  (empty($curanswer[9]) ? 0 : 1),
                    'updated_at'            =>  $modify_date,
                ]);
            }
        }
        
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
        
        $issent = DB::table('rareports')
                    ->select('email_sent')
                    ->where('rashop_id','=',$shop_chosen)
                    ->where('revision','=',$revision)
                    ->get()
                    ->first()
                    ->email_sent;
        
        if ((1 == $datatoadd['completed'])
                and empty($issent)
                and !empty($rasettings['sender_name']) 
                and !empty($rasettings['sender_email']) 
                and !empty($rasettings['receiver_name']) 
                and !empty($rasettings['receiver_email']) 
                and !empty($rasettings['email_subject'])) {
            $shop_info = DB::table('rashops')
                    ->join('clients','clients.id','=','rashops.client_id')
                    ->select('clients.companyname AS client','rashops.name AS shop_name')
                    ->where('rashops.id','=',$shop_chosen)
                    ->get()
                    ->first();
            
            $subject = str_replace('{shop_name}',$shop_info->client . ' ' . $shop_info->shop_name,$rasettings['email_subject']);
            $body = str_replace('{shop_name}',$shop_info->client . ' ' . $shop_info->shop_name,$rasettings['email_text']);
            
            $receiver = array(
                'name'  =>  $rasettings['receiver_name'],
                'email' =>  $rasettings['receiver_email'],
            );

            $additional_headers = array(
                'From: ' . $rasettings['sender_name'] . '<' . $rasettings['sender_email'] . '>',
                'Reply-To: ' . $rasettings['sender_name'] . '<' . $rasettings['sender_email'] . '>',
                'X-Mailer: PHP/' . phpversion(),
            );

            $headers = implode("\n",$additional_headers);
            
            mail($receiver['name'] . '<' . $receiver['email'] . '>', $subject, $body, $headers);
            
            DB::table('rareports')
                ->where('rareports.rashop_id','=',$shop_chosen)
                ->where('rareports.revision','=',$revision)
                ->update(array('email_sent' =>  1, 'issue_date' => time()));
        }
        
        return 1;
    }
    
    public function deleteShop(Request $request)
    {       
        $id = $request->input('shop_id');
        
        $reports = DB::table('rareports')
                ->where('rashop_id','=',$id)
                ->count();
        
        if ($reports > 0) {
            return 0;
        }
            
        DB::table('rashops')
            ->where('id',$id)
            ->delete();
        
        return 1;
    }
    
    public function deleteRevision(Request $request)
    {       
        $id = $request->input('id');
            
        DB::table('rareports')
            ->where('id',$id)
            ->delete();
        
        return 1;
    }
    
    public function addOtherPicture(Request $request)
    {
        $shop_id = $request->input('shop_id');
        $revision = $request->input('revision');
        
        $path = str_replace('/fra/','/',$request->input('path'));
        $caption = $request->input('caption');
        $section = $request->input('section');
        
        $report = DB::table('rareports')
                ->where('rashop_id','=',$shop_id)
                ->where('revision','=',$revision)
                ->get()
                ->first();
        
        $issue_date = time();
        $creation_date = date('Y-m-d H:i:s',$issue_date);
        $modify_date = date('Y-m-d H:i:s',$issue_date);
        
        $generatedId = DB::table('raothers')->insertGetId([
                    'rareport_id'       =>  $report->id,
                    'rasection_id'      =>  $section,
                    'picture'           =>  $path,
                    'caption'           =>  $caption,
                    'created_at'        =>  $creation_date,
                    'updated_at'        =>  $modify_date,
                ]);
        
        return $generatedId;
    }
    
    public function removeOtherPicture(Request $request)
    {
        $id = $request->input('id');
        
        $pict = DB::table('raothers')
                ->where('id','=',$id)
                ->get()
                ->first();
        
        $fullpath = public_path() . '/fra/' . $pict->picture;
        
        if (file_exists($fullpath) and ! is_dir($fullpath)) {
            unlink($fullpath);
        }
        
        DB::table('raothers')
            ->where('id',$id)
            ->delete();
        
        return 1;
    }
    
    public function checkAnswer(Request $request)
    {
        $question_id = $request->input('question_id');
        $answer = $request->input('answer');
        
        $question = DB::table('raquestions')
                ->where('id','=',$question_id)
                ->get()
                ->first();
        
        if (('na' != $answer) and ($question->goal != $answer) and ($question->goal != ucfirst($answer))) {
            return 'wrong';
        }
        
        return 'ok';
    }
    
    public function getRecommendationsAndComments($client_id)
    {
        $shop_clients = DB::table('rashops')
                ->select('client_id')
                ->distinct()
                ->get();
        
        $clients = array();
        
        foreach ($shop_clients as $shop_client) {
            $clients[] = DB::table('clients')
                            ->where('id','=',$shop_client->client_id)
                            ->select('id','companyname')
                            ->first();
        }
        
        $sections = DB::table('rasections')
                ->orderBy('id','ASC')
                ->select('id','name')
                ->get();
        
        $questions = array();
        
        $recommendations = array();
        $comments = array();
        
        foreach ($sections as $section) {
            $questions[$section->id] = DB::table('raquestions')
                                        ->where('rasection_id','=',$section->id)
                                        ->select('id','question')
                                        ->get();
            
            foreach ($questions[$section->id] as $curquestion) {
                $recommendations[$curquestion->id] = DB::table('rareadyrecommendations')
                                                                                ->where('raquestion_id','=',$curquestion->id)
                                                                                ->where('client_id','=',$client_id)
                                                                                ->select('id','text')
                                                                                ->get();
                    
                if (empty($recommendations[$curquestion->id])) {
                    $recommendations[$curquestion->id] = array();
                }

                $comments[$curquestion->id] = DB::table('rareadycomments')
                                                                        ->where('raquestion_id','=',$curquestion->id)
                                                                        ->where('client_id','=',$client_id)
                                                                        ->select('id','text')
                                                                        ->get();

                if (empty($comments[$curquestion->id])) {
                    $comments[$curquestion->id] = array();
                }
            }
        }
        
        $passed_data = array(
            'title' => 'Recommendations and Comments',
            'clients' =>  $clients,
            'client_id' => $client_id,
            'sections' => $sections,
            'questions' => $questions,
            'recommendations' => $recommendations,
            'comments' => $comments,
        );
        
        return view('recommendations', $passed_data);
    }
    
    public function saveRecommendation(Request $request)
    {
        $recomm_id = $request->input('recomm_id');
        $raquestion_id = $request->input('question_id');
        $text = $request->input('text');
        $client_id = $request->input('client_id');
        
        $mdate = date('Y-m-d H:i:s',time());
        
        if (empty($recomm_id) and (empty($raquestion_id) or empty($client_id))) {
            return response()->json(['saved' => 0]);
        }
        
        $found = null;

        if (!empty($recomm_id)) {
            $found = DB::table('rareadyrecommendations')
                        ->where('id','=',$recomm_id)
                        ->first();
        }
        
        if ($found == null) {
            DB::table('rareadyrecommendations')
                ->insert([
                    'raquestion_id' => $raquestion_id,
                    'text' => $text,
                    'client_id' => $client_id,
                    'created_at' => $mdate,
                    'updated_at' => $mdate,
                ]);
        } else {
            DB::table('rareadyrecommendations')
                ->where('id','=',$recomm_id)
                ->update([
                    'text' => $text,
                    'updated_at' => $mdate,
                ]);
        }
        
        return response()->json(['saved' => 1]);
    }
    
    public function saveComment(Request $request)
    {
        $comment_id = $request->input('comment_id');
        $raquestion_id = $request->input('question_id');
        $text = $request->input('text');
        $client_id = $request->input('client_id');
        
        $mdate = date('Y-m-d H:i:s',time());
        
        if (empty($comment_id) and (empty($raquestion_id) or empty($client_id))) {
            return response()->json(['saved' => 0]);
        }
        
        $found = null;

        if (!empty($comment_id)) {
            $found = DB::table('rareadycomments')
                        ->where('id','=',$comment_id)
                        ->first();
        }
        
        if ($found == null) {
            DB::table('rareadycomments')
                ->insert([
                    'raquestion_id' => $raquestion_id,
                    'text' => $text,
                    'client_id' => $client_id,
                    'created_at' => $mdate,
                    'updated_at' => $mdate,
                ]);
        } else {
            DB::table('rareadycomments')
                ->where('id','=',$comment_id)
                ->update([
                    'text' => $text,
                    'updated_at' => $mdate,
                ]);
        }
        
        return response()->json(['saved' => 1]);
    }
    
    public function deleteRecommendation(Request $request)
    {
        $recomm_id = $request->input('recomm_id');
        
        if (empty($recomm_id)) {
            return response()->json(['deleted' => 0]);
        }
        
        DB::table('rareadyrecommendations')
            ->where('id','=',$recomm_id)
            ->delete();
        
        return response()->json(['deleted' => 1]);
    }
    
    public function deleteComment(Request $request)
    {
        $comment_id = $request->input('comment_id');
        
        if (empty($comment_id)) {
            return response()->json(['deleted' => 0]);
        }
        
        DB::table('rareadycomments')
            ->where('id','=',$comment_id)
            ->delete();
        
        return response()->json(['deleted' => 1]);
    }
}
