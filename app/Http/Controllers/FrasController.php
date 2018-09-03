<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class FraPDF extends \fpdi\FPDI
{
    private function printTitleBar($title)
    {
        $stds = $this->defineStandardFont();
        
        $x = 32;
        $y = 21;
        
        $page_width = 210;
        
        if ('L' == $this->CurOrientation) {
            $page_width = 297;
        }
        
        $this->SetFillColor(204,53,46);
        $this->SetTextColor(255,255,255);
        $this->Rect($x, $y, ($page_width - (2 * $x)), 7, 'F');
        
        $x = 35;
        $y = 25.5;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->Text($x, $y, $title);
        $this->SetFillColor(255,255,255);        
        
        return $y;
    }
    
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
    
    private function fixChars($string)
    {
        $mystring = $string;
        
        $mystring = str_replace("ç","c",$mystring);
        $mystring = str_replace("‘","'",$mystring);
        $mystring = str_replace("’","'",$mystring);
        $mystring = str_replace("“","\"",$mystring);
        $mystring = str_replace("”","\"",$mystring);
        
        return $mystring;
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
    
    private function printTableSection($index,$section,$questions,$answers)
    {
        $lower_bound = 190;
        
        $x = 32;
        $y = 4 + $this->GetY();
        
        $stds = $this->defineStandardFont();
        
        $page_width = 210;
        
        if ('L' == $this->CurOrientation) {
            $page_width = 297;
        }
        
        // Calculate the number of rows for the headers
        $nb_headers = 0;
        $nb = array();

        $colsw = array(
            12.1,
            50.6,
            16.9,
            16.9,
            16.9,
            16.9,
            50.6,
            50.6,
        );

        $headers = array(
            "Item No.",
            "Inspection",
            "N/A",
            "Yes",
            "No",
            "Unknown",
            "Comment",
            "Recommendation",
        );
        
        $cellh = 5;
        $hfact = 1.0;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($headers as $i => $header) {
            $nb_headers = max($nb_headers,$this->nbLines($colsw[$i], $header));
        }
        
        $rect_headerh = ($hfact * $cellh * $nb_headers);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);

        $cells = array();
        $goals = array();
        
        $k = 0;
        foreach ($questions as $quest_id => $quest) {
            $answer = $answers[$quest_id];
            
            $record = array(
                ($index . '.' . (1 + $k)),
                $quest->question,
                (('N/A' == $answer->answer) ? '1' : '0'),
                (('Yes' == $answer->answer) ? '1' : '0'),
                (('No' == $answer->answer) ? '1' : '0'),
                (('Not Known' == $answer->answer) ? '1' : '0'),
                $answer->comments,
                $answer->recommendation,
                ((('N/A' != $answer->answer) and ($quest->goal != ucfirst($answer->answer)) and empty($answer->info)) ? 1 : 0),
            );
            
            $record[6] = $this->fixChars($record[6]);            
            $record[7] = $this->fixChars($record[7]);       
            
            if ($answer->priority_code > 0) {
                $this->remedials[$quest_id]->topic = ($index . '.' . (1 + $k)) . ' ' . $section->name;
            }
            
            $cells[$k] = $record;
            $goals[$k] = $quest->goal;

            $nb[$k] = 0;

            foreach ($record as $j => $cell) {
                if (!in_array($j,array(2,6,7))) {
                    continue;
                }

                $cell_lines = $this->nbLines($colsw[$j], $cell);
                $nb[$k] = max($nb[$k],$cell_lines);
            }
            
            $k++;
        }
        
        $first_line_rect = 0;
            
        foreach ($cells[0] as $i => $elem) {
            if ($i == 8) {
                continue;
            }
            
            $first_line_rect = max($first_line_rect,$this->nbLines($colsw[$i] - 2, $elem));
        }
        
        $rect_elem = ($hfact * ($cellh - 1) * $first_line_rect);
        
        if ($y >= $lower_bound - $rect_headerh - 2 - $cellh - $rect_elem) {
            $this->AddPage('L');
                
            $y = 21;
        }
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $this->Cell(($page_width - (2 * $x)),7,($index . '. ' . $section->name));
        
        $y += 7;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $align = 'L';
            if (in_array($k,array(0,2,3,4,5))) {
                $align = 'C';
            }
            
            if ($k == 0) {
                $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                $this->SetXY($x + 1, $y + 1);                    
            } else {
                $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
            }

            $this->MultiCell($colsw[$k] - 2, $cellh - 2, $headers[$k], 0, $align, false);
        }
        
        $this->SetFillColor(255,255,255);
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $y += $rect_headerh;
        
        foreach ($cells as $index => $record) {            
            $nb_record = 0;
            
            foreach ($record as $i => $elem) {
                if ($i == 8) {
                    continue;
                }
                
                $nb_record = max($nb_record,$this->nbLines($colsw[$i] - 2, $elem));
            }

            $rect_elem = ($hfact * ($cellh - 1) * $nb_record);
            
            if ($nb_record == 1) {
                $rect_elem = ($hfact * $cellh * $nb_record);
            }
            
            if ($rect_elem > $lower_bound - $y) {
                $this->AddPage('L');
                
                $y = 21;
                
                $this->SetFont($stds['font-family'],'B',$stds['font-table']);
                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);

                for ($k = 0; $k < count($headers); $k++) {
                    $align = 'L';
                    if (in_array($k,array(0,2,3,4,5))) {
                        $align = 'C';
                    }

                    if ($k == 0) {
                        $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                        $this->SetXY($x + 1, $y + 1);                    
                    } else {
                        $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                        $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                    }

                    $this->MultiCell($colsw[$k] - 2, $cellh - 2, $headers[$k], 0, $align, false);
                }

                $this->SetFillColor(255,255,255);
                $this->SetFont($stds['font-family'],'',$stds['font-table']);

                $y += $rect_headerh;
            }
            
            for ($k = 0; $k < count($record) - 1; $k++) {                
                $this->SetFont($stds['font-family'],'', $stds['font-table']);
                $this->SetTextColor(0,0,0);
                
                $align = 'L';
                if (in_array($k,array(0,2,3,4,5))) {
                    $align = 'C';
                }
                
                $this->SetFillColor(255,255,255);
                
                if ('0' == $record[$k]) {
                    $record[$k] = '';
                } else if ('1' == $record[$k]) {
                    $this->SetFont('ZapfDingbats','', $stds['font-table']);
                    
                    if (in_array($k,array(2,3,4,5))) {
                        $nrecs = count($record) - 1;
                        
                        switch ($record[$nrecs]) {
                            case 1:
                                $this->SetFillColor(255,0,0);
                                break;
                            default:
                                $this->SetFillColor(255,255,255);
                                break;
                        }
                    }
                    
                    $this->SetTextColor(0,0,0);
                    $record[$k] = '4';
                }

                if ($k == 0) {
                    $this->Rect($x, $y, $colsw[0], $rect_elem, 'FD');
                    $this->SetXY($x + 1, $y + 1);                    
                } else {
                    $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_elem, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                }
                
                $this->MultiCell($colsw[$k] - 2, $cellh - 2, $record[$k], 0, $align, false);
            }
            
            $y += $rect_elem;
        }
        
        $this->SetXY($x,$y);
            
        return $y;
    }
    
    private function rotatePicture($filename)
    {
        $image = new \Imagick($filename); 
        $image_orientation = $image->getImageOrientation();
        
        switch($image_orientation) {
            case \imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case \imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case \imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
            default:
                break;
        }

        // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); 
        
        $new_filename = str_replace('.jpeg','_rotated.jpeg',$filename);
        $new_filename = str_replace('.jpg','_rotated.jpg',$new_filename);
        
        $image->writeImage($new_filename); 
        
        return $new_filename;
    }
    
    private $shop_id;
    private $shop;
    private $client;
    private $revision;
    private $fra;
    private $sections;
    private $questions;
    private $answers;
    private $revisions;
    private $remedials;
    private $prepuser;
    private $revuser;
    private $additional;    
    
    protected $_toc = array();
    protected $_numbering = false;
    protected $_numberingFooter = false;
    protected $_numPageNum = 2;
    
    private $printHeader;
    private $printFooter;
    
    protected $USEFUL_WIDTH = 146;
    protected $PICTURE_WIDTH = 72;
    protected $PICTURE_HEIGHT = 54;
    
    public function __construct($shop_id, $revision)
    {
        parent::__construct();
        
        $this->shop_id = $shop_id;
        
        $this->shop = DB::table('rashops')
                ->where('id','=',$this->shop_id)
                ->get()
                ->first();
        
        $this->client = DB::table('clients')
                ->where('id','=',$this->shop->client_id)
                ->get()
                ->first();
        
        $revmax = DB::table('rareports')
                ->where('rashop_id','=',$this->shop_id)
                ->max('revision');
        
        $myrev = $revision;
        
        if ($myrev > $revmax) {
            $myrev = $revmax;
        }
        
        $previous = 'none';
        $old_issue_date = 'none';
        
        $this->revision = $myrev;
        
        $previous = $this->revision;
        $old_issue = null;
        $old_issue_date = "";
        
        if ($this->revision > 1) {
            while (($old_issue == null) and ($previous > 0)) {
                $previous--;

                $old_issue = DB::table('rareports')
                    ->select('issue_date')
                    ->where('rashop_id','=',$this->shop_id)
                    ->where('revision','=',$previous)
                    ->get()
                    ->first();
                
                if ($old_issue !== null) {
                    $old_issue_date = $old_issue->issue_date;
                }
            }
        }
        
        $this->fra = DB::table('rareports')
                ->where('rashop_id','=',$this->shop_id)
                ->where('revision','=',$myrev)
                ->get()
                ->first();
        
        $this->fra->construction_type = $this->fixChars($this->fra->construction_type);
        $this->fra->executive_summary = $this->fixChars($this->fra->executive_summary);
        $this->fra->use_of_building = $this->fixChars($this->fra->use_of_building);
        $this->fra->disabled_occupants = $this->fixChars($this->fra->disabled_occupants);

	if (empty($this->fra->issue_date)) {
            $this->fra->issue_date = strtotime($this->fra->created_at);
	}

        $this->fra->risk_from_fire = 1;
        
        if (($this->fra->hazard_from_fire == 1) and ($this->fra->life_safety == 1)) {
            $this->fra->risk_from_fire = 1;
        } else if (($this->fra->hazard_from_fire == 1) and ($this->fra->life_safety == 2)) {
            $this->fra->risk_from_fire = 2;
        } else if (($this->fra->hazard_from_fire == 1) and ($this->fra->life_safety == 3)) {
            $this->fra->risk_from_fire = 3;
        } else if (($this->fra->hazard_from_fire == 2) and ($this->fra->life_safety == 1)) {
            $this->fra->risk_from_fire = 2;
        } else if (($this->fra->hazard_from_fire == 2) and ($this->fra->life_safety == 2)) {
            $this->fra->risk_from_fire = 3;
        } else if (($this->fra->hazard_from_fire == 2) and ($this->fra->life_safety == 3)) {
            $this->fra->risk_from_fire = 4;
        } else if (($this->fra->hazard_from_fire == 3) and ($this->fra->life_safety == 1)) {
            $this->fra->risk_from_fire = 3;
        } else if (($this->fra->hazard_from_fire == 3) and ($this->fra->life_safety == 2)) {
            $this->fra->risk_from_fire = 4;
        } else if (($this->fra->hazard_from_fire == 3) and ($this->fra->life_safety == 3)) {
            $this->fra->risk_from_fire = 5;
        }
        
        $this->fra->old_issue_date = $old_issue_date;
        
        $this->sections = DB::table('rasections')
                ->select('id','name')
                ->orderBy('id','asc')
                ->get();
        
        $this->questions = array();
        $this->remedials = array();
        
        $fquestions = DB::table('raquestions')
                ->select('id','question','goal','rasection_id')
                ->orderBy('id','asc')
                ->get();
        
        foreach ($fquestions as $quest) {
            if (!isset($this->questions[$quest->rasection_id])) {
                $this->questions[$quest->rasection_id] = array();
            }
            
            $this->questions[$quest->rasection_id][$quest->id] = $quest;
        }
        
        $this->answers = array();
        
        $fanswers = DB::table('raanswers')
                ->where('rareport_id','=',$this->fra->id)
                ->orderBy('raquestion_id','asc')
                ->orderBy('updated_at','desc')
                ->get();

        foreach ($fanswers as $fanswer) {
            $this->answers[$fanswer->raquestion_id] = $fanswer;
            
            if ($fanswer->priority_code > 0) {
                $this->remedials[$fanswer->raquestion_id] = $fanswer;
                $this->remedials[$fanswer->raquestion_id]->topic = '';
            }
        }
        
        $this->revisions = DB::table('rareports')
                ->select('id','revision','comments','issue_date','created_at')
                ->where('rashop_id','=',$this->shop_id)
                ->orderBy('revision','asc')
                ->get();
        
        $revseluser = DB::table('users')
                ->select('id','name','surname','qualification')
                ->where('id','=',$this->fra->review_by)
                ->get()
                ->first();
        
        $assuser = DB::table('users')
                ->select('id','name','surname','qualification')
                ->where('id','=',$this->fra->assessor)
                ->get()
                ->first();
        
        $this->prepuser = array(
            $assuser->name . ' ' . $assuser->surname . "\n" . $assuser->qualification,
            public_path() . '/fra' . $this->fra->signature,
            date('d/m/Y',$this->fra->issue_date),
        );
        
        if ($revseluser !== null) {
            $this->revuser = $revseluser->name . ' ' . $revseluser->surname . "\n" . $revseluser->qualification;
        } else {
            $this->revuser = '';
        }
        
        if ($assuser !== null) {
            $this->fra->assessor = $assuser->name . ' ' . $assuser->surname . " - " . $assuser->qualification;
        } else {
            $this->fra->assessor = '';
        }
        
        $adds = DB::table('raothers')
                ->join('rasections','rasections.id','=','raothers.rasection_id')
                ->where('rareport_id','=',$this->fra->id)
                ->select('raothers.picture AS picture','raothers.caption AS caption','rasections.id AS section_id','rasections.name AS section_name')
                ->orderBy('raothers.rasection_id','asc')
                ->orderBy('raothers.id','asc')
                ->get();
        
        $this->additional = array();
        
        foreach ($adds as $curadd) {
            if (!isset($this->additional[$curadd->section_id])) {
                $this->additional[$curadd->section_id] = array();
            }
            
            $fullpath = public_path() . '/fra' . $curadd->picture;
            
            if (!file_exists($fullpath) or is_dir($fullpath)) {
                continue;
            }
            
            $this->additional[$curadd->section_id][] = $curadd;
        }
        
        $this->printHeader = true;
        $this->printFooter = true;
    }
    
    public function getElements()
    {
        return array(
            'shop_id'   =>  $this->shop_id,
            'shop'      =>  $this->shop,
            'client'    =>  $this->client,
            'revision'  =>  $this->revision,
            'fra'       =>  $this->fra,
            'prepuser'  =>  $this->prepuser,
            'sections'  =>  $this->sections,
            'questions' =>  $this->questions,
            'answers'   =>  $this->answers,
            'remedials' =>  $this->remedials,
            'revisions' =>  $this->revisions,
        );
    }
    
    public function defineStandardFont()
    {
        $pdf_standards = array(
            'font-family'       =>  'Calibri',
            'font-bigger'       =>  '30',
            'font-big'          =>  '20',
            'font-size'         =>  '11',
            'font-mini'         =>  '10',
            'font-mini-small'   =>  '8',
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
    
    public function insertTOC($location = 1) 
    {        
        // make toc at end
        $this->stopPageNums();
        
        $tocstart = $this->page;
        
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
        if ($this->page == 1) {
	    $picture = public_path() . '/img/coverpage-fra.jpg';

	    $imgw = 210;
	    $imgh = 297;

            $this->Image($picture, 0, 0, $imgw, $imgh);

            return;
        }   
        
        if (!$this->printHeader) {
            return;
        }
        
        $stds = $this->defineStandardFont();
        $elems = $this->getElements();
        
        $x = 32;
        $y = 8;
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini-small']);
        $this->setTextColor(0,0,0);
        
        $rev = (($this->revision < 10) ? '0' : '') . $this->revision;
        
        $this->SetXY($x, $y);        
        $this->Cell(162,10,'Project Number ' . $this->client->derisk_number . '/' . (empty($this->shop->code) ? '' : ($this->shop->code . '/')) . $rev,0,1,'L');
        
        $y += 3;
        
        $full = $elems['shop']->address1;
        $full .= ' ' . $elems['shop']->address2;
        $full .= ' ' . $elems['shop']->town;
        $full .= ' ' . $elems['shop']->postcode;
        
        $this->SetXY($x, $y);
        $this->Cell(162,10,$this->client->companyname . ' ' . $full,0,1,'L');
        $y += 7;
        
        $line_width = $this->USEFUL_WIDTH;
        
        if ($this->CurOrientation == 'L') {
            $line_width = 233;
        }
        
        $this->Line($x,$y,$x + $line_width,$y);
    }
    
    public function Footer()
    {
        if ($this->page == 1) {
	    return;
        }
        
        if (!$this->printFooter) {
            return;
        }
        
        $stds = $this->defineStandardFont();
        
        $rev = $this->revision;
        if ($rev < 10) {
            $rev = '0' . $rev;
        }
        
        $x = 99;
        $y = 279;
        
        $line_width = $this->USEFUL_WIDTH;
        
        if ('L' == $this->CurOrientation) {
            $x = 142;
            $y = 192;
            $line_width = 233;
        }
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini-small']);
        $this->setTextColor(0,0,0);
        
        $this->SetXY($x, $y);        
        $this->Cell(50,10,'Derisk UK Ltd',0,1,'L');
        
        $x = 32;
        $y += 7;
        
        $this->Line($x,$y,$x + $line_width,$y);
        
        $this->SetXY($x, $y);
        $this->Cell(70,4,'Fire Risk Assessment',0,1,'L');
        
        $x = 103;
        
        if ('L' == $this->CurOrientation) {
            $x = 146;
        }
        
        $this->SetXY($x, $y);
        $this->Cell(50,4,'REV' . $rev,0,1,'L');
        
        $x = 162;
        
        if ('L' == $this->CurOrientation) {
            $x = 249;
        }
        
        $this->SetXY($x, $y);
        $this->Cell(50,4,'Page ' . (1 + $this->page) . ' of {nb}',0,1,'L');
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
        
        $this->AddPage();
        
        $fra = $this->fra;
        $issue_date = date('d F Y',$fra->survey_date);
        
        $this->setTitle(ucwords('Fire Risk Assessment ' . $this->shop->name . ' - Revision ' . $this->revision . ' - ' . $issue_date),true);
        
        $x = 32;        
        $y = 45;
        
        $this->SetXY($x, $y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-bigger']);
        $this->setTextColor(255,255,255);
        
        $this->Cell(162,10,'FIRE RISK ASSESSMENT',0,1,'L');
        
        if (strpos($this->client->companyname,'Pret') !== false) {
            $picture = public_path() . '/img/logo-pret.png';
            
            $x = 110;
            $y = 28;

            $this->Image($picture, $x, $y);
        }
        
        $this->SetFont($stds['font-family'],'',$stds['font-big']);
        $this->setTextColor(255,255,255);
        
        $x = 32;
        $y = 65;
        
        $this->SetXY($x, $y);        
        $this->Cell(162,10,$this->shop->name . (empty($this->shop->code) ? '' : (' - Shop Number ' . $this->shop->code)),0,1,'L');
        
        $y += 8;
        
        $this->SetXY($x, $y);
        $this->Cell(162,10,$this->shop->address1,0,1,'L');
        $y += 8;
        
        $this->SetXY($x, $y);
        $this->Cell(162,10,$this->shop->address2,0,1,'L');
        $y += 8;
        
        $this->SetXY($x, $y);
        $this->Cell(162,10,$this->shop->town . ' ' . $this->shop->postcode,0,1,'L');
        $y += 8;
        
        $this->SetXY($x, $y);
        $this->Cell(162,10,'Issue Date: ' . $issue_date,0,1,'L');
        
        return $y;
    }
    
    public function printExecutiveSummary()
    {
        $this->addPage('P');
        
        $this->TOC_Entry('Premises Details and Executive Summary', 0);
        
        $stds = $this->defineStandardFont();
        
        $x = 32;
        
        $y = $this->printTitleBar('Premises Details and Executive Summary');
        
        $this->SetTextColor(0,0,0);
        
        $picture = public_path() . '/fra' . $this->fra->main_picture;        
        
        if (!empty($picture) and file_exists($picture) and !is_dir($picture)) {
            //$new_main_picture = $this->rotatePicture($picture);
            $new_main_picture = $picture;
            
            $this->Image($new_main_picture,($x + (($this->USEFUL_WIDTH - $this->PICTURE_WIDTH) / 2)),$y + 5,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
            
            //unlink($new_main_picture);
        
            $y += 64;
        } else {
            $y += 10;
        }
        
        $cellw_one = 50;
        $cellw_two = 96;
        
        $width = $cellw_one + $cellw_two;
        
        $low_limit = 280;
        
        $recth = 7;
        $cellh = 5;
        
        $nbs = array(
            0   =>  array($recth,$cellh,1),
            1   =>  array($recth,$cellh,1),
            2   =>  array($recth,$cellh,1),
            3   =>  array($recth,$cellh,1),
            4   =>  array($recth,$cellh,1),
            5   =>  array($recth,$cellh,1),
            6   =>  array($recth,$cellh,1),
            7   =>  array($recth,$cellh,1),
            8   =>  array($recth,$cellh,1),
            9   =>  array($recth,$cellh,1),
            10  =>  array($recth,$cellh,1),
            11  =>  array($recth,$cellh,1),
            12  =>  array($recth,$cellh,1),
            13  =>  array($recth,$cellh,1),
            14  =>  array($recth,$cellh,1),
        );
        
        $address = $this->client->companyname;
        $address .= "\n" . $this->shop->address1;
        $address .= "\n" . $this->shop->address2;
        $address .= "\n" . $this->shop->town . ' ' . $this->shop->postcode;
        
        $fields = array(
            0   =>  array('Survey Date',date('d/m/Y',$this->fra->survey_date)),
            1   =>  array('Address',$address),
            2   =>  array('Responsible Person',$this->fra->responsible_person),
            3   =>  array('Assessor',$this->fra->assessor),
            4   =>  array('Person to meet on site',$this->fra->person_to_meet),
            5   =>  array('Use of Building',$this->fra->use_of_building),
            6   =>  array('Number of Floors',$this->fra->number_of_floors),
            7   =>  array('Construction type',$this->fra->construction_type),
            8   =>  array('Approx. Maximum Number of Occupants',$this->fra->max_number_occupants),
            9   =>  array('Approx. Number of Employees at any Time',$this->fra->number_employees),
            10  =>  array('Disabled Occupants',$this->fra->disabled_occupants),
            11  =>  array('Occupants in Remote Areas and Lone Workers',$this->fra->remote_occupants),
            12  =>  array('Current Hours of Operation',$this->fra->hours_operation),
            13  =>  array('Date of Previous Fire Risk Assessment',((empty($this->fra->old_issue_date) or ('none' == $this->fra->old_issue_date)) ? 'none' : date('d F Y',strtotime($this->fra->old_issue_date)))),
            14  =>  array('Recommended review date',date('d F Y',$this->fra->next_date_recommended)),
        );
        
        foreach ($fields as $i => $field) {
            // Calculate the height of the row
            $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
            $nb_two = max(0,$this->nbLines($cellw_two, $field[1]));
            $nb = max($nb_one,$nb_two);

            $recth = 2 + ($cellh * $nb);
            
            $nbs[$i] = array($recth,$cellh,$nb);
        }
        
        foreach ($fields as $i => $field) {
            $string_to_print = $field[1];

            $currecth = 0;

            while (!empty($string_to_print)) {
                // tot lines from the current position to the end of the page
                $remaining = ($low_limit - $y - 5) / $cellh;
                
                if ($remaining < 2) {
                    $this->AddPage();

                    $y = 27;
                }
                
                $maxline = min($remaining,$nbs[$i][2]);

                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);

                $currecth = 1 + min($nbs[$i][0],$low_limit - $y - 5);

                $this->SetFont($stds['font-family'],'B',$stds['font-size']);
                $this->Rect($x, $y, $cellw_one, $currecth, 'FD');
                $this->SetXY(1 + $x, $y + 1);
                $this->MultiCell($cellw_one - 2, $nbs[$i][1], $field[0], 0, 'L', true, $maxline); 

                $this->SetFillColor(255,255,255);
                
                $this->SetFont($stds['font-family'],'',$stds['font-size']);
                $this->Rect($x + $cellw_one, $y, 2 + $cellw_two, $currecth, 'FD');
                $this->SetXY(1 + $x + $cellw_one, $y + 1);
                $string_to_print = $this->MultiCell($cellw_two, $nbs[$i][1], $string_to_print, 0, 'J', true, $maxline);

                $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
                $nb_two = max(0,$this->nbLines($cellw_two, $string_to_print));
                $nb = max($nb_one,$nb_two);

                $nbs[$i][0] = 2 + ($cellh * $nb);
                $nbs[$i][2] = $nb;

                if (!empty($string_to_print)) {
                    $this->AddPage();

                    $y = 21;
                }
            }

            $y += $currecth;
        }
        
        $this->AddPage();             

        $y = 21;
        
        $sentence = 'The Fire Risk Assessment should be reviewed by a competent person by the date indicated above or at such ';
        $sentence .= 'earlier time if there is reason to suspect that it is no longer valid or if there has been a significant change in ';
        $sentence .= 'the matters to which it relates; a review is also required following a fire.';
        
        while (!empty($sentence)) {
            $this->SetXY($x, $y);
            
            $fullnb = max(0,$this->nbLines($width, $sentence));
            $currecth = 1 + min($fullnb,$low_limit - $y);
            
            $remaining = ($low_limit - $y - 5) / (1 + $cellh);
            $maxline = min($remaining,$fullnb);
            
            $sentence = $this->MultiCell($width, $cellh, $sentence, 0, 'J', true, $maxline);
        }
        
        $y += 25;
        
        $this->SetXY($x,$y);
        
        $cellh = 4.9;
        
        $nbs = array(
            0   =>  array($recth,$cellh,1),
            1   =>  array($recth,$cellh,1),
            2   =>  array($recth,$cellh,1),
        );
        
        $fields = array(
            0   =>  array('Executive Summary',$this->fra->executive_summary),
            1   =>  array('Fire Loss Experience',$this->fra->fire_loss_experience),
            2   =>  array('Relevant Fire Safety Legislation',$this->fra->relevant_fire_safety_legislation),
        );
        
        foreach ($fields as $i => $field) {
            // Calculate the height of the row
            $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
            $nb_two = max(0,$this->nbLines($cellw_two, $field[1]));
            $nb = max($nb_one,$nb_two);

            $recth = 2 + ($cellh * $nb);
            
            $nbs[$i] = array($recth,$cellh,$nb);
        }
        
        foreach ($fields as $i => $field) {
            $string_to_print = $field[1];

            $currecth = 0;

            while (!empty($string_to_print)) {
                $remaining = ($low_limit - $y - 5) / $cellh;
                
                if ($remaining < 2) {
                    $this->AddPage();

                    $y = 27;
                }
                
                $maxline = min($remaining,$nbs[$i][2]);

                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);

                $currecth = 1 + min($nbs[$i][0],$low_limit - $y - 5);

                $this->SetFont($stds['font-family'],'B',$stds['font-size']);
                $this->Rect($x, $y, $cellw_one, $currecth, 'FD');
                $this->SetXY(1 + $x, $y + 1);
                $this->MultiCell($cellw_one - 2, $nbs[$i][1], $field[0], 0, 'L', true, $maxline);                        

                $this->SetFillColor(255,255,255);
                
                $this->SetFont($stds['font-family'],'',$stds['font-size']);
                $this->Rect($x + $cellw_one, $y, 2 + $cellw_two, $currecth, 'FD');
                $this->SetXY(1 + $x + $cellw_one, $y + 1);
                $string_to_print = $this->MultiCell($cellw_two, $nbs[$i][1], $string_to_print, 0, 'J', true, $maxline);

                $nb_one = max(0,$this->nbLines($cellw_one, $field[0]));
                $nb_two = max(0,$this->nbLines($cellw_two, $string_to_print));
                $nb = max($nb_one,$nb_two);

                $nbs[$i][0] = 2 + ($cellh * $nb);
                $nbs[$i][2] = $nb;

                if (!empty($string_to_print)) {
                    $this->AddPage();

                    $y = 27;
                }
            }

            $y += $currecth;
        }
        
        $y += 5;
        
        return $y;
    }

    public function printQuestionsAnswers()
    {
        $this->AddPage('L');
        
        $this->TOC_Entry('Premises Survey', 0);
        
        $stds = $this->defineStandardFont();
        
        $y = $this->printTitleBar('Premises Survey');
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        
        $this->SetXY(136,8 + $y);
        
        $this->Cell(43,7,'FIRE RISKS AND HAZARDS');
        
        foreach ($this->sections as $index => $sect) {
            $y = $this->printTableSection($index+1,$sect,$this->questions[$sect->id],$this->answers);
        }
    }

    public function printGradingMethodology()
    {
        $this->AddPage('P');
        
        $this->TOC_Entry('Risk Assessment Grading and Methodology', 0);
        
        $stds = $this->defineStandardFont();
        
        $full_width = 146;        
        
        $x = 32;
        $y = $this->printTitleBar('Risk Assessment Grading and Methodology');
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $y += 5;
        
        $cell_h = 4;
        
        $this->SetXY($x,$y);
        
        $sentence = 'The following simple risk level estimator is based on a more general health and safety risk level estimator.';
        
        $this->Multicell($full_width,$cell_h,$sentence, 0, 'J');
        
        $y += 10;
        
        // Table of Likelihood of Fire
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->SetTextColor(0,0,0);
        $this->SetFillColor(217,217,217);
        
        $rect_w = $full_width / 4;
        $rect_h = 7;
              
        // First line
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,2 * $rect_h,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->Cell($rect_w - 2,(2 * $rect_h) - 2,"Likelihood of fire",0,1,'C',false);
        
        $this->Rect($x + (1 * $rect_w),$y,3 * $rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->Cell((3 * $rect_w) - 2,$rect_h - 2,"Potential consequences of fire",0,1,'C',false);
        
        $this->Rect($x + (1 * $rect_w),$y + $rect_h,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + $rect_h + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Slight Harm",0,1,'C',false);
        
        $this->Rect($x + (2 * $rect_w),$y + $rect_h,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $rect_w) + 1,$y + $rect_h + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Moderate Harm",0,1,'C',false);
        
        $this->Rect($x + (3 * $rect_w),$y + $rect_h,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $rect_w) + 1,$y + $rect_h + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Extreme Harm",0,1,'C',false);
        
        $y += 2 * $rect_h;
        
        // Second line
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Low",0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        $this->SetFillColor(146,208,80);
        
        $this->Rect($x + (1 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Trivial Risk",0,1,'C',false);
        
        $this->Rect($x + (2 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Tolerable Risk",0,1,'C',false);
        
        $this->SetFillColor(255,255,0);
        
        $this->Rect($x + (3 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Moderate Risk",0,1,'C',false);        
        
        $y += $rect_h;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->SetFillColor(217,217,217);
        
        // Third line
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Medium",0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        $this->SetFillColor(146,208,80);
        
        $this->Rect($x + (1 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Tolerable Risk",0,1,'C',false);
        
        $this->SetFillColor(255,255,0);
        
        $this->Rect($x + (2 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Moderate Risk",0,1,'C',false);
        
        $this->SetFillColor(255,0,0);
        
        $this->Rect($x + (3 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Substantial Risk",0,1,'C',false);
        
        $y += $rect_h;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->SetFillColor(217,217,217);
        
        // Fourth line
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"High",0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        $this->SetFillColor(255,255,0);
        
        $this->Rect($x + (1 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Moderate Risk",0,1,'C',false);
        
        $this->SetFillColor(255,0,0);
        
        $this->Rect($x + (2 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Substantial Risk",0,1,'C',false);
        
        $this->Rect($x + (3 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $rect_w) + 1,$y + 1);
        $this->Cell($rect_w - 2,$rect_h - 2,"Intolerable Risk",0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $y += $rect_h + 5;
        $this->SetXY($x,$y);
        
        $sentence = 'Taking into account the fire prevention measures observed at the time of this risk assessment, it is considered ';
        $sentence .= 'that the hazard from fire (likelihood of fire) at these premises is:';
        
        $this->Multicell($full_width,$cell_h,$sentence, 0, 'J');
        
        $y += $cell_h + 6;
        $this->SetXY($x,$y);
        
        $box_w = 24;
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Low Risk",0,1,'C',false);
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        
        $low_risk = '';
        if (1 == $this->fra->hazard_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $low_risk = '4';
        }     
        
        $this->Cell($box_w - 2,$rect_h - 2,$low_risk,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B', $stds['font-table']);
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (2 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Medium Risk",0,1,'C',false);
        
        $medium_risk = '';
        if (2 == $this->fra->hazard_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $medium_risk = '4';
        }  
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (3 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$medium_risk,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B', $stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (4 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (4 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"High Risk",0,1,'C',false);
        
        $high_risk = '';
        if (3 == $this->fra->hazard_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $high_risk = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (5 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (5 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$high_risk,0,1,'C',false);
                
        $y += 12;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'In this context, a definition of the above terms is as follows:';
        
        $this->Multicell($full_width,$cell_h,$sentence);
        
        $y += 6;
        $this->SetXY($x,$y);
        
        // Legend table about risk
       
        $rect_h = 6;        
                
        // First line
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,'Low Risk',0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Usually low likelihood of fire as a result of negligible potential sources of ignition';       
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += $rect_h;
        $this->SetXY($x,$y);
        
        // Second line

        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,"Medium Risk",0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Normal fire hazards (e.g. potential initial sources) for this type of occupancy, with fire ';
        $sentence .= 'hazards generally subject to appropriate controls (other than minor shortcomings)';
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'J',false);
        
        // Third line
        
        $y += (2 * $rect_h) - 2;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,"High Risk",0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Lack of adequate controls applied to one or more significant fire hazards, such as to ';
        $sentence .= 'result in significant increase in likelihood of fire';
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'J',false);
        
        // Table of life safety
        
        $y += (2 * $rect_h) + 4;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'Taking into account the nature of the building and the occupants, as well as the fire protection and procedural';
        $sentence .= 'arrangements observed at the time of this fire risk assessment, it is considered that the consequences for the life';
        $sentence .= 'safety in the event of fire would be:';
        
        $this->Multicell($full_width,$cell_h,$sentence, 0, 'J');
        
        $y += 14;
        
        $box_w = 24;
        $rect_h = 7;
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Slight Harm",0,1,'C',false);
        
        $slight_harm = '';
        if (1 == $this->fra->life_safety) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $slight_harm = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$slight_harm,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B', $stds['font-table']);        
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (2 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Moderate Harm",0,1,'C',false);
        
        $moderate_harm = '';
        if (2 == $this->fra->life_safety) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $moderate_harm = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (3 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$moderate_harm,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B', $stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (4 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (4 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Extreme Harm",0,1,'C',false);
        
        $extreme_harm = '';
        if (3 == $this->fra->life_safety) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $extreme_harm = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (5 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (5 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$extreme_harm,0,1,'C',false);
                
        $y += 12;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'In this context, a definition of the above terms is as follows:';
        
        $this->Multicell($full_width,$cell_h,$sentence);
        
        $y += 6;
        $this->SetXY($x,$y);
        
        // Legend table about life consequences
       
        $rect_h = 6;        
                
        // First line
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,'Slight Harm',0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Outbreak of fire unlikely to result in serious injury or death of any occupant (other than ';
        $sentence .= 'an occupant sleeping in a room in which a fire occurs)';       
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += (2 * $rect_h) - 2;
        $this->SetXY($x,$y);
        
        // Second line

        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,"Moderate Harm",0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Outbreak of fire could foreseeably result in injury (including serious injury) of one or ';
        $sentence .= 'more occupants, but it is likely to involve multiple fatalities';
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'J',false);
        
        // Third line
        
        $y += (2 * $rect_h) - 2;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (0 * $rect_w),$y,$rect_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $rect_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,"Extreme Harm",0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $sentence = 'Significant potential for serious injury or death of one or more occupants';
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $rect_w),$y,$full_width - $rect_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $rect_w) + 1,$y + 1);
        $this->MultiCell($full_width - $rect_w - 2,$cell_h,$sentence,0,'L',false);
        
        $y += $rect_h + 6;
                
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'Accordingly, it is considered that the risk to life from fire at these premises is:';
        
        $this->Multicell($full_width,$cell_h,$sentence);
        
        $y += 6;
        $this->SetXY($x,$y);
        
        $box_w = 14.6;
        
        $rect_h = 7;
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Trivial",0,1,'C',false);
        
        $trivial = '';
        if (1 == $this->fra->risk_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $trivial = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$trivial,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (2 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (2 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Tolerable",0,1,'C',false);
        
        $tolerable = '';
        if (2 == $this->fra->risk_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $tolerable = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (3 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (3 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$tolerable,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (4 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (4 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Moderate",0,1,'C',false);
        
        $moderate = '';
        if (3 == $this->fra->risk_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $moderate = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (5 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (5 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$moderate,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (6 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (6 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Substantial",0,1,'C',false);
        
        $substantial = '';
        if (4 == $this->fra->risk_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $substantial = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (7 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (7 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$substantial,0,1,'C',false);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (8 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (8 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Intolerable",0,1,'C',false);
        
        $intolerable = '';
        if (5 == $this->fra->risk_from_fire) {
            $this->SetFont('ZapfDingbats','', $stds['font-table']);
            $intolerable = '4';
        } 
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (9 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (9 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$intolerable,0,1,'C',false);
        
        $this->AddPage('P');
        $y = 21;

        $this->SetXY($x,$y);        
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'A suitable risk-based control plan should involve effort and urgency that is proportional to risk.';
        $sentence .= "\n" . 'The following risk-based control plan is based on one advocated for general health & safety risks:';
        
        $this->Multicell($full_width,$cell_h,$sentence, 0, 'J');
                
        $box_w = 24;
        $rect_h = 6;
        
        $y += 12;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(217,217,217);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,"Risk Level",0,1,'C',false);
        
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->Cell($full_width - $box_w - 2,$rect_h - 2,"Action and Timescale",0,1,'L',false);
        
        $y += $rect_h;
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->MultiCell($rect_w - 2,$cell_h,"Trivial",0,'L',false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        $sentence = 'No action is required and no detailed records need to be kept';
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->MultiCell($full_width - $box_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += $rect_h;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(146,208,80);
        $this->Rect($x + (0 * $box_w),$y,$box_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->MultiCell($box_w  - 2,$cell_h,"Tolerable",0,'L',false);
        
        $sentence = 'No major additional controls required. However, there might be a need for';
        $sentence .= ' improvements that involve minor or limited costs';
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,(2 * $rect_h) - 2,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->MultiCell($full_width - $box_w - 2,$cell_h,$sentence,0,1,'J',false);
        
        $y += (2 * $rect_h) - 2;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,255,0);
        $this->Rect($x + (0 * $box_w),$y,$box_w,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->MultiCell($box_w  - 2,$cell_h,"Moderate",0,'L',false);
        
        $sentence = 'It is essential that efforts are made to reduce the risk. Risk reduction measures should';
        $sentence .= ' be implemented within a defined time period. Where moderate risk is associated with';
        $sentence .= ' consequences that constitute extreme harm, further assessment might be required to';
        $sentence .= ' establish more precisely the likelihood of harm as a basis for determining the priority for';
        $sentence .= ' improved control measures.';
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->MultiCell($full_width - $box_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += (4 * $rect_h) - 6;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (0 * $box_w),$y,$box_w,(3 * $rect_h) - 4,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->MultiCell($box_w  - 2,$cell_h,"Substantial",0,'L',false);
        
        $sentence = 'Considerable resources might have to be allocated to reduce the risk. If the building is';
        $sentence .= ' unoccupied, it should not be occupied until the risk has been reduced. If the building is';
        $sentence .= ' occupied, urgent action is required.';
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,(3 * $rect_h) - 4,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->MultiCell($full_width - $box_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += (3 * $rect_h) - 4;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        $this->SetFillColor(255,0,0);
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->MultiCell($box_w  - 2,$cell_h,"Intolerable",0,'L',false);
        
        $sentence = 'The building (or relevant area) should not be occupied until the risk is reduced';
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $this->SetFillColor(255,255,255);
        $this->Rect($x + (1 * $box_w),$y,$full_width - $box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->MultiCell($full_width - $box_w - 2,$cell_h,$sentence,0,'J',false);
        
        $y += $rect_h + 6;
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'BU',$stds['font-mini']);
        
        $this->Cell(5,$cell_h,"NB:");
        
        $this->SetXY($x + 7,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        
        $sentence = 'Although the purpose of this section is to place the fire risk in context, the above approach to fire risk';
        $sentence .= ' assessment is subjective and for guidance only. All hazards and deficiencies identified in this report should be';
        $sentence .= ' addressed by implementing all recommendations contained in the following action plan. The fire risk assessment';
        $sentence .= ' should be reviewed regularly.';
        
        $this->MultiCell($full_width,$cell_h,$sentence,0,'J',false);
        
        return $y;
    }

    public function printActionPlan()
    {
        $this->AddPage('L');
        
        $this->TOC_Entry('Action Plan', 0);
        
        $stds = $this->defineStandardFont();
        
        $full_width = 233;      
        $cell_h = 4;
        $box_w = 24;
        $rect_h = 7;
        
        $x = 32;
        $y = $this->printTitleBar('Action Plan');
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $y += 5;
        
        $this->SetXY($x,$y);
        
        $sentence = 'It is considered that the following recommendations should be implemented in order to reduce fire risk to, or maintain it at, the following level:';
        
        $this->Multicell($full_width,$cell_h,$sentence, 0, 'J');
        
        $x = 124.5;
        $y += 10;

        $this->SetXY($x,$y);
        
        $general_fire_risk = '';
        
        switch ($this->fra->general_fire_risk) {
            case 1:
                $general_fire_risk = 'Trivial';
                $this->SetFillColor(146,208,80);
                break;
            case 2:
                $general_fire_risk = 'Tolerable';
                $this->SetFillColor(146,208,80);
                break;
            case 3:
                $general_fire_risk = 'Moderate';
                $this->SetFillColor(255,255,0);
                break;
            case 4:
                $general_fire_risk = 'Substancial';
                $this->SetFillColor(255,0,0);
                break;
            case 5:
                $general_fire_risk = 'Intolerable';
                $this->SetFillColor(255,0,0);
                break;
            default:
                break;
        }        
        
        $this->Rect($x + (0 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (0 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$general_fire_risk,0,1,'C',false);
        
        $this->SetFont('ZapfDingbats','', $stds['font-table']);
        $general_level = '4';
        
        $this->Rect($x + (1 * $box_w),$y,$box_w,$rect_h,'FD');
        $this->SetXY($x + (1 * $box_w) + 1,$y + 1);
        $this->Cell($box_w - 2,$rect_h - 2,$general_level,0,1,'C',false);
        
        $x = 32;
        $y += 12;
        $this->SetXY($x,$y);
        
        $rect_h = 7;        
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);        
        $this->SetFillColor(217,217,217);
        
        $this->Rect($x,$y,40,$rect_h,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->Cell(38,$rect_h - 2,"Risk Grading",0,1,'C',false);
        
        $this->Rect($x + 40,$y,150,$rect_h,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->Cell(148,$rect_h - 2,"Description of Deficiency",0,1,'L',false);
        
        $this->Rect($x + 190,$y,43,$rect_h,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->Cell(41,$rect_h - 2,"Suggested Timescale",0,1,'C',false);
        
        $y += $rect_h;
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        // Grade 1
        $this->SetFillColor(255,0,0);
        
        $this->Rect($x,$y,40,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->MultiCell(38,$rect_h - 2,"1",0,'C',false);
        
        $this->SetFillColor(255,255,255);
        
        $sentence = 'Major risk critical deficiency which could lead to death or serious injury to occupants. (Usually major deficiency in the';
        $sentence .= ' means of escape or means of detecting a fire and raising the alarm).' . "\n";
        $sentence .= 'Deficiencies in this group could lead to the issuing of Prohibition Notice/Enforcement Notice being issued by enforcing';
        $sentence .= ' authorities.';
        
        $this->Rect($x + 40,$y,150,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->MultiCell(148,$rect_h - 2,$sentence,0,'L',false);
        
        $sentence = 'Immediately to 1 month';
        
        $this->Rect($x + 190,$y,43,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->MultiCell(41,$rect_h - 2,$sentence,0,'C',false);
        
        $y += (4 * $rect_h) - 6;
        
        // Grade 2
        $this->SetFillColor(255,255,0);
        
        $this->Rect($x,$y,40,(5 * $rect_h) - 8,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->MultiCell(38,$rect_h - 2,"2",0,'C',false);
        
        $this->SetFillColor(255,255,255);
        
        $sentence = 'Minor risk critical deficiency, (E.g. Minor deficiency in means of escape, such as insufficient signage or emergency';
        $sentence .= ' lighting, or in the means of detecting a fire and raising the alarm, such as smoke detectors missing from certain areas';
        $sentence .= ' where necessary), and major non risk critical deficiency, (E.g. maintenance, training, management etc),' . "\n";
        $sentence .= 'Deficiencies in this group could lead to the issuing of Enforcement Notice/Improvement Notice being issued by';
        $sentence .= ' enforcing authorities.';
        
        $this->Rect($x + 40,$y,150,(5 * $rect_h) - 8,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->MultiCell(148,$rect_h - 2,$sentence,0,'L',false);
        
        $sentence = '1 to 3 months';
        
        $this->Rect($x + 190,$y,43,(5 * $rect_h) - 8,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->MultiCell(41,$rect_h - 2,$sentence,0,'C',false);
        
        $y += (5 * $rect_h) - 8;
        
        // Grade 3
        $this->SetFillColor(146,208,80);
        
        $this->Rect($x,$y,40,(3 * $rect_h) - 5,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->MultiCell(38,$rect_h - 2,"3",0,'C',false);
        
        $this->SetFillColor(255,255,255);
        
        $sentence = 'Minor non risk critical deficiency, (E.g. missing warning notices for COSSH chemicals, providing fire safety information';
        $sentence .= ' to contractors etc), which although minor are still a requirement of fire safety legislation, and which could lead to the';
        $sentence .= ' issuing of an Improvement Notices by enforcing authorities.';
        
        $this->Rect($x + 40,$y,150,(3 * $rect_h) - 5,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->MultiCell(148,$rect_h - 2,$sentence,0,'L',false);
        
        $sentence = '3 to 6 months';
        
        $this->Rect($x + 190,$y,43,(3 * $rect_h) - 5,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->MultiCell(41,$rect_h - 2,$sentence,0,'C',false);
        
        $y += (3 * $rect_h) - 5;
        
        // Grade 4
        $this->SetFillColor(0,176,240);
        
        $this->Rect($x,$y,40,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->Multicell(38,$rect_h - 2,"4",0,'C',false);
        
        $this->SetFillColor(255,255,255);
        
        $sentence = 'Major deficiency in fire safety provisions designed to prevent the spread of fire and protect property. (E.g. major lack';
        $sentence .= ' of fire separation within premises, or defects in fire doors which are provided for property protection only).' . "\n";
        $sentence .= 'These defects are for property safety only, and failure to address them would not lead to any significant risk to';
        $sentence .= ' occupiers. It is most unlikely that any enforcement action would be taken by authorities.';
        
        $this->Rect($x + 40,$y,150,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->MultiCell(148,$rect_h - 2,$sentence,0,'L',false);
        
        $sentence = 'Optional, but 6 to 12 month recommended';
        
        $this->Rect($x + 190,$y,43,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->MultiCell(41,$rect_h - 2,$sentence,0,'C',false);
        
        $y += (4 * $rect_h) - 6;
        
        // Grade 5
        $this->SetFillColor(204,192,217);
        
        $this->Rect($x,$y,40,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 1,$y + 1);
        $this->MultiCell(38,$rect_h - 2,"5",0,'C',false);
        
        $this->SetFillColor(255,255,255);
        
        $sentence = 'Minor deficiencies in fire safety provisions designed to prevent the spread of fire and protect property. (E.g. Minor lack';
        $sentence .= ' of fire separation within premises, such as small holes through walls).' . "\n";
        $sentence .= 'These defects are for property safety only, and failure to address them would not lead to any significant risk to';
        $sentence .= ' occupiers. It is most unlikely that any enforcement action would be taken by authorities.';
        
        $this->Rect($x + 40,$y,150,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 41,$y + 1);
        $this->MultiCell(148,$rect_h - 2,$sentence,0,'L',false);
        
        $sentence = 'Recommendation only, which occupier may wish to consider to improve property protection as part of scheduled works.';
        
        $this->Rect($x + 190,$y,43,(4 * $rect_h) - 6,'FD');
        $this->SetXY($x + 191,$y + 1);
        $this->MultiCell(41,$rect_h - 2,$sentence,0,'C',false);
        
        $y += (4 * $rect_h) - 6;
        
        return $y;        
    }
    
    public function printRemedialActions()
    {
        $y = $this->GetY();
        
        if (empty($this->remedials)) {
            return $y;
        }
        
        $this->AddPage('L');
        
        $stds = $this->defineStandardFont();
        
        $lower_bound = 190;
        
        $x = 32;
        $y = 21;
        
        // Calculate the number of rows for the headers
        $nb_headers = 0;
        $nb = array();

        $colsw = array(
            31.15,
            50.0,
            21.4,
            31.35,
            31.35,
            31.35,
            36.6,
        );

        $headers = array(
            "Report Ref & Hazard Area",
            "Recommended Remedial Action",
            "Priority Code",
            "Action by Whom",
            "Actioned by",
            "Date of completion",
            "Photo",
        );
        
        $cellh = 5;
        $hfact = 1.0;
        $imgw = 35;
        $imgh = 26.25;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        foreach ($headers as $i => $header) {
            $nb_headers = max($nb_headers,$this->nbLines($colsw[$i], $header));
        }
        
        $rect_headerh = ($hfact * $cellh * $nb_headers) - 2;
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $cells = array();
        
        $k = 0;
        foreach ($this->remedials as $quest_id => $remedial) {
            $record = array(
                $remedial->topic,
                $remedial->recommendation,
                $remedial->priority_code,
                $remedial->action_by_whom,
                $remedial->actioned_by,
                '',
                (empty($remedial->picture) ? '' : public_path() . '/fra' . $remedial->picture),
            );
            
            if (!empty($remedial->date_of_completion) and ('0000-00-00' != $remedial->date_of_completion)) {
                $record[5] = date('d/m/Y',strtotime($remedial->date_of_completion));
            }
            
            $cells[$k] = $record;

            $nb[$k] = 0;

            foreach ($record as $j => $cell) {
                $cell_lines = $this->nbLines($colsw[$j], $cell);
                $nb[$k] = max($nb[$k],$cell_lines);
            }
            
            $k++;
        }
        
        $first_line_rect = 0;
            
        foreach ($cells[0] as $i => $elem) {
            $first_line_rect = max($first_line_rect,$this->nbLines($colsw[$i] - 2, $elem));
        }
        
        $rect_elem = ($hfact * ($cellh - 1) * $first_line_rect);
        $rect_elem = max($imgh + 2, $rect_elem);
        
        if ($y >= $lower_bound - $rect_headerh - 2 - $cellh - $rect_elem) {
            $this->AddPage('L');
                
            $y = 21;
        }
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $align = 'L';
            if (in_array($k,array(2,3,4,5,6,))) {
                $align = 'C';
            }
            
            if ($k == 0) {
                $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                $this->SetXY($x + 1, $y + 1);                    
            } else {
                $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
            }

            $this->MultiCell($colsw[$k] - 2, $cellh - 2, $headers[$k], 0, $align, false);
        }
        
        $this->SetFillColor(255,255,255);
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $y += $rect_headerh;
        
        foreach ($cells as $index => $record) {            
            $nb_record = 0;
            
            foreach ($record as $i => $elem) {
                $nb_record = max($nb_record,$this->nbLines($colsw[$i] - 2, $elem));
            }

            $rect_elem = ($hfact * ($cellh - 1) * $nb_record);
            $rect_elem = max($imgh + 2, $rect_elem);
            
            if ($nb_record == 1) {
                $rect_elem = ($hfact * $cellh * $nb_record);
                $rect_elem = max($imgh + 2, $rect_elem);
            }
            
            if ($rect_elem > $lower_bound - $y) {
                $this->AddPage('L');
                
                $y = 21;
                
                $this->SetFont($stds['font-family'],'B',$stds['font-table']);
                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);

                for ($k = 0; $k < count($headers); $k++) {
                    $align = 'L';
                    if (in_array($k,array(2,3,4,5,6,))) {
                        $align = 'C';
                    }

                    if ($k == 0) {
                        $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                        $this->SetXY($x + 1, $y + 1);                    
                    } else {
                        $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                        $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                    }

                    $this->MultiCell($colsw[$k] - 2, $cellh - 2, $headers[$k], 0, $align, false);
                }

                $this->SetFillColor(255,255,255);
                $this->SetFont($stds['font-family'],'',$stds['font-table']);

                $y += $rect_headerh;
            }
            
            for ($k = 0; $k < count($record); $k++) {
                if ($k == 0) {
                    $this->SetFont($stds['font-family'],'B', $stds['font-table']);
                } else {
                    $this->SetFont($stds['font-family'],'', $stds['font-table']);
                }
                
                $this->SetTextColor(0,0,0);
                
                $align = 'L';
                $this->SetFillColor(255,255,255);
                
                if ($k == 0) {
                    $this->SetFillColor(217,217,217);
                    $align = 'L';
                } else if ($k == 2) {
                    $align = 'C';
                    
                    switch ($record[2]) {
                        case '1':
                            $this->SetFillColor(255,0,0);
                            break;
                        case '2':
                            $this->SetFillColor(255,255,0);
                            break;
                        case '3':
                            $this->SetFillColor(146,208,80);
                            break;
                        case '4':
                            $this->SetFillColor(0,176,240);
                            break;
                        case '5':
                            $this->SetFillColor(204,192,217);
                            break;
                        default:
                            break;
                    }
                } else if (in_array($k,array(3,4,5,))) {
                    $align = 'C';
                }
                
                if ($k == 0) {
                    $this->Rect($x, $y, $colsw[0], $rect_elem, 'FD');
                    $this->SetXY($x + 1, $y + 1);                    
                } else {
                    $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_elem, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                }
                
                if ($k < 6) {
                    $this->MultiCell($colsw[$k] - 2, $cellh - 2, $record[$k], 0, $align, false);
                } else if (!empty($record[$k]) and file_exists($record[$k]) and !is_dir($record[$k])) {
                    //$new_picture = $this->rotatePicture($record[$k]);
                    $new_picture = $record[$k];
                                       
                    $this->Image($new_picture, $x + $this->sumUntil($colsw,$k) + 0.5, $y + 1, $imgw, $imgh, 'jpg', '', 'C', true);
                    
                    //unlink($new_picture);
                }
            }
            
            $y += $rect_elem;
        }
        
        $this->SetXY($x,$y);
        
        return $y;
    }
    
    public function printAdditionalPictures()
    {
        if (empty($this->additional)) {
            return;
        }
        
        $this->AddPage('P');
        
        $this->TOC_Entry('Additional Pictures', 0);
        
        $stds = $this->defineStandardFont();
        
        $full_width = 146;
        $cell_h = 5;
        $picture_w = 70;
        $picture_h = 52.50;
        $picture_shift = $full_width - (2 * $picture_w);
        $low_limit = 281;
        
        $x = 32;
        $y = $this->printTitleBar('Additional Pictures');        
        
        $y += 5;
        
        $totsections = count($this->additional);
        $n = 0;
        
        foreach ($this->additional as $section => $pictures) {    
            $this->SetXY($x,$y);
            
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->SetTextColor(0,0,0);
            
            $sentence = $section . '. ' . $pictures[0]->section_name;
              
            $this->MultiCell($full_width, $cell_h, $sentence);
            
            $y += 8;
            
            $this->SetXY($x,$y);
            
            $totpictures = count($pictures);
            
            $newpage = false;
            
            foreach ($pictures as $k => $curpicture) {
                $fullpath = public_path() . '/fra' . $curpicture->picture;
                
                //$new_picture = $this->rotatePicture($fullpath);
                $new_picture = $fullpath;
                
                $this->Image($new_picture, $x, $y, $picture_w, $picture_h, 'jpg', '', 'C', true);
                
                //unlink($new_picture);
                
                $this->SetFont($stds['font-family'],'',$stds['font-mini']);
                $this->SetTextColor(0,0,0);
                
                $y += $picture_h + 2;
                $this->SetXY($x,$y);
                
                $multih = $cell_h * $this->nbLines($picture_w, $curpicture->caption);
                
                $this->MultiCell($picture_w, $cell_h, $curpicture->caption);
                
                $x += $picture_w + $picture_shift;
                $y = $y - $picture_h - 2;
                
                $this->SetXY($x,$y);
                
                if (($x > $full_width) or ($k == $totpictures - 1)) {
                    $x = 32;
                    $y += $picture_h + $multih + 10;
                    
                    $this->SetXY($x,$y);
                }
                
                if (($low_limit < ($y + $picture_h + 10)) and (($k < $totpictures - 1) or ($n < $totsections - 1))) {
                    $newpage = true;
                    $this->AddPage('P');
                    $y = 21;
                    $this->SetXY($x,$y);
                } else {
                    $newpage = false;
                }
            }
            
            $n++;
            
            if (($n < $totsections) and !$newpage) {                
                $this->Line($x,$y,$x + $full_width,$y);
                $y += 5;
            }
        }    
    }

    public function printReferences()
    {
        $this->AddPage('P');
        
        $this->TOC_Entry('References', 0);
        
        $stds = $this->defineStandardFont();
        
        $full_width = 146;
        $cell_h = 5;
        
        $x = 32;
        $y = $this->printTitleBar('References');
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $y += 10;
        
        $this->SetXY($x,$y);
        
        $sentence = 'The following is a list of the reference documentation that may be considered as "Benchmark Standards" and which have, ';
        $sentence .= 'where relevant, been referred to for the purposes of producing this report:';
        
        $this->MultiCell($full_width, $cell_h, $sentence, 0, 'J');
        
        $y += 20;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $sentence = 'General Regulations and Standards:';
        
        $this->MultiCell($full_width, $cell_h, $sentence);
        
        $y += 8;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $sentences = array(
            'Building Regulations 2000 Approved Document B (2013 edition)',
            'British Standard 9999: 2008 Fire Safety in the Design, Management and Use of Buildings',
            'British Standard 5839: 2002 + A2 2008 Fire Alarm Systems and Equipment',
            'British Standard 5266: 2005 Emergency Lighting Systems',
            'British Standard EN3 and 5306: 1990 Fire Extinguishing Equipment',
            'British Standard 5378-1:1980 Safety Signs and Colours',
            'British Standard 4533-2 Electric Luminares',
            'British Standard 5499: 2006 Fire Safety Signs',
            'British Standard 8214: 1990 Fire Door Assemblies',
            'British Standard 476: 2004 Fire Testing of Structural Elements',
            'The Health and Safety (Safety Signs and Signals) Regulations 1996',
            'The Furniture and Furnishing (Fire) (Safety) Regulations 1988 (as amended 1993)',
            'The Dangerous Substances and Explosive Atmosphere (DSEAR) Regulations 2002',
            'British Standard 6651:1999 COP for the Protection of Structures against Lightning',
            'The Disability Discrimination Act (DDA) 1995 (as amended by DDA 2005)',
            'British Standard 7671: 2008 17th Edition IEE Wiring Regulations',
            'The Construction (Design and Management) Regulations 2015',
            'The Licensing Act 2003',
            'Fire and Rescue Services Act 2004',
            'The Residential Homes Act 1980',
            'The Nursing Homes Act 1975',
            'Care Standards Act 2000',
        );
        
        foreach ($sentences as $cursentence) {
            $this->MultiCell($full_width, $cell_h, $cursentence);
            $y += $cell_h;
            $this->SetXY($x,$y);
        }
        
        $y += 10;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $sentence = 'Main Legislation Applicable';
        
        $this->MultiCell($full_width, $cell_h, $sentence);
        
        $y += 8;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $sentences = array(
            'The Regulatory Reform (Fire Safety) Order 2005',
            'Health and Safety at Work etc Act 1974',
            'Management of Health and Safety at Work Regulations 1999',
            'Electricity at Work Regulations 1989',
        );
        
        foreach ($sentences as $cursentence) {
            $this->MultiCell($full_width, $cell_h, $cursentence);
            $y += $cell_h;
            $this->SetXY($x,$y);
        }
        
        return $y;
    }

    public function printLastPage()
    {
        $this->printHeader = false;
        
        $this->AddPage('P');
        
        $picture = public_path() . '/img/lastpage.jpg';

        $imgw = 210;
        $imgh = 297;

        $this->Image($picture, 0, 0, $imgw, $imgh);
        
        $this->printFooter = false;
    }

    public function printContentsPage()
    {
        $this->printHeader = true;
        
        $this->AddPage('P');
        
        $stds = $this->defineStandardFont();
        
        $y = $this->printTitleBar('Contents');

	$left_limit = 32;
        $right_limit = 0;
        $y += 6;
        
        $this->SetXY($left_limit,$y);
        
        /**** TABLE OF CONTENTS ****/
        
        $str = 'Contents';
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        $strsize = $this->GetStringWidth($str);
        $this->Cell($strsize+2,$this->FontSize+2,$str,0);
        
        // Filling dots
        $p = '2';
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        $PageCellSize = $this->GetStringWidth($p) + 2;
        $w = $this->w - $left_limit - $right_limit - $PageCellSize - ($strsize + 2);
        $nb = ($w - 4) / $this->GetStringWidth('.');
        $dots = str_repeat('.',$nb - $left_limit);
        $this->Cell($w - $left_limit,$this->FontSize+2,$dots,0,0,'R');

        // Page number
        $this->Cell($PageCellSize,$this->FontSize+2,$p,0,1,'R');

        foreach ($this->_toc as $t) {
	    $this->SetX($left_limit);
            // Offset
            $level = $t['l'];
            if ($level > 0) {
                $this->Cell($level * 8);
            }

            $str = $t['t'];
            $this->SetFont($stds['font-family'],'',$stds['font-mini']);
            $this->SetTextColor(0,0,0);
            $strsize = $this->GetStringWidth($str);
            $this->Cell($strsize+2,$this->FontSize+2,$str,0);

            // Filling dots
            $this->SetFont($stds['font-family'],'',$stds['font-mini']);
            $this->SetTextColor(0,0,0);
            $PageCellSize = $this->GetStringWidth($t['p']) + 2;
            $w = $this->w - $left_limit - $right_limit - $PageCellSize - ($level * 8) - ($strsize + 2);
	    $nb = ($w - 4) / $this->GetStringWidth('.');
            $dots = str_repeat('.',$nb - $left_limit);
            $this->Cell($w - $left_limit,$this->FontSize+2,$dots,0,0,'R');

            // Page number
            $this->Cell($PageCellSize,$this->FontSize+2,$t['p'] - 1,0,1,'R');
        }
        
        /**** REVISIONS TABLE ****/
        
        $x = $left_limit;
        $y = 4 + $this->GetY();
        
        $lower_bound = 260;
        
        // Calculate the number of rows for the headers
        $nb_headers = 0;
        $nb = array();

        $colsw = array(
            48.66,
            48.66,
            48.66,
        );

        $headers = array(
            "Date",
            "Revision No.",
            "Comments",
        );
        
        $cellh = 7;
        $hfact = 1.1;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);

        foreach ($headers as $i => $header) {
            $nb_headers = max($nb_headers,$this->nbLines($colsw[$i], $header));
        }
        
        $rect_headerh = ($hfact * $cellh * $nb_headers);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $cells = array();
        
        $k = 0;
        foreach ($this->revisions as $currev) {
            $record = array(
                empty($currev->issue_date) ? date('d/m/Y',strtotime($currev->created_at)) : date('d/m/Y',$currev->issue_date),
                ($currev->revision < 10) ? ('0' . $currev->revision) : $currev->revision,
                $currev->comments,
            );
            
            $cells[$k] = $record;

            $nb[$k] = 0;

            foreach ($record as $j => $cell) {
                $cell_lines = $this->nbLines($colsw[$j], $cell);
                $nb[$k] = max($nb[$k],$cell_lines);
            }
            
            $k++;
        }
        
        $first_line_rect = 0;
            
        foreach ($cells[0] as $i => $elem) {
            $first_line_rect = max($first_line_rect,$this->nbLines($colsw[$i] - 2, $elem));
        }
        
        $rect_elem = ($hfact * ($cellh - 1) * $first_line_rect);
        
        if ($y >= $lower_bound - $rect_headerh - 2 - $cellh - $rect_elem) {
            $this->AddPage('P');
                
            $y = 21;
        }
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $align = 'C';
            
            if ($k == 0) {
                $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                $this->SetXY($x + 1, $y + 0.25);                    
            } else {
                $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 0.25);
            }

            $this->MultiCell($colsw[$k] - 2, $cellh, $headers[$k], 0, $align, false);
        }
        
        $this->SetFillColor(255,255,255);
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $y += $rect_headerh;
        
        foreach ($cells as $index => $record) {            
            $nb_record = 0;
            
            foreach ($record as $i => $elem) {
                $nb_record = max($nb_record,$this->nbLines($colsw[$i] - 2, $elem));
            }

            $rect_elem = ($hfact * ($cellh - 1) * $nb_record);
            
            if ($nb_record == 1) {
                $rect_elem = ($hfact * $cellh * $nb_record);
            }
            
            if ($rect_elem > $lower_bound - $y) {
                $this->AddPage('P');
                
                $y = 21;
                
                $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
                $this->SetFillColor(217,217,217);
                $this->SetDrawColor(0,0,0);

                for ($k = 0; $k < count($headers); $k++) {
                    $align = 'C';

                    if ($k == 0) {
                        $this->Rect($x, $y, $colsw[0], $rect_headerh, 'FD');
                        $this->SetXY($x + 1, $y + 1);                    
                    } else {
                        $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_headerh, 'FD');
                        $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                    }

                    $this->MultiCell($colsw[$k] - 2, $cellh, $headers[$k], 0, $align, false);
                }

                $this->SetFillColor(255,255,255);
                $this->SetFont($stds['font-family'],'',$stds['font-mini']);

                $y += $rect_headerh;
            }
            
            for ($k = 0; $k < count($record); $k++) {
                $this->SetFont($stds['font-family'],'', $stds['font-mini']);
                
                $this->SetTextColor(0,0,0);
                $this->SetFillColor(255,255,255);
                
                $align = 'C';
                
                if ($k == 0) {
                    $this->Rect($x, $y, $colsw[0], $rect_elem, 'FD');
                    $this->SetXY($x + ($k * $colsw[0]) + 1,$y + ($rect_elem / 2) - 3);
                } else if ($k == 1) {
                    $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[1], $rect_elem, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k) + 1,$y + ($rect_elem / 2) - 3);
                } else {
                    $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_elem, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
                }
                
                $this->MultiCell($colsw[$k] - 2, $cellh - 2, $record[$k], 0, $align, false);
            }
            
            $y += $rect_elem;
        }
        
        $y += 3;
        
        $this->SetXY($x,$y);
        
        /**** SIGNATURE TABLE ****/
        
        $headers = array(
            'Document Prepared by',
            'Signed',
            'Dated',
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $align = 'C';
            $rect_width = 48.66;
            $rect_height = 9;
            
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $headers[$k], 0, $align, false);
        }
        
        $y += $rect_height;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
        $imgw = 30;
	$imgh = 22.50;
        
        $rect_width = 48.66;
        $rect_height = 26.25;
        
        for ($k = 0; $k < count($this->prepuser); $k++) {
            $align = 'C';
            
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1); 
            
            if ($k == 0) {
                $this->SetXY($x + ($k * $rect_width) + 1,$y + ($rect_height / 2) - 7);
                $this->MultiCell($rect_width - 2, $cellh, $this->prepuser[$k], 0, $align, false);
            } else if ($k == 2) {
                $this->SetXY($x + ($k * $rect_width) + 1,$y + ($rect_height / 2) - 5);
                $this->MultiCell($rect_width - 2, $cellh, $this->prepuser[$k], 0, $align, false);
            } else if (!empty($this->prepuser[$k]) and file_exists($this->prepuser[$k]) and !is_dir($this->prepuser[$k])) {
                $this->Image($this->prepuser[$k], $x + ($k * $rect_width) + (($rect_width - $imgw) / 2), $y + (($rect_height - $imgh) / 2), $imgw, $imgh);
            }
        }
        
        $y = 22 + $this->GetY();
        
        /**** REVIEW TABLE ****/
        
        $headers = array(
            'Document Review by',
            'Signed',
            'Dated',
        );
        
        $content = array(
            $this->revuser,
            (empty($this->fra->review_signature) ? '' : (public_path() . '/fra' . $this->fra->review_signature)),
            date('d/m/Y',$this->fra->review_date),
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $align = 'C';
            $rect_width = 48.66;
            $rect_height = 9;
            
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $headers[$k], 0, $align, false);
        }
        
        $y += $rect_height;
        
        $this->SetXY($x,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
	$imgw = 30;
	$imgh = 22.50; 

        $rect_width = 48.66;
        $rect_height = 26.25; 

        for ($k = 0; $k < count($content); $k++) {
            $align = 'C';
            
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1);
            
            if ($k == 0) {
                $this->SetXY($x + ($k * $rect_width) + 1,$y + ($rect_height / 2) - 7);
                $this->MultiCell($rect_width - 2, $cellh, $content[$k], 0, $align, false);
            } else if ($k == 2) {
                $this->SetXY($x + ($k * $rect_width) + 1,$y + ($rect_height / 2) - 5);
                $this->MultiCell($rect_width - 2, $cellh, $content[$k], 0, $align, false);
            } else if (!empty($content[$k]) and file_exists($content[$k]) and !is_dir($content[$k])) {
                $this->Image($content[$k], $x + ($k * $rect_width) + (($rect_width - $imgw) / 2), $y + (($rect_height - $imgh) / 2), $imgw, $imgh);
            }
        }
        
        /**** TEXT ****/
        
        $y += 4 + $rect_height;
        
        $this->SetXY($x,$y);
	$this->SetFont($stds['font-family'],'',$stds['font-size']);
        
        $sentence = 'All necessary fire safety systems and equipment is provided at ' . $this->shop->address1 . ', and ';
        $sentence .= 'management of those systems appears to be generally very good. Taking into account the safety ';
        $sentence .= 'measures in place, enforced by the management and workforce, the premises risk level for the ';
        $sentence .= 'building at time of inspection is rated as ';
        
        $this->MultiCell(146, 5, $sentence, 0, 'J', false);
        
        switch ($this->fra->risk_level_rate) {
            case 'trivial':
                $this->SetTextColor(146,208,80);
                break;
            case 'tolerable':
                $this->SetTextColor(146,208,80);
                break;
            case 'moderate':
                $this->SetTextColor(255,255,0);
                break;
            case 'substancial':
                $this->SetTextColor(255,0,0);
                break;
            case 'intolerable':
                $this->SetTextColor(255,0,0);
                break;
            default:
                $this->SetTextColor(0,0,0);
                break;
        }       
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetXY(123,$this->GetY() - 5);
        $this->Write(5,ucfirst($this->fra->risk_level_rate));
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        $this->SetXY($this->GetX() + 1,$this->GetY());
        $this->Write(5,'.');
        
        $y += 22;
        
        $this->SetXY($left_limit,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);        
        
        $sentence = 'The fire safety deficiencies identified during the assessment were of a minor nature, and the action ';
        $sentence .= 'plan provides recommendations of good practice and improvement to drive the risk as low as ';
        $sentence .= 'possible.';
        
        $this->MultiCell(146, 5, $sentence, 0, 'J', false);
        
        $y += 20;
        
        $this->SetXY($left_limit,$y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);        
        
        $sentence = 'It is important that you study this fire risk assessment and understand its contents. If any ';
        $sentence .= 'recommendation in the Action Plan is unclear you should request further advice.';
        
        $this->MultiCell(146, 5, $sentence, 0, 'J', false);
        
        /**************/
        
        $rev = $this->revision;
        if ($rev < 10) {
            $rev = '0' . $rev;
        }
        
        // Print Footer Manually
        $x = 99;
        $y = 264;
        
        $line_width = $this->USEFUL_WIDTH;
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini-small']);
        $this->setTextColor(0,0,0);
        
        $this->SetXY($x, $y);        
        $this->Cell(50,10,'Derisk UK Ltd',0,1,'L');
        
        $x = 32;
        $y += 7;
        
        $this->Line($x,$y,$x + $line_width,$y);
        
        $this->SetXY($x, $y);
        $this->Cell(70,4,'Fire Risk Assessment',0,1,'L');
        
        $x = 103;
        
        $this->SetXY($x, $y);
        $this->Cell(50,4,'REV' . $rev,0,1,'L');
        
        $x = 162;
        
        $this->SetXY($x, $y);
        $this->Cell(50,4,'Page 2 of {nb}',0,1,'L');
        
        $this->printFooter = false;
    }
}

class FrasController extends Controller
{
    public function index()
    {
        
    }
    
    public function printReport(Request $request)
    {
        // Get th elements needed to construct the object
        $shop_id = $request->input('shop_id');
        $revision = $request->input('revision');
        
        // Create an instance to print the report
        $pdf = new FraPDF($shop_id,$revision);
        
        $elems = $pdf->getElements();
        
        $pdf->AliasNbPages();
        
        $pdf->startPageNums();
        
        $pdf->printCoverPage();
        
        $pdf->printExecutiveSummary();
        
        $pdf->printQuestionsAnswers();
        
        $pdf->printGradingMethodology();
        
        $pdf->printActionPlan();
        
        $pdf->printRemedialActions();
        
        $pdf->printAdditionalPictures();
        
        $pdf->printReferences();
        
        $pdf->printLastPage();
        
        $pdf->stopPageNums();
        
        $pdf->printContentsPage();
        
        $pdf->insertTOC(2);
             
        // Save the report in file
        $shop_name = str_replace(" ","-",$elems['shop']->name);
        $relative_path = '/' . strtolower($shop_name) . '/fra-issue-' . $elems['revision'] . '.pdf';
        $path_pdf = public_path() . '/fra' . $relative_path;
        
        $pdf->Output($path_pdf,'F');
        
        // Return the path of the written file
        return $relative_path;
    }
}
