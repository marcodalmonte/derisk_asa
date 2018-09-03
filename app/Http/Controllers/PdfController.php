<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class ReportPDF extends \fpdi\FPDI
{
    private function breakText($text,$chars_per_line)
    {
        $brokenText = array();
        
        $normalized = str_replace("\r\n", "\n", $text);
        $human_lines = explode("\n",$normalized);
        
        foreach ($human_lines as $human_line) {
            $broken = explode(" ",$human_line);
            
            if (1 == count($broken)) {
                $brokenText[] = $human_line;
                continue;
            }
            
            $totchars = 0;
            $curline = array();
            
            for ($k = 0; $k < count($broken); $k++) {
                if ($chars_per_line >= $totchars + strlen($broken[$k]) + (empty($curline) ? 0 : count($curline))) {
                    $curline[] = $broken[$k];
                    $totchars += strlen($broken[$k]);
                    
                    if ($k < count($broken) - 1) {
                        continue;
                    }
                }
                
                $brokenText[] = trim(implode(" ",$curline));
                $totchars = 0;
                $curline = array();
            }
        }
        
        return $brokenText;
    }
    
    private function sumUntil($values, $end, $exclude = -1)
    {
        if (empty($values)) {
            return 0;
        }
        
        if ($end == 0) {
            return $values[0];
        }
        
        $finalIndex = min($end,count($values));
        
        $sum = 0;
        
        for ($k = 0; $k < $finalIndex; $k++) {
            if ($k == $exclude) {
                continue;
            }
            
            $sum += $values[$k];
        }
        
        return $sum;
    }
    
    private function adaptPhoto($file, $w, $h) 
    {
        list($width, $height) = getimagesize($file);
                    
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        return $dst;
    }
    
    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0)
    {
        // Output text with automatic or explicit line breaks, at most $maxline lines
        $cw = &$this->CurrentFont['cw'];
        
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        
        $wmax = (($w - (2 * $this->cMargin)) * 1000) / $this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        
        if (($nb > 0) and ($s[$nb-1] == "\n")) {
            $nb--;
        }
        
        $b = 0;
        
        if ($border) {
            if ($border == 1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (is_int(strpos($border,'L'))) {
                    $b2 .= 'L';
                }
                
                if (is_int(strpos($border,'R'))) {
                    $b2 .= 'R';
                }
                
                $b = (is_int(strpos($border,'T')) ? ($b2 . 'T') : $b2);
            }
        }
        
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        
        while ($i < $nb) {
            // Get next character
            $c = $s[$i];
            
            if ($c == "\n") {
                // Explicit line break
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                
                if ($border and ($nl == 2)) {
                    $b = $b2;
                }
                
                if ($maxline and ($nl > $maxline)) {
                    return substr($s,$i);
                }
                
                continue;
            }
            
            if ($c == ' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            
            $l += $cw[$c];
            if ($l > $wmax) {
                // Automatic line break
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                    
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                } else {
                    if ($align == 'J') {
                        $this->ws = (($ns > 1) ? (($wmax - $ls) / 1000 * $this->FontSize / ($ns-1)) : 0);
                        $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    $i = $sep + 1;
                }
                
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                
                if ($border and ($nl == 2)) {
                    $b = $b2;
                }
                
                if ($maxline and ($nl > $maxline)) {
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    
                    return substr($s,$i);
                }
            } else {
                $i++;
            }
        }
        
        // Last chunk
        if ($this->ws > 0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        
        if ($border and is_int(strpos($border,'B'))) {
            $b .= 'B';
        }
        
        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
        $this->x = $this->lMargin;
        
        return '';
    }
    
    private function estimateQualityCheckTable($issue,$y)
    {
        $issue_to_print = $this->issues[$issue];
        
        $authors = $issue_to_print->authors;
        $tot_authors = count($authors);
        
        $surveyors = $issue_to_print->surveyors;
        $tot_surveyors = count($surveyors);
        
        $destinations = $issue_to_print->issued_to;
        $tot_destinations = count($destinations);
        
        return (($tot_authors + $tot_surveyors + $tot_destinations + 3) * 7);
    }
    
    private function printQualityCheckTable($issue,$y)
    {
        $stds = $this->defineStandardFont();
        
        $issue_to_print = $this->issues[$issue];
        
        $authors = $issue_to_print->authors;
        $tot_authors = count($authors);
        
        $surveyors = $issue_to_print->surveyors;
        $tot_surveyors = count($surveyors);
        
        $destinations = $issue_to_print->issued_to;
        $tot_destinations = count($destinations);
        
        $start_x = 12.75;
        $start_y = $y;
        $full_width = 185;
        
        $cell_width = $full_width / 4;
        $cell_height = 7;
        
        $headers = array(
            'Report Author',
            'Report Compiled On',
            'Lead Surveyor',
            'Date Checked',
            'Quality Check',
            'Date Authorised',
            'Date Issued',
            'To',
        );
        
        $this->SetFillColor(255,255,255);
        
        // Authors
        $this->Rect($start_x, $start_y, $cell_width, $tot_authors * $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, ($tot_authors * $cell_height) - 2, $headers[0], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width, $tot_authors * $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,((count($issue_to_print->authors) > 1) ? ($start_y + 1) : ($start_y + 0.5)));
        $this->MultiCell($cell_width - 2, ((count($issue_to_print->authors) > 1) ? ($cell_height - 1) : ($cell_height - 0.5)), implode("\n",$issue_to_print->authors), 0, 'L', false);
        $this->Rect($start_x + (2 * $cell_width), $start_y, 2 * $cell_width, (1 + $tot_authors) * $cell_height, 'FD');

        $this->SetXY($start_x + (2 * $cell_width) + 1,$start_y + 1);
        
        $existing = array();
        
        foreach ($issue_to_print->authors_signatures as $signature) {
            if (empty($signature)) {
                continue;
            }
            
            $pict = public_path() . '/signatures/' . $signature;
            
            if (!file_exists($pict)) {
                continue;
            }
            
            $existing[] = $pict;
        }
        
        if (!empty($existing)) {
            $pic_space_w = ((2 * $cell_width) - 1) / count($existing);
            if (count($existing) == 1) {
                $pic_space_w = ((2 * $cell_width) - 1) / 2;
            }
            $pic_space_h = (1 + $tot_authors) * $cell_height;

            foreach ($existing as $k => $pic) {
                $adapted = $this->adaptPhoto($pic, $pic_space_w, $pic_space_h);

                if ($adapted !== null) {
                    $picture = '/tmp/' . substr($pic, strrpos($pic, '/') + 1);
                    imagejpeg($adapted, $picture);

                    $this->SetXY($start_x + (2 * $cell_width) + ($k * $pic_space_w) + 0.5,$start_y + 1);
                    
                    $this->Image($picture, $start_x + (2 * $cell_width) + ($k * $pic_space_w) + 0.5, $start_y + 1, $pic_space_w, $pic_space_h, 'jpg', '', 'C', true);
                    
                    unlink($picture);
                }
            }
        }
        
        $start_y += $tot_authors * $cell_height;
        
        $date_completed = (empty($issue_to_print->date_completed) ? '' : date('d/m/Y',strtotime($issue_to_print->date_completed)));
        
        // Report Compiled On
        $this->Rect($start_x, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $headers[1], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,$start_y + 1);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $date_completed, 0, 'L', false);
        
        $start_y += $cell_height;
        
        // Lead Surveyor
        $this->Rect($start_x, $start_y, $cell_width, $tot_surveyors * $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, ($tot_surveyors * $cell_height) - 2, $headers[2], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width, $tot_surveyors * $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,((count($issue_to_print->surveyors) > 1) ? ($start_y + 1) : ($start_y + 0.5)));
        $this->MultiCell($cell_width - 2, ((count($issue_to_print->surveyors) > 1) ? ($cell_height - 1) : ($cell_height - 0.5)), implode("\n",$issue_to_print->surveyors), 0, 'L', false);
        $this->Rect($start_x + (2 * $cell_width), $start_y, 2 * $cell_width, (1 + $tot_surveyors) * $cell_height, 'FD');
        
        $existing = array();
        
        foreach ($issue_to_print->surveyors_signatures as $signature) {
            if (empty($signature)) {
                continue;
            }
            
            $pict = public_path() . '/signatures/' . $signature;
            
            if (!file_exists($pict)) {
                continue;
            }
            
            $existing[] = $pict;
        }
        
        if (!empty($existing)) {
            $pic_space_w = ((2 * $cell_width) - 1) / count($existing);
            if (count($existing) == 1) {
                $pic_space_w = ((2 * $cell_width) - 1) / 2;
            }
            
            $pic_space_h = (1 + $tot_authors) * $cell_height;

            foreach ($existing as $k => $pic) {
                $adapted = $this->adaptPhoto($pic, $pic_space_w, $pic_space_h);

                if ($adapted !== null) {
                    $picture = '/tmp/' . substr($pic, strrpos($pic, '/') + 1);
                    imagejpeg($adapted, $picture);
                    
                    $this->SetXY($start_x + (2 * $cell_width) + ($k * $pic_space_w) + 0.5,$start_y + 1);

                    $this->Image($picture, $start_x + (2 * $cell_width) + ($k * $pic_space_w) + 0.5, $start_y + 1, $pic_space_w, $pic_space_h, 'jpg', '', 'C', true);
                    
                    unlink($picture);
                }
            }
        }
        
        $start_y += $tot_surveyors * $cell_height;
        
        $date_checked = (empty($issue_to_print->date_checked) ? '' : date('d/m/Y',strtotime($issue_to_print->date_checked)));
        
        // Date Checked
        $this->Rect($start_x, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $headers[3], 0, 'L', false);
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,$start_y + 1);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $date_checked, 0, 'L', false);
        
        $start_y += $cell_height;
        
        // Quality Check
        $this->Rect($start_x, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $headers[4], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,$start_y + 1);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $issue_to_print->quality_check, 0, 'L', false);
        $this->Rect($start_x + (2 * $cell_width), $start_y, 2 * $cell_width, 2 * $cell_height, 'FD');
        
        if (!empty($issue_to_print->quality_signature)) {
            $pic = public_path() . '/signatures/' . $issue_to_print->quality_signature;
            
            if (file_exists($pic)) {
                $pic_space_w = (2 * $cell_width) / 2;
                $pic_space_h = 2 * $cell_height;
                
                $adapted = $this->adaptPhoto($pic, $pic_space_w, $pic_space_h);

                $picture = '/tmp/' . substr($pic, strrpos($pic, '/') + 1);
                imagejpeg($adapted, $picture);

                $this->Image($picture, $start_x + (2 * $cell_width) + 0.5, $start_y + 1, $pic_space_w, $pic_space_h, 'jpg', '', 'C', true);

                unlink($picture);
            }
        }
        
        $start_y += $cell_height;
        
        $date_authorised = (empty($issue_to_print->date_authorised) ? '' : date('d/m/Y',strtotime($issue_to_print->date_authorised)));
        
        // Date Authorised
        $this->Rect($start_x, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $headers[5], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width, $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,$start_y + 1);
        $this->MultiCell($cell_width - 2, $cell_height - 2, $date_authorised, 0, 'L', false);
        
        $start_y += $cell_height;
        
        $date_issued = (empty($issue_to_print->date_issued) ? '' : date('d/m/Y',strtotime($issue_to_print->date_issued)));
        
        // Date Issued
        $this->Rect($start_x, $start_y, $cell_width, $tot_destinations * $cell_height, 'FD');
        $this->SetXY($start_x + 1,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, ($tot_destinations * $cell_height) - 2, $headers[6], 0, 'L', false);
        $this->Rect($start_x + $cell_width, $start_y, $cell_width - 10, $tot_destinations * $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + $cell_width + 1,$start_y + 1);
        $this->MultiCell($cell_width - 2, ($tot_destinations * $cell_height) - 2, $date_issued, 0, 'L', false);
        
        $start_x += 2 * $cell_width;       
        
        // To
        $this->Rect($start_x - 10, $start_y, 10, $tot_destinations * $cell_height, 'FD');
        $this->SetXY($start_x - 9,$start_y + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->MultiCell($cell_width - 2, ($tot_destinations * $cell_height) - 2, $headers[7], 0, 'L', false);
        $this->Rect($start_x, $start_y, 2 * $cell_width, $tot_destinations * $cell_height, 'FD');
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetXY($start_x + 1,((count($issue_to_print->issued_to) > 1) ? ($start_y + 1) : ($start_y + 0.5)));
        $this->MultiCell($cell_width - 2, ((count($issue_to_print->issued_to) > 1) ? ($cell_height - 1) : ($cell_height - 0.5)), implode("\n",$issue_to_print->issued_to), 0, 'L', false);
        
        $start_y += $tot_destinations * $cell_height;
        
        return $start_y;
    }
    
    private $nreport;    
    private $survey;
    private $address;
    private $siteaddress;
    private $surveyor;
    private $report_author;
    private $date;
    private $issues;
    private $first_issue;
    private $asbestos;
    private $observations;
    private $inaccessible;
    private $certificate;
    private $floorplans;
    private $airmonitoring;
    private $buildings;
    private $floors;
 
    protected $_toc = array();
    protected $_numbering = false;
    protected $_numberingFooter = false;
    protected $_numPageNum = 2;

    private $printHeader;
    private $printFooter;

    public function __construct($nreport) 
    {
        parent::__construct();
        
	$this->printHeader = true;
	$this->printFooter = true;

        $this->nreport = $nreport;
        
        $this->survey = DB::table('surveys')
                ->join('clients','clients.id','=','surveys.client_id')
                ->join('surveytypes','surveytypes.id','=','surveys.surveytype_id')
                ->where('surveys.jobnumber','=',$nreport)
                ->select('surveys.id AS id','surveys.jobnumber AS jobnumber','surveys.ukasnumber AS ukasnumber','surveys.reinspectionof AS reinspectionof','clients.companyname AS client','clients.contact AS contact','clients.emails AS emails','clients.address1 AS address1','clients.address2 AS address2','clients.city AS city','clients.postcode AS postcode','surveytypes.surveytype AS surveytype','surveys.siteaddress AS siteaddress','surveys.sitedescription AS sitedescription','surveys.scope AS scope','surveys.agreed_excluded_areas AS agreed_excluded_areas','surveys.deviations_from_standard_methods AS deviations_from_standard_methods',DB::raw('DATE_FORMAT(surveys.surveydate,"%d/%m/%Y") AS surveydate'),'surveys.issued_to AS issued_to','surveys.othersdates AS othersdates')
                ->get()
                ->first();
        
        $othersdates = $this->survey->othersdates;
        
        if (!empty($this->survey->reinspectionof)) {
            $othersurveyid = trim($this->survey->reinspectionof);
            
            $othersurvey = DB::table('surveys')
                    ->where('id','=',$othersurveyid)
                    ->select('ukasnumber AS ukasnumber')
                    ->get()
                    ->first();
            
            if ($othersurvey !== null) {
                $this->survey->reinspectionof = $othersurvey->ukasnumber;
            }
        }
        
        $this->survey->othersdates = explode("|",$othersdates);
        
        for ($k = 0; $k < count($this->survey->othersdates); $k++) {
            $this->survey->othersdates[$k] = trim($this->survey->othersdates[$k]);
        }
        
        if ((1 == count($this->survey->othersdates)) and empty($this->survey->othersdates[0])) {
            $this->survey->othersdates = array();
        }

        $this->nreport = $this->survey->ukasnumber;
        
        $this->siteaddress = explode("\n",$this->survey->siteaddress);
        
        $siteaddress = array($this->siteaddress[0],);
        
        if (4 == count($this->siteaddress)) {            
            $siteaddress[1] = trim($this->siteaddress[1]);
            $siteaddress[2] = trim($this->siteaddress[2]) . ' ' . trim($this->siteaddress[3]);
        } else if (5 == count($this->siteaddress)) {
            $siteaddress[1] = trim($this->siteaddress[1]) . ', ' . trim($this->siteaddress[2]);
            $siteaddress[2] = trim($this->siteaddress[3]) . ' ' . trim($this->siteaddress[4]);
        }
                
        $this->siteaddress = $siteaddress;
        
        $this->address = array('address1' => $this->survey->address1,);
        $this->address['address2'] = $this->survey->address2;
        $this->address['city'] = $this->survey->city;
        $this->address['postcode'] = $this->survey->postcode;
        
        $this->surveyor = DB::table('surveys_surveyors')
                ->join('surveyors','surveyors.id','=','surveys_surveyors.surveyor_id')
                ->where('surveys_surveyors.survey_id','=',$this->survey->id)
                ->select('surveyors.name AS name','surveyors.surname AS surname')
                ->get()
                ->first();
        
        $this->date = date('d/m/Y',time());
        
        $authuser = Auth::user();
        
        $this->report_author = $authuser->name . ' ' . $authuser->surname;
        
        $reports_issues = DB::table('reports')
                ->where('survey_id','=',$this->survey->id)
                ->orderBy('created_at','asc')
                ->get()
                ->all();
        
        $tot_issues = DB::table('reports_issues')
                ->where('survey_id','=',$this->survey->id)
                ->get()
                ->last();
                
        $this->issues = DB::table('reports_issues')
                ->where('survey_id','=',$this->survey->id)
                ->orderBy('revision','asc')
                ->get()
                ->all();
        
        if ($tot_issues == null) {
            $this->issues = array();
            $tot_issues = 0;
        } else {
            $tot_issues = $tot_issues->revision;
            
            for ($k = 0; $k < count($this->issues); $k++) {
                $this->issues[$k]->authors = explode("|",$this->issues[$k]->authors);
                $this->issues[$k]->authors_signatures = explode("|",$this->issues[$k]->authors_signatures);
                $this->issues[$k]->surveyors = explode("|",$this->issues[$k]->surveyors);
                $this->issues[$k]->surveyors_signatures = explode("|",$this->issues[$k]->surveyors_signatures);
                $this->issues[$k]->issued_to = explode("|",$this->issues[$k]->issued_to);
                $this->issues[$k]->comments = (!isset($reports_issues[$k]) ? 'Last Issue' : $reports_issues[$k]->comments);
            }
        }
        
        $this->first_issue = $tot_issues;
        
        if ($this->first_issue < 10) {
            $this->first_issue = '0' . $this->first_issue;
        }
        
        $asb = DB::table('inspections')
                ->join('rooms','rooms.id','=','inspections.room_id','left outer')
                ->join('floors','floors.id','=','inspections.floor_id','left outer')
                ->join('products','products.id','=','inspections.product_id','left outer')
                ->join('extents','extents.id','=','inspections.extent_of_damage','left outer')
                ->join('surface_treatments','surface_treatments.id','=','inspections.surface_treatment','left outer')
                ->where([
                    ['inspections.survey_id','=',$this->survey->id],
                    ['inspections.accessible','=',1],
                    ['inspections.observation','=',0],
                    ['inspections.results','<>','Non-asbestos'],
                    ['inspections.results','<>','']
                ])
                ->select('inspections.building AS building','inspections.floor_id AS floor_id','floors.name AS floor_name','floors.code AS floor','floors.menu AS floor_menu','rooms.name AS room',DB::raw('REPLACE(inspections.room_name,"\n","") AS room_name'),DB::raw('REPLACE(inspections.comments,"\n","") AS comments'),DB::raw('REPLACE(inspections.material_location,"\n","") AS material_location'),DB::raw('REPLACE(inspections.recommendations,"\n","") AS recommendations'),DB::raw('REPLACE(inspections.recommendationsNotes,"\n","") AS recommendationsNotes'),'inspections.inspection_number AS inspection_number','inspections.results AS asbestostype','products.name AS product','products.score AS product_score','extents.id AS extent_id','extents.name AS extent','extents.score AS extent_score','inspections.surface_treatment AS surface_treatment_id','surface_treatments.score AS surface_treatment_score','surface_treatments.code AS surftreatment','surface_treatments.description AS surfdescription','inspections.quantity AS quantity','inspections.photo AS photo','inspections.referral AS referral','inspections.presumed AS presumed','inspections.accessibility AS accessibility')
                ->orderBy('inspections.building','asc')
                ->orderBy('floors.menu','asc')
                ->orderBy('inspections.room_id','asc')
                ->orderBy('inspections.inspection_number','asc')
                ->get()
                ->all();
        
        if (!empty($asb)) {
            for ($k = 0; $k < count($asb); $k++) {
                if (!is_int($asb[$k]->referral)) {
                    continue;
                }
                
                $found = DB::table('inspections')
                    ->where([
                        ['id','=',$asb[$k]->referral]
                    ])
                    ->select('id','inspection_number')
                    ->get()
                    ->first();
                
                $asb[$k]->referral = $found->inspection_number;
            }
        }
        
        $this->asbestos = array();
        
        foreach ($asb as $curasb) {
            if (!isset($this->asbestos[trim($curasb->building)])) {
                $this->asbestos[trim($curasb->building)] = array();
            }
            
            if (!isset($this->asbestos[trim($curasb->building)][$curasb->floor_menu])) {
                $this->asbestos[trim($curasb->building)][$curasb->floor_menu] = array();
            }
            
            $this->asbestos[trim($curasb->building)][$curasb->floor_menu][] = $curasb;
        }
        
        $obs = DB::table('inspections')
                ->join('rooms','rooms.id','=','inspections.room_id','left outer')
                ->join('floors','floors.id','=','inspections.floor_id','left outer')
                ->join('products','products.id','=','inspections.product_id','left outer')
                ->join('extents','extents.id','=','inspections.extent_of_damage','left outer')
                ->join('surface_treatments','surface_treatments.id','=','inspections.surface_treatment','left outer')
                ->where([
                    ['inspections.survey_id','=',$this->survey->id],
                ])
                ->select('inspections.building AS building','inspections.floor_id AS floor_id','floors.name AS floor_name','floors.code AS floor','floors.menu AS floor_menu','rooms.name AS room',DB::raw('REPLACE(inspections.room_name,"\n","") AS room_name'),DB::raw('REPLACE(inspections.comments,"\n","") AS comments'),DB::raw('REPLACE(inspections.material_location,"\n","") AS material_location'),DB::raw('REPLACE(inspections.recommendations,"\n","") AS recommendations'),DB::raw('REPLACE(inspections.recommendationsNotes,"\n","") AS recommendationsNotes'),'inspections.inspection_number AS inspection_number','inspections.results AS asbestostype','products.name AS product','products.score AS product_score','extents.id AS extent_id','extents.name AS extent','extents.score AS extent_score','inspections.surface_treatment AS surface_treatment_id','surface_treatments.score AS surface_treatment_score','surface_treatments.code AS surftreatment','surface_treatments.description AS surfdescription','inspections.quantity AS quantity','inspections.photo AS photo','inspections.referral AS referral','inspections.presumed AS presumed','inspections.accessibility AS accessibility')
                ->orderBy('inspections.building','asc')
                ->orderBy('floors.menu','asc')
                ->orderBy('inspections.room_id','asc')
                ->orderBy('inspections.inspection_number','asc')
                ->get()
                ->all();

	if (!empty($obs)) {
            for ($k = 0; $k < count($obs); $k++) {
                if (!is_int($obs[$k]->referral)) {
                    continue;
                }
                
                $found = DB::table('inspections')
                    ->where([
                        ['id','=',$obs[$k]->referral]
                    ])
                    ->select('id','inspection_number')
                    ->get()
                    ->first();
                
                $obs[$k]->referral = $found->inspection_number;
            }
        }
        
        $this->observations = array();
        
        foreach ($obs as $curobs) {
            if (!isset($this->observations[trim($curobs->building)])) {
                $this->observations[$curobs->building] = array();
            }
            
            if (!isset($this->observations[trim($curobs->building)][$curobs->floor_menu])) {
                $this->observations[trim($curobs->building)][$curobs->floor_menu] = array();
            }
            
            $this->observations[trim($curobs->building)][$curobs->floor_menu][] = $curobs;
        }
        
        $this->buildings = array();
        
        $inspbuildings = DB::table('inspections')
                ->where([
                    ['inspections.survey_id','=',$this->survey->id],
                ])
                ->select('inspections.building AS building')
                ->orderBy('inspections.created_at','asc')
                ->distinct()
                ->get()
                ->all();
        
        foreach ($inspbuildings as $curbuilding) {
            if (in_array($curbuilding->building,$this->buildings)) {
                continue;
            }
            
            $this->buildings[] = trim($curbuilding->building);
        }
        
        $limited = DB::table('inspections')
                ->join('rooms','rooms.id','=','inspections.room_id','left outer')
                ->join('floors','floors.id','=','inspections.floor_id','left outer')
                ->where([
                    ['inspections.survey_id','=',$this->survey->id],
                    ['inspections.accessible','=',0],
                ])
                ->select('inspections.building AS building','floors.code AS floor','floors.menu AS floor_menu','rooms.name AS room',DB::raw('REPLACE(inspections.room_name,"\n","") AS room_name'),DB::raw('REPLACE(inspections.comments,"\n","") AS comments'),DB::raw('REPLACE(inspections.recommendations,"\n","") AS recommendations'),DB::raw('REPLACE(inspections.recommendationsNotes,"\n","") AS recommendationsNotes'),DB::raw('REPLACE(inspections.material_location,"\n","") AS material_location'),'inspections.referral AS referral')
                ->orderBy('inspections.building','asc')
                ->orderBy('floors.menu','asc')
                ->orderBy('inspections.room_id','asc')
                ->orderBy('inspections.inspection_number','asc')
                ->get()
                ->all();
        
        $noaccess = array();
        
        foreach ($limited as $curlim) {
            if (!isset($noaccess[trim($curlim->building)])) {
                $noaccess[$curlim->building] = array();
            }
            
            if (!isset($noaccess[trim($curlim->building)][$curlim->floor_menu])) {
                $noaccess[trim($curlim->building)][$curlim->floor_menu] = array();
            }
            
            $noaccess[trim($curlim->building)][$curlim->floor_menu][] = $curlim;
        }
        
        $this->inaccessible = array();
        
        foreach ($noaccess as $mybuilding => $myfloor) {
            foreach ($myfloor as $curmenu => $nolimits) {
                foreach ($nolimits as $nolimit) {
                    $this->inaccessible[] = $nolimit;
                }
            }
        }
        
        $this->certificate = DB::table('files')
                ->where([
                    ['files.survey_id','=',$this->survey->id],
                    ['files.path','=',$this->survey->ukasnumber . '_certificate_analysis.pdf'],
                ])
                ->select('files.path AS path')
                ->get()
                ->first();
        
        $this->floorplans = DB::table('files')
                ->where([
                    ['files.survey_id','=',$this->survey->id],
                    ['files.path','=',$this->survey->ukasnumber . '_floor_plans.pdf'],
                ])
                ->select('files.path AS path')
                ->get()
                ->first();
        
        $this->airmonitoring = DB::table('files')
                ->where([
                    ['files.survey_id','=',$this->survey->id],
                    ['files.path','=',$this->survey->ukasnumber . '_airmonitoring.pdf'],
                ])
                ->select('files.path AS path')
                ->get()
                ->first();
        
        $buildingfloors = DB::table('floors')
                ->select('id','name','menu')
                ->orderBy('menu','asc')
                ->get()
                ->all();
        
        $this->floors = array();
        
        foreach ($buildingfloors as $curfloor) {
            $this->floors[$curfloor->menu] = $curfloor->name;
        }
    }
    
    public function getElements()
    {
        return array(
            'survey'        =>  $this->survey,
            'surveyor'      =>  $this->surveyor,
            'address'       =>  $this->address,
            'siteaddress'   =>  $this->siteaddress,
            'date'          =>  $this->date,
            'author'        =>  $this->report_author,
            'issues'        =>  $this->issues,
            'first'         =>  $this->first_issue,
            'asbestos'      =>  $this->asbestos,
            'observations'  =>  $this->observations,
            'inaccessible'  =>  $this->inaccessible,
            'certificate'   =>  $this->certificate,
            'floor_plans'   =>  $this->floorplans,
            'airmonitoring' =>  $this->airmonitoring,
            'floors'        =>  $this->floors,
        );
    }
    
    public function defineStandardFont()
    {
        $pdf_standards = array(
            'font-family'       =>  'Calibri',
            'font-bigger'       =>  '20',
            'font-big'          =>  '16',
            'font-size'         =>  '11',
            'font-mini'         =>  '10',
            'font-mini-small'   =>  '9',
            'font-table'        =>  '8',
            'font-style'        =>  '',
            'paper-format'      =>  'A4',
            'paper-orientation' =>  'P',
            'paper-unit'        =>  'mm',
            'cell_height'       =>  '20',
        );

        return $pdf_standards;
    }

    public function AddPage($orientation = '', $format = '', $rotationOrKeepmargins = false, $tocpage = false)
    {
        parent::AddPage($orientation,$format,$rotationOrKeepmargins);
        
        if($this->_numbering) {
            $this->_numPageNum++;
        }
    }

    public function startPageNums() 
    {
        $this->_numbering = true;
        $this->_numberingFooter = false;
    }

    public function stopPageNums() 
    {
        $this->_numbering = false;
    }

    public function numPageNo() 
    {
        return $this->_numPageNum;
    }

    public function TOC_Entry($txt, $level = 0) 
    {
        $this->_toc[] = array('t' => $txt, 'l' => $level, 'p' => $this->numPageNo());
    }
    
    public function insertTOC($location = 1, $entrySize = 10, $tocfont = 'Calibri') 
    {        
        // make toc at end
        $this->stopPageNums();
        $tocstart = $this->page;

	$this->SetY(45);

	$left_limit = 24;

        foreach ($this->_toc as $t) {
	    $this->SetX($left_limit);
            // Offset
            $level = $t['l'];
            if ($level > 0) {
                $this->Cell($level * 8);
            }

            $weight = '';
            if ($level == 0) {
                $weight = 'B';
            }

            $str = $t['t'];
            $this->SetFont($tocfont,$weight,$entrySize);
            $strsize = $this->GetStringWidth($str);
            $this->Cell($strsize+2,$this->FontSize+2,$str,0);

            // Filling dots
            $this->SetFont($tocfont,'',$entrySize);
            $PageCellSize = $this->GetStringWidth($t['p']) + 2;
            $w = $this->w - $this->lMargin - $this->rMargin - $PageCellSize - ($level * 8) - ($strsize + 2);
	    $nb = ($w - 4) / $this->GetStringWidth('.');
            $dots = str_repeat('.',$nb - $left_limit);
            $this->Cell($w - $left_limit,$this->FontSize+2,$dots,0,0,'R');

            // Page number
            $this->Cell($PageCellSize,$this->FontSize+2,$t['p'],0,1,'R');
        }
        
        $stds = $this->defineStandardFont();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        $this->SetXY(170, 276);
        $this->Write(0,'Page 2 of ' . count($this->pages));

        // Grab it and move to selected location
        $n = $this->page;
        $n_toc = $n - $tocstart + 1;
        $last = array();

        // store toc pages
        
        for($i = $tocstart;$i <= $n;$i++) {
            $last[] = $this->pages[$i];            
        }

        // move pages
        for($i = $tocstart-1;$i >= $location-1;$i--) {
            $this->pages[$i+$n_toc] = $this->pages[$i];
            if (isset($this->PageSizes[$i])) {
                $this->PageSizes[$i+$n_toc] = $this->PageSizes[$i];
            } else {
                $this->PageSizes[$i+$n_toc] = null;
            }
        }

        // Put toc pages at insert point
        for($i = 0;$i < $n_toc;$i++) {
            $this->pages[$location + $i] = $last[$i];
        }
    }
    
    public function _putpages()
    {
        $nb = $this->page;
        if(!empty($this->AliasNbPages)) {
            // Replace number of pages
            for($n = 1; $n <= $nb; $n++) {
                $this->pages[$n] = str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
            }
        }
        
        if($this->DefOrientation=='P') {
            $wPt = $this->DefPageSize[0]*$this->k;
            $hPt = $this->DefPageSize[1]*$this->k;
        } else {
            $wPt = $this->DefPageSize[1]*$this->k;
            $hPt = $this->DefPageSize[0]*$this->k;
        }
        
        $filter = ($this->compress) ? '/Filter /FlateDecode ' : '';
        for($n = 1; $n <= $nb; $n++) {
            // Page
            $this->_newobj();
            $this->_out('<</Type /Page');
            $this->_out('/Parent 1 0 R');
            if(isset($this->PageSizes[$n])) {
                $this->_out(sprintf('/MediaBox [0 0 %.2F %.2F]',$this->PageSizes[$n][0],$this->PageSizes[$n][1]));
            }
            $this->_out('/Resources 2 0 R');
            if(isset($this->PageLinks[$n])) {
                // Links
                $annots = '/Annots [';
                foreach($this->PageLinks[$n] as $pl) {
                    $rect = sprintf('%.2F %.2F %.2F %.2F',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
                    $annots .= '<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
                    if(is_string($pl[4])) {
                        $annots .= '/A <</S /URI /URI '.$this->_textstring($pl[4]).'>>>>';
                    } else {
                        $l = $this->links[$pl[4]];
                        $h = isset($this->PageSizes[$l[0]]) ? $this->PageSizes[$l[0]][1] : $hPt;
                        $annots .= sprintf('/Dest [%d 0 R /XYZ 0 %.2F null]>>',1+2*$l[0],$h-$l[1]*$this->k);
                    }
                }
                $this->_out($annots.']');
            }
            
            if($this->PDFVersion>'1.3') {
                $this->_out('/Group <</Type /Group /S /Transparency /CS /DeviceRGB>>');
            }
            
            $this->_out('/Contents '.($this->n+1).' 0 R>>');
            $this->_out('endobj');
            // Page content
            $p = ($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
            $this->_newobj();
            $this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
            $this->_putstream($p);
            $this->_out('endobj');
        }
        // Pages root
        $this->offsets[1] = strlen($this->buffer);
        $this->_out('1 0 obj');
        $this->_out('<</Type /Pages');
        $kids = '/Kids [';
        for($i=0;$i<$nb;$i++)
                $kids .= (3+2*$i).' 0 R ';
        $this->_out($kids.']');
        $this->_out('/Count '.$nb);
        $this->_out(sprintf('/MediaBox [0 0 %.2F %.2F]',$wPt,$hPt));
        $this->_out('>>');
        $this->_out('endobj');
    }
    
    public function Header()
    {
	if (!$this->printHeader) {
		return;
	}

        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        if ($this->page == 1) {
	    $picture = public_path() . '/img/coverpage.jpg';

	    $imgw = 210;
	    $imgh = 297;

            $this->Image($picture, 0, 0, $imgw, $imgh);

            return;
        }   
        
        $picture = public_path() . '/img/logo.png';
        $imgw = 50;
        $imgh = 12;
        
        $this->Image($picture, 20, 6, $imgw, $imgh, 'png', '', 'C', true);
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        
        $x = 125;
        $y = 9; 
        
        $this->SetXY($x, $y);
        $this->Write(0,$elems['survey']->ukasnumber);
        
        $y = 13.8; 
        
        $this->SetXY($x, $y);
        $this->Write(0,$this->first_issue);
        
        $y = 16.3;
        
        $this->SetXY($x, $y);
        $this->MultiCell(0,5, str_replace("\n"," ",$elems['survey']->siteaddress), 0, 'L', false);
    }
    
    public function Footer()
    {
	if (!$this->printFooter) {
                return;
        }
	
	$stds = $this->defineStandardFont();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        $page = $this->PageNo();
        
        $x = 170;
        $y = 278;
        
        if ($this->CurOrientation == 'L') {
            $x = 250;
            $y = 191; 
        }
        
        if ($page > 1) {
            $this->SetXY($x, $y);
            $this->Write(0,'Page ' . (1 + $page) . ' of {nb}');
        }
    }

    public function setPrintHeader($printHeader = true)
    {
	$this->printHeader = $printHeader;
    }

    public function setPrintFooter($printFooter = true)
    {
        $this->printFooter = $printFooter;
    }
    
    public function nbLines($w, $txt) 
    {
        // Computes the number of lines a MultiCell of width w will take
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if (($nb > 0) and ($s[$nb - 1] == "\n")) {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        
        return $nl;
    }
    
    public function checkPageBreak($h) 
    {
        
    }
    
    public function printCoverPage()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $survey = $elems['survey'];
        
        $this->setTitle(ucwords('Report ' . $this->nreport),true);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-bigger']);
        $this->setTextColor(255,255,255);
        
        $x = 31;
        
        $y = 45;
        
        $this->SetXY($x, $y);
        
        $title_to_print = 'Asbestos ' . ucfirst($this->survey->surveytype) . ' Survey';
        
        if (!empty($this->survey->reinspectionof)) {
            $title_to_print = 'Asbestos reinspection of Survey ' . $this->survey->reinspectionof;
        }
        
        $this->Cell(162,10,$title_to_print,0,1,'L');
        
        $this->SetFont($stds['font-family'],'B',$stds['font-big']);
        
        $y += 20;
        
        $this->SetXY($x, $y);
        
        $this->Cell(162,10,$survey->client,0,1,'L');
        
        $y += 10;
        
        $fields = $elems['siteaddress'];
        
        $dates_set = array();
        
        if (!empty($survey->surveydate)) {
            $dates_set[] = $survey->surveydate;
        }
        
        if (!empty($survey->othersdates)) {
            foreach ($survey->othersdates as $otherdate) {
                $dates_set[] = date('d/m/Y',strtotime($otherdate));
            }
        }
        
        foreach ($fields as $n => $field) {
            $this->SetXY($x, $y);
            $this->Cell(162,10,$field,0,1,'L');
            $y += 7;
        }
        
        $y += 3;
        
        if (!empty($dates_set)) {
            $alldates = implode(', ',$dates_set);
            
            $this->SetXY($x, $y);
            $this->MultiCell(150,7, $alldates, 0, 'L', false);
            $y += 7;
        }
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        
        $x = 160;
        $y = 108.25;
        
        $tot_issues = count($elems['issues']);
        
        if ($tot_issues == 0) {
            $tot_issues++;
        }
        
        if ($tot_issues < 10) {
            $tot_issues = '0' . $tot_issues;
        }
        
        $small_fields = array(
            'Job Number ' . $this->nreport, 
            'Issue: ' . $tot_issues,
        );
        
        foreach ($small_fields as $field) {
            $this->SetXY($x, $y);
            $this->MultiCell(162,7, $field, 0, 'L', false);
            $y += 5.6;
        }
        
        return $y;
    }
    
    public function printReportAuthorisationTable($start_y)
    {
        $survtype = strtolower($this->survey->surveytype);
        
        if (!empty($this->survey->reinspectionof)) {
            $survtype = 'reinspection';
        }
        
        $template = public_path() . '/templates/auth_' . str_replace(" ","_",$survtype) . '.pdf';
        
        $this->AddPage();
        $this->TOC_Entry('Report Authorisations', 0);
        $this->setSourceFile($template);
        $tplIdx = $this->importPage(1);
        $this->useTemplate($tplIdx);
        
        $stds = $this->defineStandardFont();
        $elems = $this->getElements();
        
        $start_x = 12.75;
        $cell_height = 7;
        
        $y = $start_y;
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->setTextColor(0,0,0);
        
        $this->SetXY($start_x,$y);
            
        foreach ($elems['issues'] as $n => $issue) {
            $total_table_height = $this->estimateQualityCheckTable($n, $y + $cell_height - 2);
            
            if (($y + $total_table_height + $cell_height) > 275) {
                $this->AddPage('P');
                $y = $start_y;
                
                $this->SetFont($stds['font-family'],'',$stds['font-size']);
                $this->setTextColor(0,0,0);

                $this->SetXY($start_x,$y);
            }
            
            $sentence = 'Quality Check';
            if (count($elems['issues']) > 1) {
                $sentence .= ' Issue ' . (($n < 9) ? ('0' . (1 + $n)) : (1 + $n));
            }
            
            $this->SetFillColor(217,217,217);
            $this->SetDrawColor(0,0,0);
            $this->Rect($start_x, $y - 4.50, 185, 7, 'FD');
            
            $this->SetXY(15, $y);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Text(15, $y, $sentence);
            
            $y = $this->printQualityCheckTable($n, $y + $cell_height - 4.50);
            
            $y += $cell_height + 5;
        }
        
        return $y;
    }
    
    public function printIssuesTable($start_y)
    {
        $y = $start_y;
        
        $start_x = 12.75;
     
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->SetXY($start_x , $y);
        
        $h = 16 + (5 * count($elems['issues']));
        
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        $this->Rect($start_x , $y, 185.25, $h, 'FD');
        
        $y += 4;
        
        $this->SetXY(20, $y);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->Text(20, $y, 'Issue Summary Table');
        
        $y += 2;
        
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
        foreach ($elems['issues'] as $i => $curissue) {
            $issue_num = $curissue->revision;
            if ($issue_num < 10) {
                $issue_num = '0' . $issue_num;
            }
            
            $comments = 'Last Issue';
            if (!empty($curissue->comments)) {
                $comments = $curissue->comments;
            }
                        
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Rect(18, $y, 30, 8, 'FD');
            $this->SetXY(19, $y + 1);
            $this->MultiCell(28, 6, 'Issue ' . $issue_num, 0, 'L', true);                        
            
            $this->SetFont($stds['font-family'],'',$stds['font-size']);
            $this->Rect(48, $y, 144, 8, 'FD');
            $this->SetXY(49, $y + 1);
            $this->MultiCell(142, 6, ((1 == $curissue->revision) ? 'Original Issue' : $comments), 0, 'L', true);
            
            $y += 8;
        }
        
        return $y;
    }
    
    public function printSurveyDetailsTable($start_y)
    {
        $y = $start_y;
        
        $start_x = 12.75;
        $width = 185;
        $low_limit = 273;
     
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->SetXY($start_x, $y);
        
        $h = 20 + (5 * 5);
        
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        $this->Rect($start_x, $y, 185.25, $h, 'FD');
        
        $y += 4;
        
        $this->SetXY(20, $y);
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->Text(20, $y, 'Client Details');
        
        $y += 2;
        
        $emails = explode(';',$elems['survey']->emails);
        
        $fields = array(
            'Company'   =>  $elems['survey']->client, 
            'Contact'   =>  $elems['survey']->contact,
            'Address'   =>  '',
        );
        
        $fields['Address'] = $elems['address']['address1'];
        if (!empty($elems['address']['address1']) and !empty($elems['address']['address2'])) {
            $fields['Address'] .= "\n";
        }
        if (!empty($elems['address']['address2'])) {
            $fields['Address'] .= $elems['address']['address2'];
        }
        if (!empty($fields['Address']) and (!empty($elems['address']['city']) or !empty($elems['address']['postcode']))) {
            $fields['Address'] .= "\n";
        }
        if (!empty($elems['address']['city'])) {
            $fields['Address'] .= $elems['address']['city'];
        }
        if (!empty($elems['address']['city']) and !empty($elems['address']['postcode'])) {
            $fields['Address'] .= ' ' . $elems['address']['postcode'];
        }
        
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
        foreach ($fields as $field => $value) {
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Rect(18, $y, 30, (('Address' == $field) ? 20 : 8), 'FD');
            $this->SetXY(19, $y + 1);
            $this->MultiCell(28, 6, $field, 0, 'L', true);                        
            
            $this->SetFont($stds['font-family'],'',$stds['font-size']);
            $this->Rect(48, $y, 144, (('Address' == $field) ? 20 : 8), 'FD');
            $this->SetXY(49, $y + 1);
            $this->MultiCell(142, 6, $value, 0, 'L', true);
            
            $y += 8;
        }
        
        $y += 18;
        
        $fields = array(
            0   =>  array('Site Address',implode("\n",$elems['siteaddress'])), 
            1   =>  array('Site Description',$elems['survey']->sitedescription), 
            2   =>  array('Survey Type',$elems['survey']->surveytype), 
            3   =>  array('Date of Survey',$elems['survey']->surveydate), 
            4   =>  array('Scope of Survey',str_replace("{b}",chr(149),$elems['survey']->scope)), 
            5   =>  array('Agreed Excluded Areas',str_replace("{b}",chr(149),$elems['survey']->agreed_excluded_areas)), 
            6   =>  array('Deviations from Standard Methods',$elems['survey']->deviations_from_standard_methods),           
        );
        
        $recth = 8;
        $cellh = 6;
        
        $cellw_one = 33.75;
        $cellw_two = 147.50;
        
        $nbs = array(
            0   =>  array($recth,$cellh,1),
            1   =>  array($recth,$cellh,1),
            2   =>  array($recth,$cellh,1),
            3   =>  array($recth,$cellh,1),
            4   =>  array($recth,$cellh,1),
            5   =>  array($recth,$cellh,1),
            6   =>  array($recth,$cellh,1),
        );
        
        $totlines = 0;
        $toth = 0;
        
        foreach ($fields as $i => $field) {
            // Calculate the height of the row
            $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
            $nb_two = max(0,$this->nbLines($cellw_two, $field[1]));
            $nb = max($nb_one,$nb_two);
            
            $totlines += $nb;

            $recth = 2 + ($cellh * $nb);
            $toth += $recth;
            
            $nbs[$i] = array($recth,$cellh,$nb);
        }
        
        $totlines += count($fields) - 1;
        
        $done = false;
        
        while ($y <= $low_limit) {            
            if (!$done) {
                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);
                $this->Rect($start_x, $y, 0.25 + $width, 6, 'FD');
                
                $y += 4;
        
                $this->SetXY(20, $y);
                $this->SetFont($stds['font-family'],'B',$stds['font-size']);
                $this->Text(20, $y, 'Survey Scope and Methodology');

                $y += 2;
                
                $done = true;
            }
            
            foreach ($fields as $i => $field) {
                $h = $nbs[$i][1] * $nbs[$i][2];
                
                $string_to_print = $field[1];
                
                $currecth = 0;

                while (!empty($string_to_print)) {
                    // tot lines from the current position to the end of the page
                    $remaining = ($low_limit - $y - 5) / $cellh;
                    
                    if ((($i == 5) or ($i == 6)) and ($remaining < 2)) {
                        $this->AddPage();
                        $tplIdx = $this->importPage(2);
                        $this->useTemplate($tplIdx);
                        
                        $y = 27;
                        
                        $remaining = ($low_limit - $y - 5) / $cellh;
                    }
                    
                    $maxline = min($remaining,$nbs[$i][2]);

                    $this->SetFillColor(255,255,255);
                    $this->SetDrawColor(0,0,0);
                    
                    $currecth = 1 + min($nbs[$i][0],$low_limit - $y - 5);
                    
                    $this->SetFont($stds['font-family'],'B',$stds['font-size']);
                    $this->Rect($start_x, $y, 2 + $cellw_one, $currecth + 0.1, 'FD');
                    $this->SetXY(1 + $start_x, $y + 1);
                    $this->MultiCell($cellw_one, $nbs[$i][1], $field[0], 0, 'L', true, $maxline);                        

                    $this->SetFont($stds['font-family'],'',$stds['font-size']);
                    $this->Rect($start_x + 2 + $cellw_one, $y, 2 + $cellw_two, $currecth + 0.1, 'FD');
                    $this->SetXY($start_x + 3 + $cellw_one, $y + 1);
                    $string_to_print = $this->MultiCell($cellw_two, $nbs[$i][1], $string_to_print, 0, 'L', true, $maxline);
                    
                    $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
                    $nb_two = max(0,$this->nbLines($cellw_two, $string_to_print));
                    $nb = max($nb_one,$nb_two);

                    $nbs[$i][0] = 2 + ($cellh * $nb);
                    $nbs[$i][2] = $nb;
                    
                    if (!empty($string_to_print)) {
                        $this->AddPage();
                        $tplIdx = $this->importPage(2);
                        $this->useTemplate($tplIdx);
                        
                        $y = 27;
                    }
                }
                
                $y += $currecth;
            }
            
            $y = $low_limit + 1;
        }
        
        return $y;
    }
    
    public function printAsbestosContainingMaterialsTable()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $asbestos = $elems['asbestos'];
        
        $tot = count($asbestos);
        
        // There are not elements with asbestos
        if ($tot == 0) {
            $this->AddPage('L');
            $this->TOC_Entry('Executive Summary - Asbestos Containing Materials', 0);
            $survtype = strtolower($this->survey->surveytype);
            $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
            $this->setSourceFile($template);
            $tplIdx = $this->importPage(1);
            $this->useTemplate($tplIdx);
            
            $x = 15.5;
            $y = 32.2;
            
            $this->SetTextColor(255,255,255);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);

            $this->Text($x, $y, 'Executive Summary - Asbestos Containing Materials');

            $y += 10;
    
            $this->SetTextColor(0,0,0);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Text($x, $y, 'No asbestos containing materials identified during this survey.');
            
            return;
        }
        
        // There are elements with asbestos
        
        // Calculate the number of rows for the headers
        $nb_headers = 0;
        $nb = array();
        
        $headers_colsw = array(
            50.4,
            18.0,
            19.9,
            107.2,
            26.8,
            26.8,
            26.8,
            26.8,
            15.0,
            15.0,
            17.0,
            29.0,
        );
        
        $values_colsw = array(
            50.4,
            18.0,
            19.9,
            26.8,
            26.8,
            26.8,
            26.8,
            15.0,
            15.0,
            17.0,
            29.0,
        );

        $cellh = 5.2;
        $rect_h = 5.6;
        $totlines = array();
	$htotlines = 0;
        
        $headers = array(
            "\nRoom/Area Location\nand Description",
            "\nInspection \nSample No.",
            "\nAccessibility",
            "MATERIAL ASSESSMENT",
            "Asbestos Type",
            "Product",
            "Extent\nof Damage",
            "Surface\nTreatment",
            "\nHazard Score",
            "\nHazard Rating",
            "\nQuantity",
            "\nRecommendations",
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($headers as $i => $header) {
            if (($i >= 3) and ($i <= 7)) {
                continue;
            }

            $nb_headers = max($nb_headers,$this->nbLines($headers_colsw[$i], $header));
        }
        
        $htotlines += $nb_headers;

        $nb_second_line = 0;

        foreach ($headers as $i => $header) {
            if (($i < 4) or ($i > 7)) {
                continue;
            }

            $nb_second_line = max($nb_second_line,$this->nbLines($headers_colsw[$i], $header));
        }

        $nb_headers += $nb_second_line;

        $htotlines += $nb_second_line; 
        
        // Calculate the scores
        $cells = array();
        $scores = array();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($this->buildings as $n => $building) {
            if (empty($asbestos[$building])) {
                continue;
            }
            
            $asbuilding = $asbestos[$building];
            
            $cells = array();
            $scores = array();
            
            foreach ($asbuilding as $floor_id => $asbs) {
                $totfloor = count($asbs);

                if (!isset($cells[$floor_id])) {
                    $cells[$floor_id] = array();
                    $scores[$floor_id] = array();
                }

                $totlines[$floor_id] = $htotlines;
                $i = 0;

                foreach ($asbs as $i => $curasb) {
                    $scores[$floor_id][$i] = array(
                        'prodtype'      =>  $curasb->product_score,
                        'extent'        =>  $curasb->extent_score,
                        'surftreatment' =>  $curasb->surface_treatment_score,
                        'asbtype'       =>  0,
                    );

                    if ('Chrysotile' == $curasb->asbestostype) {
                        $scores[$floor_id][$i]['asbtype'] = 1;
                    } else if ('Crocidolite' == $curasb->asbestostype) {
                        $scores[$floor_id][$i]['asbtype'] = 3;
                    } else {
                        $scores[$floor_id][$i]['asbtype'] = 2;
                    }

                    $scores[$floor_id][$i]['tot'] = $scores[$floor_id][$i]['prodtype'] + $scores[$floor_id][$i]['extent'] + $scores[$floor_id][$i]['surftreatment'] + $scores[$floor_id][$i]['asbtype'];
                    $scores[$floor_id][$i]['rating'] = '';

                    if ($scores[$floor_id][$i]['tot'] <= 4) {
                        $scores[$floor_id][$i]['rating'] = 'Very low';
                    } else if (in_array($scores[$floor_id][$i]['tot'],array(5,6))) {
                        $scores[$floor_id][$i]['rating'] = 'Low';
                    } else if (in_array($scores[$floor_id][$i]['tot'],array(7,8,9))) {
                        $scores[$floor_id][$i]['rating'] = 'Medium';
                    } else {
                        $scores[$floor_id][$i]['rating'] = 'High';
                    }

                    $first_column = $curasb->floor . '.' . $curasb->room;
                    
                    if (strpos($curasb->floor, 'RS') !== false) {
                        $first_column = str_replace('RS','RS.',$curasb->floor);
                    }
                    
                    if (!empty($curasb->room_name)) {
                        $first_column .= "\n" . $curasb->room_name;
                    }
                    
                    $mat = trim($curasb->material_location);
                    if (!empty($mat)) {
                        $first_column .= "\n" . $curasb->material_location;
                    }
                    
                    $comm = trim($curasb->comments);
                    if (!empty($comm)) {
                        $first_column .= "\n" . $curasb->comments;
                    }     
                    
                    if (!empty($curasb->referral)) {
                        $first_column .= "\n" . 'Refer to sample ' . $curasb->referral . ' for analysis.';
                    }

                    $last_column = '';
                    if (!empty($curasb->recommendations)) {
                        $last_column = $curasb->recommendations;
                    }

                    if (!empty($curasb->recommendations) and !empty($curasb->recommendationsNotes)) {
                        $last_column .= "\n";
                    }

                    if (!empty($curasb->recommendationsNotes)) {
                        $last_column .= $curasb->recommendationsNotes;
                    }

                    $cells[$floor_id][] = array(
                        $first_column,
                        $curasb->inspection_number . (!empty($curasb->referral) ? 'R' : (!empty($curasb->presumed) ? 'P' : '')),
                        (empty($curasb->accessibility) ? '-' : ucfirst($curasb->accessibility)),
                        (empty($curasb->asbestostype) ? '-' : $curasb->asbestostype),
                        (empty($curasb->product) ? '-' : $curasb->product),
                        (empty($curasb->extent) ? '-' : $curasb->extent),
                        (empty($curasb->surfdescription) ? '-' : $curasb->surfdescription),
                        $scores[$floor_id][$i]['tot'],
                        $scores[$floor_id][$i]['rating'],
                        (empty($curasb->quantity) ? '-' : $curasb->quantity),
                        $last_column,
                    );

                    $nb[$i] = 0;

                    foreach ($cells[$floor_id][$i] as $j => $cell) {
                        $cell_lines = $this->nbLines($values_colsw[$j], $cell);
                        $nb[$i] = max($nb[$i],$cell_lines);
                    }

                    $totlines[$floor_id] += $nb[$i];
                }

                $rectangles = array(
                    $cellh,
                );

                foreach ($cells[$floor_id] as $j => $line) {
                    $rectangles[] = ($rect_h - 2) * $nb[$j];
                }

                $tables_to_build = array();

                $hsum = 0;

                for ($l = 0; $l < count($rectangles); $l++) {
                    if ($hsum + $rectangles[$l] > 142) {
                        $tables_to_build[] = $hsum;
                        $hsum = 0;
                    }

                    $hsum += $rectangles[$l];

                    if ($l == count($rectangles) - 1) {
                        $tables_to_build[] = $hsum;
                    }
                }

                $npag = 0;
                $nfloor = 0;

                while ($nfloor < $totfloor) {
                    $this->AddPage('L');
                    if ($nfloor == 0) {
                        if (1 == count($asbestos)) {
                            $this->TOC_Entry('Executive Summary - Asbestos Containing Materials - ' . $this->floors[$floor_id], 0);
                        } else {
                            $this->TOC_Entry('Executive Summary - Asbestos Containing Materials - ' . $building . ' - ' . $this->floors[$floor_id], 0);
                        }
                    }
                    
                    if ($npag == 0) {
                        $survtype = strtolower($this->survey->surveytype);
                        $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
                        $this->setSourceFile($template);
                        $tplIdx = $this->importPage(1);
                        $this->useTemplate($tplIdx);
                    } else {
                        $survtype = strtolower($this->survey->surveytype);
                        $template = public_path() . '/templates/emptypage_' . str_replace(" ","_",$survtype) . '.pdf';
                        $this->setSourceFile($template);
                        $tplIdx = $this->importPage(1);
                        $this->useTemplate($tplIdx);
                    }

                    $y = 22.2;

                    if ($npag == 0) {
                        $x = 15.5;
                        $y = 32.2;

                        $this->SetTextColor(255,255,255);
                        $this->SetFont($stds['font-family'],'B',$stds['font-size']);

                        if (1 == count($asbestos)) {
                            $this->Text($x, $y, 'Executive Summary - Asbestos Containing Materials - ' . $this->floors[$floor_id]);
                        } else {
                            $this->Text($x, $y, 'Executive Summary - Asbestos Containing Materials - ' . $building . ' - ' . $this->floors[$floor_id]);
                        }
                    }

                    $x = 12.5;
                    $y += 5;

                    $this->SetFillColor(217,217,217);
                    $this->SetDrawColor(0,0,0);

                    $npag++;

                    // Print the headers of the table
                    $this->SetTextColor(0,0,0);
                    $this->SetFont($stds['font-family'],'B',$stds['font-table']);

                    for ($k = 0; $k < count($headers); $k++) {
                        if ($k == 0) {
                            $rect_cellh = 2.5 * $rect_h;
                            $this->Rect($x, $y, $headers_colsw[0], $rect_cellh, 'FD');
                            $this->SetXY($x + 1, $y + 1);                    
                        } else {
                            $rect_cellh = 2.5 * $rect_h;

                            if ($k == 3) {
                                $rect_cellh = $rect_h;
                            } else if (($k >= 4) and ($k <= 7)) {
                                $rect_cellh = 1.5 * $rect_h;
                            }

                            $excluded = -1;
                            if ($k >= 4) {
                                $excluded = 3;
                            }

                            $this->Rect($x + $this->sumUntil($headers_colsw,$k,$excluded), $y + ((($k >= 4) and ($k <= 7)) ? $rect_h : 0), $headers_colsw[$k], $rect_cellh, 'FD');
                            $this->SetXY($x + $this->sumUntil($headers_colsw,$k,$excluded) + 1, $y + ((($k >= 4) and ($k <= 7)) ? $rect_h : 0) + 1);
                        }

                        $this->MultiCell($headers_colsw[$k] - 2, $cellh - 2, $headers[$k], 0, (($k == 0) ? 'L' : 'C'), false);
                    }

                    $this->SetFont($stds['font-family'],'B',$stds['font-table']);
                    $this->SetFillColor(255,255,255); 

                    $rect_cellh = 2.5 * $rect_h;
                    $y += $rect_cellh;

                    foreach ($cells[$floor_id] as $j => $line) {
                        if ($j < $nfloor) {
                            continue;
                        }

                        $rect_cellh = ($rect_h - 1.20) * $nb[$j];

                        if ($y > 183 - $rect_cellh) {
                            break;
                        }

                        for ($k = 0; $k < count($line); $k++) {
                            if ($k == 0) {
                                $this->Rect($x, $y, $values_colsw[0], $rect_cellh, 'FD');
                                $this->SetXY($x + 1, $y + 1);
                            } else {
                                $this->Rect($x + $this->sumUntil($values_colsw,$k), $y, $values_colsw[$k], $rect_cellh, 'FD');
                                $this->SetXY($x + $this->sumUntil($values_colsw,$k) + 1, $y + 1);
                            }

                            $this->MultiCell($values_colsw[$k] - 2, $cellh - 2, $line[$k], 0, (((($k >= 1) and ($k <= 10)) or ('-' == trim($line[$k])) or ('N/A' == trim($line[$k])) or ($k == count($line) - 1)) ? 'C' : 'L'), false);
                        }

                        $y += $rect_cellh;
                        $nfloor++;
                    }
                }
            }
        }
    }

    public function printLimitedAccessTable()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $inaccessibles = $elems['inaccessible'];
        $tot = count($inaccessibles);
        
        // There are not inaccessibles
        if ($tot == 0) {
            $this->AddPage('L');
            $this->TOC_Entry('Executive Summary - Limited or No Access Areas', 0);
            $survtype = strtolower($this->survey->surveytype);
            $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
            $this->setSourceFile($template);
            $tplIdx = $this->importPage(1);
            $this->useTemplate($tplIdx);
            
            $x = 15.5;
            $y = 32.2;
            
            $this->SetTextColor(255,255,255);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);

            $this->Text($x, $y, 'Executive Summary - Limited or No Access Areas');

            $y += 10;
    
            $this->SetTextColor(0,0,0);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Text($x, $y, 'There were no further areas of limited or no access during this survey in additional to those highlighted within the scope of works.');
            
            return;
        }
        
        // There are inaccessibles
        
        $n = 0;

        $nb_headers = 0;
        $nb = array();
            
        $cellw = array(
            85.0,
            93.50,
            93.50,
        );
        
        $cellh = 5.2;
        $rect_h = 5.6;
        $totlines = 0;
            
        $headers = array(
            'Room/Area Location and Description',
            'Reason for Limited or No Access',
            'Recommendations',
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($headers as $i => $header) {
            $nb_headers = max($nb_headers,$this->nbLines($cellw[$i], $header));
        }
            
        $totlines += $nb_headers;

        $cells = array();
                    
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        foreach ($inaccessibles as $i => $inaccessible) {
            $first_column = $inaccessible->building . ' ' . $inaccessible->floor . '.' . $inaccessible->room;
            
            if (strpos($inaccessible->floor, 'RS') !== false) {
                $first_column = $inaccessible->building . ' ' . str_replace('RS','RS.',$inaccessible->floor);
            }
            
            if (!empty($inaccessible->room_name)) {
                $first_column .= " " . $inaccessible->room_name;
            }     
            
            $first_column = preg_replace('/\s+/', ' ',$first_column);
            
            if (!empty($inaccessible->material_location)) {
                $first_column .= "\n" . preg_replace('/\s+/', ' ',$inaccessible->material_location);
            }
            
            $last_column = '';
            if (!empty($inaccessible->recommendations)) {
                $last_column = preg_replace('/\s+/', ' ',$inaccessible->recommendations);
            }
            
            if (!empty($inaccessible->recommendations) and !empty($inaccessible->recommendationsNotes)) {
                $last_column .= "\n";
            }
            
            if (!empty($inaccessible->recommendationsNotes)) {
                $last_column .= preg_replace('/\s+/', ' ',$inaccessible->recommendationsNotes);
            }
            
            $second_column = preg_replace('/\s+/', ' ',$inaccessible->comments);
            
            $cells[] = array(
                $first_column,
                $second_column,
                $last_column,
            );
                
            $nb[$i] = 0;
            
            foreach ($cells[$i] as $j => $cell) {
                $nb[$i] = max($nb[$i],$this->nbLines($cellw[$j], $cell));
            }

            $totlines += $nb[$i];
        }

	$rectangles = array(
            ($rect_h - 4) * $cellh,
        );

        foreach ($cells as $j => $line) {
            $rectangles[] = ($rect_h - 4) * $nb[$j];
        }

        $tables_to_build = array();

        $hsum = 0;

        for ($l = 0; $l < count($rectangles); $l++) {
            if ($hsum + $rectangles[$l] > 142) {
                $tables_to_build[] = $hsum;
                $hsum = 0;
            }

            $hsum += $rectangles[$l];

            if ($l == count($rectangles) - 1) {
                $tables_to_build[] = $hsum;
            }
        }
        
        $npag = 0;
        
        while ($n < $tot) {
            $this->AddPage('L');
            if ($n == 0) {
                $this->TOC_Entry('Executive Summary - Limited or No Access Areas', 0);
            }
            if ($npag == 0) {
                $survtype = strtolower($this->survey->surveytype);
                $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
                $this->setSourceFile($template);
                $tplIdx = $this->importPage(1);
                $this->useTemplate($tplIdx);
            } else {
                $survtype = strtolower($this->survey->surveytype);
                $template = public_path() . '/templates/emptypage_' . str_replace(" ","_",$survtype) . '.pdf';
                $this->setSourceFile($template);
                $tplIdx = $this->importPage(1);
                $this->useTemplate($tplIdx);
            }
        
            $y = 22.2;

            if ($npag == 0) {
                $x = 15.5;
                $y = 32.2;

                $this->SetTextColor(255,255,255);
                $this->SetFont($stds['font-family'],'B',$stds['font-size']);

                $this->Text($x, $y, 'Executive Summary - Limited or No Access Areas');
            }
            
            $x = 12.5;
            $y += 5;
            
            $this->SetFillColor(217,217,217);
            $this->SetDrawColor(0,0,0);
            $npag++;
            
            $this->SetTextColor(0,0,0);
            $this->SetFont($stds['font-family'],'B',$stds['font-table']);

            for ($k = 0; $k < count($headers); $k++) {
                if ($k == 0) {
                    $this->Rect($x, $y, $cellw[0], $cellh, 'FD');
                    $this->SetXY($x + 1, $y + 1);                    
                } else {
                    $this->Rect($x + $this->sumUntil($cellw,$k), $y, $cellw[$k], $cellh, 'FD');
                    $this->SetXY($x + $this->sumUntil($cellw,$k) + 1, $y + 1);
                }

                $this->MultiCell($cellw[$k] - 2, $cellh - 2, $headers[$k], 0, 'L', false);
            }
            
            $this->SetFont($stds['font-family'],'B',$stds['font-table']);
	    $this->SetFillColor(255,255,255); 

            $y += $cellh;
            
            foreach ($cells as $j => $line) {
                if ($j < $n) {
                    continue;
                }
                
                $rect_cellh = $cellh * $nb[$j];

                for ($k = 0; $k < count($line); $k++) {
                    if ($k == 0) {
                        $this->Rect($x, $y, $cellw[0], $rect_cellh, 'FD');
                        $this->SetXY($x + 1, $y + 1);                    
                    } else {
                        $this->Rect($x + $this->sumUntil($cellw,$k), $y, $cellw[$k], $rect_cellh, 'FD');
                        $this->SetXY($x + $this->sumUntil($cellw,$k) + 1, $y + 1);
                    }

                    $this->MultiCell($cellw[$k] - 2, $cellh - 2, $line[$k], 0, 'L', false);
                }

                $y += $rect_cellh;
                $n++;
                
                if ($y > 183 - $rect_cellh) {
                    break;
                }
            }
        }
    }
    
    public function printObservationsTable()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $observations = $elems['observations'];
        $tot = count($observations);
        
        // There are not observations
        if ($tot == 0) {
            $this->AddPage('L');
            $this->TOC_Entry('Observations', 0);
            $survtype = strtolower($this->survey->surveytype);
            $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
            $this->setSourceFile($template);
            $tplIdx = $this->importPage(1);
            $this->useTemplate($tplIdx);
            
            $x = 15.5;
            $y = 32.2;
            
            $this->SetTextColor(255,255,255);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);

            $this->Text($x, $y, 'Observations');

            $y += 10;
    
            $this->SetTextColor(0,0,0);
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->Text($x, $y, 'There were no further observations.');
            
            return;
        }
        
        // There are observations
        
        // Calculate the number of rows for the headers
        $nb_headers = 0;
        $nb = array();

        $colsw = array(
            21.0,
            41.0,
            18.0,
            19.9,
            24.0,
            24.0,
            15.0,
            16.0,
            23.0,
            33.0,
            36.6,
        );

        $cellh = 5.2;
        $rect_h = 5.6;
        $totlines = array();

        $headers = array(
            "Room\nArea",
            "Observation\nComment",
            "Inspection\nSample No.",
            "Accessibility",
            "Asbestos\nType",
            "Product",
            "Quantity",
            "Extent of\nDamage",
            "Surface\nTreatment",
            "Recommendation",
            "Photo",
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($headers as $i => $header) {
            $nb_headers = max($nb_headers,$this->nbLines($colsw[$i], $header));
        }

        $cells = array();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        foreach ($this->buildings as $n => $building) {
            $obsbuilding = $observations[$building];
            
            $cells = array();
            
            foreach ($obsbuilding as $floor_id => $obs) {
                if (!isset($cells[$floor_id])) {
                    $cells[$floor_id] = array();
                }

                $totlines[$floor_id] = $nb_headers;           
                $i = 0;

                foreach ($obs as $curobs) {
                    $first_column = $curobs->floor . '.' . $curobs->room;
                    
                    if (strpos($curobs->floor, 'RS') !== false) {
                        $first_column = str_replace('RS','RS.',$curobs->floor);
                    }

                    if (!empty($curobs->room_name)) {
                        $first_column .= "\n" . $curobs->room_name;
                    }

                    $second_column = '';

                    if (!empty($curobs->material_location)) {
                        $second_column = preg_replace('/\s+/', ' ',trim($curobs->material_location));
                    }

                    if (!empty($second_column) and !empty($curobs->comments)) {
                        $second_column .= "\n";
                    }

                    if (!empty($curobs->comments)) {
                        $second_column .= preg_replace('/\s+/', ' ',trim($curobs->comments));
                    }

                    if (!empty($curobs->referral)) {
                        $second_column .= "\n" . 'Refer to sample ' . $curobs->referral . ' for analysis';
                    }

                    $last_column = '';
                    if (!empty($curobs->recommendations)) {
                        $last_column = preg_replace('/\s+/', ' ',trim($curobs->recommendations));
                    }

                    if (!empty($curobs->recommendations) and !empty($curobs->recommendationsNotes)) {
                        $last_column .= "\n";
                    }

                    if (!empty($curobs->recommendationsNotes)) {
                        $last_column .= preg_replace('/\s+/', ' ',trim($curobs->recommendationsNotes));
                    }

                    $record = array(
                        $first_column,
                        $second_column,
                        $curobs->inspection_number . (!empty($curobs->referral) ? 'R' : (!empty($curobs->presumed) ? 'P' : '')),
                        (empty($curobs->accessibility) ? '-' : ucfirst($curobs->accessibility)),
                        (empty($curobs->asbestostype) ? '-' : $curobs->asbestostype),
                        (empty($curobs->product) ? '-' : $curobs->product),
                        (empty($curobs->quantity) ? '-' : $curobs->quantity),
                        (empty($curobs->extent) ? '-' : $curobs->extent),
                        (empty($curobs->surfdescription) ? '-' : $curobs->surfdescription),
                        $last_column,
                        $curobs->photo,
                    );

                    $cells[$floor_id][$i] = $record;

                    $nb[$floor_id][$i] = 0;

                    foreach ($record as $j => $cell) {
                        if ($j == 10) {
                            continue;
                        }

                        $cell_lines = $this->nbLines($colsw[$j], $cell);

			if (8 <= $cell_lines) {
			    
                            $cell_lines = $cell_lines + ceil($cell_lines / 4);
                        }

			$nb[$floor_id][$i] = max($nb[$floor_id][$i],$cell_lines);
                    }

                    $totlines[$floor_id] += $nb[$floor_id][$i];

                    $i++;
                }
            }

            $imgw = 35;
            $imgh = 26.25;
        
            foreach ($obsbuilding as $obs_floor => $obs) {
                $nfloor = 0;
                $totfloor = count($obs);

                $rectangles = array(
                    $cellh * $nb_headers,
                );

                foreach ($cells[$obs_floor] as $j => $line) {
                    $rect_cellh = ($rect_h - 2) * $nb[$obs_floor][$j];
                    $rect_cellh = max($imgh + 2, $rect_cellh);

                    $rectangles[] = $rect_cellh;
                }

                $tables_to_build = array();

                $hsum = 0;

                for ($l = 0; $l < count($rectangles); $l++) {
                    if ($hsum + $rectangles[$l] > 142) {
                        $tables_to_build[] = $hsum;
                        $hsum = 0;
                    }

                    $hsum += $rectangles[$l];

                    if ($l == count($rectangles) - 1) {
                        $tables_to_build[] = $hsum;
                    }
                }

                $npag = 0;

                while ($nfloor < $totfloor) {
                    $this->AddPage('L');
                    if ($nfloor == 0) {
                        if (1 == count($observations)) {
                            $this->TOC_Entry('Observations - ' . $this->floors[$obs_floor], 0);
                        } else {
                            $this->TOC_Entry('Observations - ' . $building . ' - ' . $this->floors[$obs_floor], 0);
                        }
                    }
                    if ($npag == 0) {
                        $survtype = strtolower($this->survey->surveytype);
                        $template = public_path() . '/templates/red_' . str_replace(" ","_",$survtype) . '.pdf';
                        $this->setSourceFile($template);
                        $tplIdx = $this->importPage(1);
                        $this->useTemplate($tplIdx);
                    } else {
                        $survtype = strtolower($this->survey->surveytype);
                        $template = public_path() . '/templates/emptypage_' . str_replace(" ","_",$survtype) . '.pdf';
                        $this->setSourceFile($template);
                        $tplIdx = $this->importPage(1);
                        $this->useTemplate($tplIdx);
                    }

                    $y = 22.2;

                    if ($npag == 0) {
                        $x = 15.5;
                        $y = 32.2;

                        $this->SetTextColor(255,255,255);
                        $this->SetFont($stds['font-family'],'B',$stds['font-size']);

                        if (1 == count($observations)) {
                            $this->Text($x, $y, 'Observations - ' . $this->floors[$obs_floor]);
                        } else {
                            $this->Text($x, $y, 'Observations - ' . $building . ' - ' . $this->floors[$obs_floor]);
                        }
                    }

                    $x = 12.5;
                    $y += 5;

                    $this->SetFillColor(217,217,217);
                    $this->SetDrawColor(0,0,0);
                    $npag++;

                    $this->SetTextColor(0,0,0);
                    $this->SetFont($stds['font-family'],'B',$stds['font-table']);

                    $rect_headerh = $rect_h * 0.75 * $nb_headers;

                    for ($k = 0; $k < count($headers); $k++) {
                        if ($k == 0) {
                            $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                            $this->SetXY($x + 1, $y + 1);                    
                        } else {
                            $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                            $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                        }

                        $this->MultiCell($colsw[$k] - 1, $cellh - 2, $headers[$k], 0, 'C', false);
                    }

                    $this->SetFont($stds['font-family'],'B',$stds['font-table']);
                    $this->SetFillColor(255,255,255); 

                    $y += $rect_headerh;

                    foreach ($cells[$obs_floor] as $j => $line) {
                        if ($j < $nfloor) {
                            continue;
                        }

                        $rect_cellh = $cellh * 0.65 * $nb[$obs_floor][$j];
                        $rect_cellh = max($imgh + 2, $rect_cellh);

                        if ($y > 183 - $rect_cellh) {
                            break;
                        }

                        for ($k = 0; $k < count($line); $k++) {
                            if ($k == 0) {
                                $this->Rect($x, $y, $colsw[0], $rect_cellh, 'FD');
                                $this->SetXY($x + 1, $y + 1);                    
                            } else {
                                $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_cellh, 'FD');

                                if ($k == 10) {
                                    $picture = '';
                                    
                                    $start_picture = public_path() . '/tablet' . $line[$k];
                                    if (!empty($line[$k]) and file_exists($start_picture)) {
                                        $picture = '/tmp/' . substr($line[$k], strrpos($line[$k], '/') + 1);
                                        $dst = $this->adaptPhoto($start_picture, 300, 225);
                                        imagejpeg($dst, $picture);
                                        
                                        $this->Image($picture, $x + $this->sumUntil($colsw,$k) + 0.5, $y + 1, $imgw, $imgh, 'jpg', '', 'C', true);
                                    }

                                    continue;
                                }

                                $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                            }

                            $this->MultiCell($colsw[$k] - 2, $cellh - 2, $line[$k], 0, ((($k == 0) or ((($k >= 2) and ($k <= 8)) or ('-' == trim($line[$k])) or ('N/A' == trim($line[$k])) or ($k == 9)))  ? 'C' : 'L'), false);
                        }

                        $y += $rect_cellh;
                        $nfloor++;
                    }
                }       
            }
        }
    }
    
    public function printCertificateOfAnalysis()
    {
        $elems = $this->getElements();
        
        $cert = '';
        
        if ($elems['certificate'] !== null) {
            $cert = public_path() . '/files/' . $elems['certificate']->path;
        }
        
        if (!file_exists($cert)) {
            return;
        }

	$this->setPrintHeader(true);
	$this->AddPage();
        $this->TOC_Entry('Certificate of Analysis', 0);
        $tplIdx = $this->importPage(10);
        $this->useTemplate($tplIdx);
	$this->setPrintFooter(true); 

        $this->SetTextColor(0,0,0);
        
        $pageCount = $this->setSourceFile($cert);
	
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $this->setPrintHeader(false);
            
            // import a page
            $templateId = $this->importPage($pageNo);
            // get the size of the imported page
            $size = $this->getTemplateSize($templateId);

            // create a page (landscape or portrait depending on the imported page size)
            if ($size['w'] > $size['h']) {
                $this->AddPage('L', array($size['w'], $size['h']));
            } else {
                $this->AddPage('P', array($size['w'], $size['h']));
            }

            // use the imported page
            $this->useTemplate($templateId);
            
            $this->setPrintFooter(false);
        }	
    }
    
    public function printFloorPlans()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $cert = '';
        
        if ($elems['floor_plans'] !== null) {
            $cert = public_path() . '/files/' . $elems['floor_plans']->path;
        }
        
        if (!file_exists($cert)) {
            return;
        }

	$this->setPrintHeader(true);
	$this->AddPage();
        $this->TOC_Entry('Floor Plans', 0);
        $tplIdx = $this->importPage(11);
        $this->useTemplate($tplIdx);
	$this->setPrintFooter(true);

        $this->SetTextColor(0,0,0);

        $pageCount = $this->setSourceFile($cert);
	
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $this->setPrintHeader(false);
            
            // import a page
            $templateId = $this->importPage($pageNo);
            // get the size of the imported page
            $size = $this->getTemplateSize($templateId);

            // create a page (landscape or portrait depending on the imported page size)
            if ($size['w'] > $size['h']) {
                $this->AddPage('L', array($size['w'], $size['h']));
            } else {
                $this->AddPage('P', array($size['w'], $size['h']));
            }

            // use the imported page
            $this->useTemplate($templateId);
            
            $this->setPrintFooter(false);
        }	
    }
    
    public function printAirMonitoring()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $cert = '';
        
        if ($elems['airmonitoring'] !== null) {
            $cert = public_path() . '/files/' . $elems['airmonitoring']->path;
        }
        
        if (!file_exists($cert)) {
            return;
        }

	$this->setPrintHeader(true);
	$this->AddPage();
        $this->TOC_Entry('Air Monitoring Certificates', 0);
        $tplIdx = $this->importPage(12);
        $this->useTemplate($tplIdx);
	$this->setPrintFooter(true); 

        $this->SetTextColor(0,0,0);

        $pageCount = $this->setSourceFile($cert);
	
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $this->setPrintHeader(false);
            
            // import a page
            $templateId = $this->importPage($pageNo);
            // get the size of the imported page
            $size = $this->getTemplateSize($templateId);

            // create a page (landscape or portrait depending on the imported page size)
            if ($size['w'] > $size['h']) {
                $this->AddPage('L', array($size['w'], $size['h']));
            } else {
                $this->AddPage('P', array($size['w'], $size['h']));
            }

            // use the imported page
            $this->useTemplate($templateId);
            
            $this->setPrintFooter(false);
        }	
    }
}

class PdfController extends Controller
{
    public function index()
    {
        
    }
    
    public function printReport(Request $request)
    {
        $job_number = $request->input('job_number');
        
        $pdf = new ReportPDF($job_number);
        
        $elems = $pdf->getElements();
        
        $survtype = strtolower($elems['survey']->surveytype);
        
        if (!empty($elems['survey']->reinspectionof)) {
            $survtype = 'reinspection';
        }
        
        $template = public_path() . '/templates/' . str_replace(" ","_",$survtype) . '.pdf';
        
        $pdf->AliasNbPages();

	$pdf->setPrintHeader(true);
        $pdf->AddPage();
        $pdf->setSourceFile($template);

        // Cover Page
	$pdf->startPageNums();
        
        $y = $pdf->printCoverPage();
	$pdf->setPrintFooter(false);
        
        $y = 45;
        
        // Report Authorizations        
        $y = $pdf->printReportAuthorisationTable($y);
        
        $y = $pdf->printIssuesTable($y);
	$pdf->setPrintFooter(true);
        
        $pdf->AddPage();
        $pdf->TOC_Entry('Survey Details', 0);
        $pdf->setSourceFile($template);
        $tplIdx = $pdf->importPage(4);
        $pdf->useTemplate($tplIdx);
        
        $y = 40;
        
        $y = $pdf->printSurveyDetailsTable($y);

        $pdf->AddPage();
        $tplIdx = $pdf->importPage(5);
        $pdf->useTemplate($tplIdx);
	
	$pdf->AddPage();
	$pdf->TOC_Entry('Navigating and Understanding the Survey Report', 0);
        $tplIdx = $pdf->importPage(6);
        $pdf->useTemplate($tplIdx);
        
        $pdf->printAsbestosContainingMaterialsTable();
        
        $pdf->printLimitedAccessTable();
        
        $pdf->printObservationsTable();
        
	$pdf->setSourceFile($template);
        $pdf->printCertificateOfAnalysis();

	$pdf->setSourceFile($template);
        $pdf->printFloorPlans();
        
        $end = 17;
        if ('resurvey' == $elems['survey']->surveytype) {
            $end = 16;
        }
        
        for ($n = 12; $n < 1 + $end; $n++) {
	    if (('resurvey' != $elems['survey']->surveytype) and ($n == 12)) {
		$pdf->setSourceFile($template);
                $pdf->printAirMonitoring();
                continue;
            }

	    $pdf->setPrintHeader(true);

	    if ($n == $end) {
		$pdf->setPrintHeader(false);
	    }

	    $pdf->AddPage();
	    $pdf->setSourceFile($template);

            if ($n == 13) {
                $pdf->TOC_Entry('Management Information and Assessment Scoring', 0);
            }
            
            if ($n == 16) {
                $pdf->TOC_Entry('Additional Information Available for Download', 0);
            }
            
            if ($n < $end) {
                $tplIdx = $pdf->importPage($n);
                $pdf->useTemplate($tplIdx);
            }
            
            if ($n == $end) {
		$picture = public_path() . '/img/lastpage.jpg';

                $imgw = 210;
                $imgh = 297;

                $pdf->Image($picture, 0, 0, $imgw, $imgh);
		$pdf->setPrintFooter(false);
            } else {
		$pdf->setPrintFooter(true);
	    }
        }

        $pdf->stopPageNums();

	$pdf->setPrintHeader(true);
        $pdf->AddPage();
        $tplIdx = $pdf->importPage(2);
        $pdf->useTemplate($tplIdx);
                
        $path_pdf = public_path() . '/reports/' . $elems['survey']->ukasnumber . '.pdf';
   
        $pdf->insertTOC(2);
	
	$pdf->setPrintFooter(false);
        
        $pdf->Output($path_pdf,'F');
        
        return $elems['survey']->ukasnumber . '.pdf';
    }
}
