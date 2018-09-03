<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        
    }
    
    public function showReports($job_number)
    {
        $title = '';
        $jnumber = $job_number;
        
        $reports = DB::table('reports')
                ->join('surveys','surveys.id','=','reports.survey_id')
                ->where('surveys.jobnumber','=',$job_number)
                ->select('reports.id AS id','reports.path AS path','reports.comments AS comments')
                ->orderBy('reports.created_at', 'desc')
                ->get()
                ->all();
        
        $files = DB::table('files')
                ->join('surveys','surveys.id','=','files.survey_id')
                ->where('surveys.jobnumber','=',$job_number)
                ->select('files.id AS id','files.path AS path','files.comments AS comments')
                ->get()
                ->all();
        
        if (!empty($reports)) {
            for ($n = count($reports); $n > 0; $n--) {
                $reports[count($reports) - $n]->issue = $n;
            }
        }
            
        $title = 'Reports for Job Number ' . $jnumber;
        
        $passed_data = array(
            'job_number'    =>  $jnumber,
            'title'         =>  $title,
            'reports'       =>  $reports,
            'files'         =>  $files,
        );
        
        return view('reports', $passed_data);
    }
    
    public function uploadReport(Request $request)
    {
        $filename = $request->file('reportname');
        $originalFileName = strtolower($filename->getClientOriginalName());
        $comments = $request->input('comments');
        $jobnumber = $request->input('jobnumber');
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return '0';
        }
 
        $survey_id = DB::table('surveys')
                    ->select('id')
                    ->where('jobnumber','=',$jobnumber)
                    ->get()
                    ->first();
        
        $full_dest_path = public_path() . '/reports/';
        
        $finalname = $jobnumber . '_' . str_replace(' ','_',$originalFileName);
        
        $basename = str_replace('.pdf','',$finalname);
        
        $reps = DB::table('reports')
                ->select(DB::raw('COUNT(*) AS issues'))
                ->where('path', 'like', $basename . '%')
                ->orderBy('created_at', 'asc')
                ->get()
                ->first();
        
        $num_issue = 1 + $reps->issues;
        
        $newname = str_replace('.pdf','_' . $num_issue . '.pdf',$finalname);
        
        $filename->move($full_dest_path,$newname);
        
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());

        $data = array(
            'survey_id'         =>  $survey_id->id,
            'comments'          =>  $comments,
            'path'              =>  $newname,
            'created_at'        =>  $creation_date,
            'updated_at'        =>  $modify_date,
        );

        $retId = DB::table('reports')
            ->insertGetId($data);
        
        $arr = array(
            'id'        => $retId, 
            'name'      => $newname, 
            'comments'  => $comments, 
            'issue'     =>  $num_issue,
        );

        return json_encode($arr);
    }
    
    public function removeReport(Request $request)
    {
        $reportid = $request->input('id');
 
        $report = DB::table('reports')
                    ->select('id','path')
                    ->where('id','=',$reportid)
                    ->get()
                    ->first();
        
        unlink(public_path() . '/reports/' . $report->path);
        
        DB::table('reports')
            ->where('id','=',$reportid)
            ->delete();

        return '1';
    }
}
