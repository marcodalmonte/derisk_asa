<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RemovalPDF extends \fpdi\FPDI
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
    
    private function printRedBar($sentence,$x,$y,$width,$height)
    {
        $stds = $this->defineStandardFont();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->SetFillColor(192,0,0);
        $this->SetTextColor(255,255,255);
        
        $this->Rect($x, $y, $width, $height, 'F');
        $this->SetXY($x + 1, $y); 
        $this->MultiCell($width - 2, $height, $sentence, 0, 'L', false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
    }
    
    private function printInspectionsTable($inspections,$item_no,$y = 25)
    {
        if (empty($inspections)) {
            return;
        }
        
        $stds = $this->defineStandardFont();
        
        $headers = array(
            "Room\nArea",
            "Observation\nComment",
            "Inspection\nSample No.",
            "Analysis\nResult",
            "Product",
            "Quantity",
            "Extent\nof Damage",
            "Surface\nTreatment",
            "Recommendation",
            "Photo",
        );
        
        $colsw = array(
            18.0,
            37.4,
            18.0,
            26.6,
            26.6,
            26.6,
            26.6,
            26.6,
            29.0,
            36.6,
        );
        
        $x = 12.50;
        
        $cellh = 3;
        $rect_cellh = (1 + $cellh) * 2;
        
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        
        for ($k = 0; $k < count($headers); $k++) {
            if ($k == 0) {
                $this->Rect($x, $y, $colsw[0], $rect_cellh, 'FD');
                $this->SetXY($x + 1, $y + 1);                    
            } else {
                $this->Rect($x + $this->sumUntil($colsw,$k), $y, $colsw[$k], $rect_cellh, 'FD');
                $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $y + 1);
            }

            $this->MultiCell($colsw[$k] - 1, $cellh, $headers[$k], 0, 'C', false);
        }
                
        $cury = $y + $rect_cellh;
        
        $this->SetXY($x, $cury);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $imgw = 35;
        $imgh = 26.25;
        
        foreach ($inspections as $c => $curinsp) {
            $record = array(
                $curinsp->room,
                $curinsp->comment,
                $curinsp->inspection_no,
                $curinsp->result,
                $curinsp->product,
                $curinsp->quantity,
                $curinsp->extent_of_damage,
                $curinsp->surface_treatment,
                $curinsp->recommendation,
                $curinsp->picture_path,
            );
            
            $nbs = 0;

            foreach ($record as $j => $cell) {
                if ($j == 9) {
                    continue;
                }

                $cell_lines = $this->nbLines($colsw[$j], $cell);

                $nbs = max($nbs,$cell_lines);
            }
            
            $rect_cellh = (1 + $cellh) * $nbs;
            $rect_cellh = max(1 + $imgh,$rect_cellh);
            
            for ($k = 0; $k < count($record); $k++) {
                if ($k == 9) {
                    $start_picture = public_path() . '/tablet' . $record[9];
                    
                    if (!empty($record[9]) and file_exists($start_picture) and !is_dir($start_picture)) {
                        $picture = '/tmp/' . substr($record[9], strrpos($record[9], '/') + 1);
                        $dst = $this->adaptPhoto($start_picture, 300, 225);
                        imagejpeg($dst, $picture);

                        $this->Rect($x + $this->sumUntil($colsw,$k), $cury, $colsw[$k], $rect_cellh, 'FD');
                        $this->Image($picture, $x + $this->sumUntil($colsw,$k) + 0.5, $cury + 0.5, $imgw, $imgh, 'jpg', '', 'C', true);
                    }
                    
                    continue;
                }
                
                if ($k == 0) {
                    $this->Rect($x, $cury, $colsw[0], $rect_cellh, 'FD');
                    $this->SetXY($x + 1, $cury + 1);                    
                } else {
                    $this->Rect($x + $this->sumUntil($colsw,$k), $cury, $colsw[$k], $rect_cellh, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k) + 1, $cury + 1);
                }

                $this->MultiCell($colsw[$k] - 1, $cellh, $record[$k], 0, 'C', false);
            }

            $cury = $cury + $rect_cellh;
            
            if (($c < count($inspections) - 1) and ($cury >= 144.75)) {
                $this->AddPage($this->CurOrientation);
                $cury = 25;
            }
            
            $this->SetXY($x, $cury);
        }
        
        if ($cury >= 175) {
            $this->AddPage($this->CurOrientation);
            $cury = 25;
        }
        
        if (25 != $cury) {
            $cury += 5;
            $this->SetXY($x, $cury);
        }
        
        $this->Rect($x, $cury, 50, 8, 'FD');
        $this->SetXY($x + 1, $cury + 1);
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);
        $this->MultiCell(48, 6, 'TO COLLECTORS', 0, 'C', false);
        
        $this->Rect($x + 50, $cury, 50, 8, 'FD');
        $this->SetXY($x + 51, $cury + 1);
        $this->MultiCell(48, 6, 'TIME', 0, 'C', false);
        
        $this->Rect($x + 100, $cury, 50, 8, 'FD');
        $this->SetXY($x + 101, $cury + 1);
        $this->MultiCell(48, 6, 'COST / ' . chr(163), 0, 'C', false);
        
        $cury += 8;
        
        $this->SetXY($x, $cury);
        
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $this->Rect($x, $cury, 50, 8, 'FD');
        $this->SetXY($x + 1, $cury + 1);
        $this->MultiCell(48, 6, 'Item ' . (($item_no < 10) ? ('0' . $item_no) : $item_no), 0, 'C', false);
        
        $this->Rect($x + 50, $cury, 50, 8, 'FD');
        
        $this->Rect($x + 100, $cury, 50, 8, 'FD');
        
        $cury += 8;

        $this->SetXY($x, $cury);
    }
    
    private function cleanText($text)
    {
        if (empty($text)) {
            return array();
        }
        
        $cleanText = $text;
        
        $em_dash = html_entity_decode('&#x2013;', ENT_COMPAT, 'UTF-8');
        $em_dash2 = html_entity_decode('&#8212;', ENT_COMPAT, 'UTF-8');
        
        $cleanText = trim($cleanText);
         
        $cleanText = str_replace("&nbsp;"," ",$cleanText);
        $cleanText = str_replace("’","'",$cleanText);
            
        $cleanText = str_replace($em_dash, "-", $cleanText);            
        $cleanText = str_replace($em_dash2, "-", $cleanText);
        $cleanText = str_replace("\u2014", "-", $cleanText);
        
        $broken = explode("<br>",$cleanText);
        
        for ($k = 0; $k < count($broken); $k++) {
            $broken[$k] = trim($broken[$k]);
            
            if (strpos($broken[$k],"<font") !== false) {
                $broken[$k] = str_replace("<font color=\"#365f91;\"><span style=\"font-weight:bold;\">","titlec|",$broken[$k]);
                $broken[$k] = str_replace("<font color=\"#365f91\"><span style=\"font-weight:bold;\">","titlec|",$broken[$k]);
                $broken[$k] = str_replace("</span></font>","",$broken[$k]);
                
                continue;
            }
            
            if ((strpos($broken[$k],"<span") !== false) and (strpos($broken[$k],"style") !== false)) {
                $broken[$k] = str_replace("<span style=\"font-weight:bold;\">","title|",$broken[$k]);
                $broken[$k] = str_replace("<span style=\"font-weight:bold\">","title|",$broken[$k]);
                $broken[$k] = str_replace("</span>","",$broken[$k]);
                
                continue;
            }
            
            if (strpos($broken[$k],"<span") !== false) {
                $broken[$k] = str_replace("<span>","",$broken[$k]);
                $broken[$k] = str_replace("</span>","",$broken[$k]);
                
                continue;
            }
            
            error_log('broken[' . $k . '] = ' . $broken[$k]);
            
            continue;
        }
        
        return $broken;
    }
    
    private function printElement($element)
    {
        $stds = $this->defineStandardFont();
        
        $curx = 12.50;
        $cury = $this->GetY();
        $this->SetXY($curx,$cury);
        
        $cell_width = 185;
        $br = $stds['font-size'] / 2;
        
        if ((('L' == $this->CurOrientation) and ($cury > 191 - $br)) or (('P' == $this->CurOrientation) and ($cury > 278 - $br))) {
            $this->AddPage($this->CurOrientation);

            $cury = 35;

            $this->SetXY($curx,$cury);
        }

        $low_limit = 275;
        
        if ('L' == $this->CurOrientation) {
            $low_limit = 191;
        }
        
        if (empty($element)) {
            $cury = $this->GetY();
            
            if (35 == $cury) {
                return max($this->GetY(),35);
            }
            
            $cury = $br + $this->GetY();
            $this->SetXY($curx,$cury);
            
            $remaining = ($low_limit - $cury - 5) / $br;
            
            if (($cury >= $low_limit) or ($remaining < 1)) {
                $this->AddPage($this->CurOrientation);

                $cury = 35;
                
                $this->SetXY($curx,$cury);
            }
            
            return max($this->GetY(),35);
        }
        
        if (strpos($element,"title|") !== false) {
            $cury = $this->GetY();
            
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->SetTextColor(0,0,0);
            
            $text_to_print = str_replace("title|","",$element);
            
            $this->MultiCell($cell_width - 2, $br, $text_to_print, 0, 'L', false);

            return max($this->GetY(),35);
        }
        
        if (strpos($element,"titlec|") !== false) {
            $cury = $this->GetY();
            
            $this->SetFont($stds['font-family'],'B',$stds['font-size']);
            $this->SetTextColor(54,95,145);
            
            $text_to_print = str_replace("titlec|","",$element);
            
            $this->MultiCell($cell_width - 2, $br, $text_to_print, 0, 'L', false);
            
            $this->SetTextColor(0,0,0);

            return max($this->GetY(),35);
        }
        
        if (strpos($element,"<ul>") !== false) {
            $text_to_print = str_replace("<ul>","",$element);
            $text_to_print = str_replace("</ul>","",$text_to_print);
            
            $text_to_print = str_replace("</li><li>","|",$text_to_print);
            $text_to_print = str_replace("<li>","",$text_to_print);
            $text_to_print = str_replace("</li>","",$text_to_print);
            
            $lis = explode("|",$text_to_print);
            
            $this->SetFont($stds['font-family'],'',$stds['font-size']);
            $this->SetTextColor(0,0,0);
            
            foreach ($lis as $li) {
                $curx = 15; 
                $cury = $this->GetY();
                
                $this->SetXY($curx,$cury);
                
                $this->MultiCell($cell_width - 2 - $curx, $br, chr(149) . ' ' . $li, 0, 'L', false);
            }
            
            return max($this->GetY(),30);
        }
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        $this->SetTextColor(0,0,0);
        
        $string_to_print = trim($element);

        while (!empty($string_to_print)) {
            if ((" " == $string_to_print) and (35 == $cury)) {
                break;
            }
            
            // tot lines from the current position to the end of the page
            $remaining = ($low_limit - $cury - 5) / $br;

            $maxline = min($remaining,$this->nbLines($cell_width, $string_to_print));

            $string_to_print = $this->MultiCell($cell_width, $br, $string_to_print, $stds['print_border'], 'L', true, $maxline);

            $cury = $this->GetY();
            
            $remaining = ($low_limit - $cury - 5) / $br;
            
            if (!empty($string_to_print) or ($remaining < 1)) {
                $this->AddPage($this->CurOrientation);

                $cury = 35;
                
                $this->SetXY($curx,$cury);
            }
        }
        
        return max($this->GetY(),35);
    }
    
    private function printHtmlText($html_text)
    {
        $clean = $this->cleanText($html_text);
        
        $y = 35;
        
        foreach ($clean as $clean_elem) {
            $y = $this->printElement($clean_elem);
        }
        
        return max($y,35);
    }
    
    private $removal_id;
    private $removal;
    private $removal_areas;
    private $removal_inspections;
    private $issues;
    private $currev_no;
    private $currev;
    
    protected $_toc = array();
    protected $_numbering = false;
    protected $_numberingFooter = false;
    protected $_numPageNum = 2;

    private $printHeader;
    private $printFooter;

    public function __construct($removal_id) 
    {
        parent::__construct();
        
	$this->printHeader = true;
	$this->printFooter = true;
        
        $this->removal_id = $removal_id;
        
        $this->removal = DB::table('removals')
                ->where('id','=', $this->removal_id)
                ->get()
                ->first();
        
        $this->issues = DB::table('removals')
                ->where('project_ref','=', $this->removal->project_ref)
                ->orderBy('preparation_date','asc')
                ->get()
                ->all();
        
        for ($n = 0; $n < count($this->issues); $n++) {
            if (!($this->issues[$n]->id == $this->removal_id)) {
                continue;
            }
            
            $this->currev_no = ($n + 1);
            $this->currev = $this->issues[$n];
            
            break;
        }
        
        $this->removal_areas = DB::table('removals_areas')
                ->where('removal_id','=', $this->removal_id)
                ->get()
                ->all();
        
        $this->removal_inspections = array();
        
        foreach ($this->removal_areas as $curarea) {
            $this->removal_inspections[$curarea->id] = DB::table('removals_inspections')
                ->where('area_id','=', $curarea->id)
                ->get()
                ->all();
        }
    }
    
    public function getElements()
    {
        return array(
            'removal'       =>  $this->removal,
            'areas'         =>  $this->removal_areas,
            'inspections'   =>  $this->removal_inspections,
            'revision_no'   =>  $this->currev_no,
            'revision'      =>  $this->currev,
            'issues'        =>  $this->issues,
        );
    }
    
    public function defineStandardFont()
    {
        $pdf_standards = array(
            'font-family'       =>  'Calibri',
            'font-iperbig'      =>  '22',
            'font-bigger'       =>  '20',
            'font-big'          =>  '16',
            'font-medium'       =>  '15',
            'font-average'      =>  '14',
            'font-title'        =>  '13',
            'font-ipersize'     =>  '12',
            'font-size'         =>  '11',
            'font-mini'         =>  '10',
            'font-mini-small'   =>  '9',
            'font-table'        =>  '8',
            'font-style'        =>  '',
            'paper-format'      =>  'A4',
            'paper-orientation' =>  'P',
            'paper-unit'        =>  'mm',
            'cell_height'       =>  '5',
            'print_border'      =>  0,
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
        $this->setPrintHeader(true);
        
        $this->AddPage();
        
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
        
        if ($this->page == 1) {
	    $picture = public_path() . '/img/coverpage-fra.jpg';

	    $imgw = 210;
	    $imgh = 297;

            $this->Image($picture, 0, 0, $imgw, $imgh);

            return;
        }

        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $x = 12.50;
        $y = 12.50;
        
        $this->SetXY($x, $y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $sentence = str_replace("\n"," ",$elems['removal']->address);
        $sentence .= "\n";
        $sentence .= 'Report Revision: ' . $elems['revision_no'];
        
        $this->MultiCell(115,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
        
        $x = 130;
        
        if ($this->CurOrientation == 'L') {
            $x = 217;
        }
        
        $this->SetXY($x, $y);
        
        $sentence = 'Tender document to be read in full';
        $this->MultiCell(60,$stds['cell_height'], $sentence, $stds['print_border'], 'R', false);
    }
    
    public function Footer()
    {
	if (!$this->printFooter) {
            return;
        }
        
        $page = $this->PageNo();
        
        if ($page == 1) {
            return;
        }
	
	$stds = $this->defineStandardFont();
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetTextColor(0,0,0);
        
        $x = 12.50;
        $y = 278;
        
        if ($this->CurOrientation == 'L') {
            $y = 191; 
        }
        
        $this->SetXY($x, $y);
        
        $sentence = 'DER001 - Removal Specification';
        $sentence .= "\n";
        $sentence .= 'Template Revision: 01 - Date: 010815';
        
        $this->MultiCell(160,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
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
        
        $this->setTitle(ucwords('Project Reference ' . $elems['removal']->project_ref),true);
        
        $this->setPrintHeader(true);
        $this->AddPage();
        
        $this->startPageNums();
        
        $this->SetFont($stds['font-family'],'',$stds['font-bigger']);
        $this->setTextColor(255,255,255);
        
        $x = 31;        
        $y = 50;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Asbestos Removal Specification & Tender';
        
        $this->MultiCell(162,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-big']);
        
        $y += 10;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Area: ' . $elems['removal']->area;
        
        $this->MultiCell(162,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
        
        $y += 10;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Address: ' . $elems['removal']->address;
        
        $this->MultiCell(162,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
        
        $this->SetFont($stds['font-family'],'',$stds['font-size']);
        
        $y += 25;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Document Prepared for ' . $elems['removal']->prepared_for;
        
        $this->MultiCell(162,$stds['cell_height'], $sentence, $stds['print_border'], 'L', false);
        
        $y += 10;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Project ref ' . $elems['removal']->project_ref;
        
        $this->MultiCell(162,$stds['cell_height'], $sentence, $stds['print_border'], 'R', false);
        
	$this->setPrintFooter(false);
    }
    
    public function printDocumentHistory()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        
        $this->SetFont($stds['font-family'],'B',$stds['font-size']);
        $this->setTextColor(0,0,0);
        
        $x = 12.50;        
        $y = 30;
        
        $this->SetXY($x, $y);
        
        $sentence = 'Document History';
        
        $this->MultiCell(162,$stds['cell_height'],$sentence,$stds['print_border'],'L',false);
        
        $cellh = 7;
        $rect_width = 48.66;
        $rect_height = 2 + $cellh;
        
        $x = 32.01;
        $y = 5 + $this->GetY();
        
        $this->SetXY($x, $y);
        
        $headers = array(
            'Revision Date',
            'Revision',
            'Comment',
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        $align = 'C';
        
        for ($k = 0; $k < count($headers); $k++) {
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $headers[$k], 0, $align, false);
        }      
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
        foreach ($elems['issues'] as $n => $issue) {
            if ($n > $elems['revision_no'] - 1) {
                continue;
            }
            $y += $rect_height;
            
            $this->SetXY($x, $y);
            
            $prepdate = date('d/m/Y',strtotime($issue->preparation_date));
            $rev = ($n + 1);
            if ($rev < 10) {
                $rev = '0' . $rev;
            }
            
            $comm = (empty($issue->comments) ? '' : $issue->comments);
            
            $this->Rect($x, $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $prepdate, 0, $align, false);
            
            $this->Rect($x + (1 * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + (1 * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $rev, 0, $align, false);
            
            $this->Rect($x + (2 * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + (2 * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $comm, 0, $align, false);
        }
        
        /**** PRFEPARED BY TABLE ****/
        
        $y += 10 + $rect_height;
        
        $this->SetXY($x, $y);
        
        $headers = array(
            'Prepared by',
            'Signed',
            'Date',
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
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
        
        $rect_height = 26.25;

        $prepdate = date('d/m/Y',strtotime($elems['revision']->preparation_date));

        $this->Rect($x, $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + 1, $y + 1); 
        $this->MultiCell($rect_width - 2, $cellh, $elems['revision']->prepared_by, 0, $align, false);

        $this->Rect($x + (1 * $rect_width), $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + (1 * $rect_width) + 1, $y + 1); 
        if (!empty($elems['revision']->prepared_by_signature_path)) {
            $fullpath = public_path() . '/removals' . $elems['revision']->prepared_by_signature_path;
            
            if (file_exists($fullpath) and !is_dir($fullpath)) {
                $this->Image($fullpath, $x + (1 * $rect_width) + (($rect_width - $imgw) / 2), $y + (($rect_height - $imgh) / 2), $imgw, $imgh);
            }
        }

        $this->Rect($x + (2 * $rect_width), $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + (2 * $rect_width) + 1, $y + 1); 
        $this->MultiCell($rect_width - 2, $cellh, $prepdate, 0, $align, false);     
        
        /**** APPROVED BY TABLE ****/
        
        $y += $rect_height;
        
        $this->SetXY($x, $y);
        
        $rect_height = 2 + $cellh;
        
        $headers = array(
            'Approved By',
            'Signed',
            'Date',
        );
        
        $this->SetFont($stds['font-family'],'B',$stds['font-mini']);
        $this->SetFillColor(217,217,217);
        $this->SetDrawColor(0,0,0);
        
        for ($k = 0; $k < count($headers); $k++) {
            $this->Rect($x + ($k * $rect_width), $y, $rect_width, $rect_height, 'FD');
            $this->SetXY($x + ($k * $rect_width) + 1, $y + 1); 
            $this->MultiCell($rect_width - 2, $cellh, $headers[$k], 0, $align, false);
        }
        
        $y += $rect_height;
        
        $this->SetXY($x,$y);
        
        $rect_height = 26.25;
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        
        $approval_date = date('d/m/Y',strtotime($elems['revision']->approval_date));

        $this->Rect($x, $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + 1, $y + 1); 
        $this->MultiCell($rect_width - 2, $cellh, $elems['revision']->approved_by, 0, $align, false);

        $this->Rect($x + (1 * $rect_width), $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + (1 * $rect_width) + 1, $y + 1); 
        if (!empty($elems['revision']->approved_by_signature_path)) {
            $fullpath = public_path() . '/removals' . $elems['revision']->approved_by_signature_path;
            
            if (file_exists($fullpath) and !is_dir($fullpath)) {
                $this->Image($fullpath, $x + (1 * $rect_width) + (($rect_width - $imgw) / 2), $y + (($rect_height - $imgh) / 2), $imgw, $imgh);
            }
        }

        $this->Rect($x + (2 * $rect_width), $y, $rect_width, $rect_height, 'FD');
        $this->SetXY($x + (2 * $rect_width) + 1, $y + 1); 
        $this->MultiCell($rect_width - 2, $cellh, $approval_date, 0, $align, false);       
        
        $this->setPrintFooter(true);
    }
    
    public function printPreliminaries()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Preliminaries', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('PRELIMINARIES',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $sentence = $elems['removal']->preliminaries;
        
        $this->printHtmlText($sentence);
        
        $this->setPrintFooter(true);
    }
    
    public function printSiteLocation()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Site Location', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $cell_width_l = 272;
        $page_height_l = 160;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('SITE LOCATION',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'Site Address';
        
        $this->MultiCell($cell_width, $cell_height, $sentence, 0, 'L', true);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $this->MultiCell($cell_width, $cell_height - 2, $elems['removal']->address, 0, 'L', true);
        
        $nlines = count(explode("\n",$elems['removal']->address));
        
        $y += 3 + ($cell_height * $nlines);
        
        $this->SetXY($x, $y);
        
        $fullpath = public_path() . '/removals' . $elems['removal']->site_picture_path;
        
        if (!empty($elems['removal']->site_picture_path) and file_exists($fullpath) and !is_dir($fullpath)) {
            list($imgw, $imgh) = getimagesize($fullpath);
            
            if ($imgw > $cell_width) {
                $new_imgh = ($cell_width * $imgh) / $imgw;
                $imgw = $cell_width;
                $imgh = $new_imgh;
            }
            
            if ($imgh > $page_height_l) {
                $new_imgw = ($page_height_l * $imgw) / $imgh;
                $imgw = $new_imgw;
                $imgh = $page_height_l;
            }
            
            $this->Image($fullpath, $x, $y, $imgw, $imgh, 'jpg', '', 'C', true);
        }
        
        $fullpath = public_path() . '/removals' . $elems['removal']->map_picture_path;
        
        if (!empty($elems['removal']->map_picture_path) and file_exists($fullpath) and !is_dir($fullpath)) {
            list($imgw, $imgh) = getimagesize($fullpath);
            
            if ($imgw > $cell_width_l) {
                $new_imgh = ($cell_width_l * $imgh) / $imgw;
                $imgw = $cell_width_l;
                $imgh = $new_imgh;
            }
            
            if ($imgh > $page_height_l) {
                $new_imgw = ($page_height_l * $imgw) / $imgh;
                $imgw = $new_imgw;
                $imgh = $page_height_l;
            }
            
            $this->AddPage('L');
            
            $x = 12.50;        
            $y = 25;
            
            $this->SetXY($x, $y);
            
            $this->Image($fullpath, $x, $y, $imgw, $imgh, 'jpg', '', 'C', true);
        }
        
        $this->setPrintFooter(true);
    }
    
    public function printSpecifications()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $cell_width = 272;
        $cell_height = 7;
        
        $this->setPrintHeader(true);
        
        foreach ($elems['areas'] as $n => $myarea) {
            $this->AddPage('L');
            $this->TOC_Entry($myarea->name, 0);
            
            $x = 12.50;        
            $y = 25;
            
            $this->SetXY($x, $y);
            
            $this->printRedBar($myarea->name,$x,$y,$cell_width,$cell_height);
            
            $y += 3 + $cell_height;
        
            $this->SetXY($x, $y);
            
            $sentence = $myarea->text;

            $y = $this->printHtmlText($sentence);
            
            $this->printInspectionsTable($elems['inspections'][$myarea->id],$n+1,$y);
        }
        
        $this->setPrintFooter(true);
    }
    
    public function printFloorPlans()
    {
        $elems = $this->getElements();
        
        $stds = $this->defineStandardFont();
        
        $fullpath = public_path() . '/removals' . $elems['removal']->floor_plans_path;
        
        if (!empty($elems['removal']->floor_plans_path) and file_exists($fullpath) and !is_dir($fullpath)) {
            $this->setPrintHeader(true);
            $this->AddPage();
            $this->TOC_Entry('Floor Plans', 0);
            
            $x = 12.50;        
            $y = 25;
            $cell_width = 185;
            $cell_height = 7;
            
            $this->SetXY($x, $y);
            
            $this->printRedBar('SUPPORTING FLOOR PLANS',$x,$y,$cell_width,$cell_height);
            
            $y += 3 + $cell_height;
        
            $this->SetXY($x, $y);
            
            $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
            $sentence = 'The following floor plans have been produced to support the information provided in the specification sections and form part of those specifications.';
            
            $this->MultiCell($cell_width, $cell_height - 2, $sentence, 0, 'L', true);
            
            $parts = explode('/',$elems['removal']->floor_plans_path);
            $last = $parts[count($parts)-1];
            
            $myfile = '/tmp/' . $last;
            shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . $myfile . ' ' . $fullpath); 

            $this->SetTextColor(0,0,0);

            $pageCount = $this->setSourceFile($myfile);

            $this->setPrintHeader(false);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
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
            }
            
            unlink($myfile);
        }
        
        $fullpath = public_path() . '/removals' . $elems['removal']->access_routes_path;
        
        if (!empty($elems['removal']->include_access_routes) and !empty($elems['removal']->access_routes_path) and file_exists($fullpath) and !is_dir($fullpath)) {
            $this->setPrintHeader(true);
            $this->AddPage();
            $this->TOC_Entry('Access Routes', 0);
            
            $x = 12.50;        
            $y = 25;
            $cell_width = 185;
            $cell_height = 7;
            
            $this->SetXY($x, $y);
            
            $this->printRedBar('WORK AREAS',$x,$y,$cell_width,$cell_height);
            
            $y += 3 + $cell_height;
        
            $this->SetXY($x, $y);
            
            $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
            $sentence = 'The following floor plan shows the agreed access routes to and from the work area which are to be utilised during the contract. ';
            $sentence .= 'Any other routes proposed must be agreed in advance with the Client and Derisk.';
            
            $this->MultiCell($cell_width, $cell_height - 2, $sentence, 0, 'L', true);
            
            $parts = explode('/',$elems['removal']->access_routes_path);
            $last = $parts[count($parts)-1];
            
            $myfile = '/tmp/' . $last;
            shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . $myfile . ' ' . $fullpath); 

            $this->SetTextColor(0,0,0);

            $pageCount = $this->setSourceFile($myfile);

            $this->setPrintHeader(false);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
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
            }
            
            unlink($myfile);
        }
    }
    
    public function printBulkCertificates()
    {
        $elems = $this->getElements();
        
        $stds = $this->defineStandardFont();
        
        $fullpath = public_path() . '/removals' . $elems['removal']->bulk_analysis_certificate_path;
        if (empty($elems['removal']->bulk_analysis_certificate_path) or !file_exists($fullpath) or is_dir($fullpath)) {
            return;
        }
        
        $this->setPrintHeader(true);
        $this->AddPage();
        $this->TOC_Entry('Certificates of Analysis', 0);

        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;

        $this->SetXY($x, $y);

        $this->printRedBar('BULK ANALYSIS CERTIFICATES',$x,$y,$cell_width,$cell_height);

        $y += 3 + $cell_height;

        $this->SetXY($x, $y);

        $this->SetFont($stds['font-family'],'',$stds['font-mini']);

        $sentence = 'The following are extracts from the original survey reports and sampling exercises to confirm the ACMs present. ';
        $sentence .= 'These may include samples not included within this specification where they fall outside the scope but were ';
        $sentence .= 'within the original remit of the survey.';

        $this->MultiCell($cell_width, $cell_height - 2, $sentence, 0, 'L', true);
        
        $parts = explode('/',$elems['removal']->bulk_analysis_certificate_path);
        $last = $parts[count($parts)-1];

        $myfile = '/tmp/' . $last;
        shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=' . $myfile . ' ' . $fullpath);

        $this->SetTextColor(0,0,0);
        
        $pageCount = $this->setSourceFile($myfile);

	$this->setPrintHeader(false);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
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
        }
        
        unlink($myfile);
    }
    
    public function printFormOfTender()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Form of Tender', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('FORM OF TENDER',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'We agree to undertake the works specified within this document for the ';
        $sentence .= 'following price and timescales stated against each item. We acknowledge that ';
        $sentence .= 'instruction may be for some or all of the works itemised.';
        
        $this->MultiCell($cell_width, $cell_height - 2, $sentence, 0, 'L', true);
        
        $y += 2 * $cell_height;
        
        $this->SetXY($x, $y);
        
        $headers = array(
            'ITEM',
            'TIMESCALE',
            'COST / ' . chr(163),
        );
        
        $colsw = array(
            50.0,
            50.0,
            50.0,
        );
        
        $this->SetTextColor(0,0,0);
        $this->SetFillColor(217,217,217);
        $this->SetFont($stds['font-family'],'B',$stds['font-table']);

        for ($k = 0; $k < count($headers); $k++) {
            if ($k == 0) {
                $this->Rect($x, $y, $colsw[0], $cell_height, 'FD');
                $this->SetXY($x + 1, $y + 1);                    
            } else {
                $this->Rect($x + $this->sumUntil($colsw,$k,-1), $y, $colsw[$k], $cell_height, 'FD');
                $this->SetXY($x + $this->sumUntil($colsw,$k,-1) + 1, $y + 1);
            }

            $this->MultiCell($colsw[$k] - 2, $cell_height - 2, $headers[$k], 0, 'C', false);
        }
        
        $y += $cell_height;
        
        $this->SetXY($x, $y);
        
        $this->SetFillColor(255,255,255);
        $this->SetFont($stds['font-family'],'',$stds['font-table']);
        
        $rows = array(
            array(
                'Site Setup and Welfare',
                '',
                '',
            ),
            array(
                'Equipment (If required)',
                '',
                '',
            ),
        );
        
        foreach ($elems['areas'] as $n => $myarea) {
            $rows[] = array(
                'Item ' . (($n < 9) ? ('0' . ($n + 1)) : ($n + 1)),
                '',
                '',
            );
        }
        
        foreach ($rows as $c => $row) {
            for ($k = 0; $k < count($row); $k++) {
                if ($k == 0) {
                    $this->Rect($x, $y, $colsw[0], $cell_height, 'FD');
                    $this->SetXY($x + 1, $y + 1);                    
                } else {
                    $this->Rect($x + $this->sumUntil($colsw,$k,-1), $y, $colsw[$k], $cell_height, 'FD');
                    $this->SetXY($x + $this->sumUntil($colsw,$k,-1) + 1, $y + 1);
                }

                $this->MultiCell($colsw[$k] - 2, $cell_height - 2, $row[$k], 0, 'C', false);
            }
            
            $y += $cell_height;
        
            $this->SetXY($x, $y);
            
            if (($c < count($rows) - 1) and ($y >= 270)) {
                $this->AddPage($this->CurOrientation);
                $y = 35;
                $this->SetXY($x, $y);
            }
        }
        
        $y += $cell_height;
        $this->SetXY($x, $y);
        
        if ($y >= 209) {
            $this->AddPage($this->CurOrientation);
            $y = 35;
            $this->SetXY($x, $y);
        } 
        
        $colsw = array(
            30,
            150,
        );
        
        $colsh = array(
            15,
            7,
            7,
            30,
            7,
        );
        
        $rows = array(
            array(
                'Signed',
                '',
            ),
            array(
                'Name',
                '',
            ),
            array(
                'Company',
                '',
            ),
            array(
                'Address',
                '',
            ),
            array(
                'Date',
                '',
            ),
        );
        
        foreach ($rows as $n => $row) {
            $curx = $x;
            
            for ($k = 0; $k < count($row); $k++) {
                $fillColor = array(255,255,255);
                $bold = '';
                
                if ($k == 0) {
                    $fillColor = array(217,217,217);
                    $bold = 'B';
                }
                
                $this->SetFillColor($fillColor[0],$fillColor[1],$fillColor[2]);
                $this->SetFont($stds['font-family'],$bold,$stds['font-table']);               
                
                $this->Rect($curx, $y, $colsw[$k], $colsh[$n], 'FD');
                $this->SetXY($curx + 1, $y + 1);

                $this->MultiCell($colsw[$k] - 2, $colsh[$n] - 2, $row[$k], 0, 'L', false);
                
                $curx += $colsw[$k];
            }
            
            $y += $colsh[$n];
        
            $this->SetXY($curx, $y);
        }
        
        $this->setPrintFooter(true);
    }
    
    public function printGeneralRequirements()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Asbestos Removal Contractor - General Requirements', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('ASBESTOS REMOVAL CONTRACTOR - GENERAL REQUIREMENTS',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $sentence = $elems['removal']->general_requirements;
        
        $this->printHtmlText($sentence);
        
        $this->setPrintFooter(true);
    }
    
    public function printAnalysisOfTenderSubmission()
    {
        $stds = $this->defineStandardFont();
        
        $elems = $this->getElements();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Analysis of Tender Submission', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('ANALYSIS OF TENDER SUBMISSION',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $sentence = $elems['removal']->tender_submission;
        
        $this->printHtmlText($sentence);
        
        $this->setPrintFooter(true);
    }
    
    public function printNonCollusionAndNonCorruption()
    {
        $stds = $this->defineStandardFont();
        
        $this->setPrintHeader(true);
        
        $this->AddPage();
        $this->TOC_Entry('Non Collusion and Non Corruption', 0);
        
        $x = 12.50;        
        $y = 25;
        $cell_width = 185;
        $cell_height = 7;
        
        $this->SetXY($x, $y); 
        
        $this->printRedBar('NON COLLUSION AND NON-CORRUPTION',$x,$y,$cell_width,$cell_height);
        
        $y += 3 + $cell_height;
        
        $this->SetXY($x, $y);
        
        $this->SetFont($stds['font-family'],'',$stds['font-mini']);
        
        $sentence = 'The essence of selective tendering is that the client shall receive bona fide competitive tenders from all those tendering. ';
        $sentence .= 'In recognition of this principle, we certify that this is a bona fide tender, intended to be competitive, and that we have not fixed ';
        $sentence .= 'or adjusted the amount of the tender by or under or in accordance with any agreement or arrangement with any other person. ';
        $sentence .= 'We also certify that we have not done and we undertake that we will not do at any time before the hour and date specified for the return ';
        $sentence .= 'of this tender any of the following acts:';
        
        $this->MultiCell($cell_width - 2, $cell_height - 2, $sentence, 0, 'L', false);
        
        $y += 3 + 5 * ($cell_height - 2);
        
        $this->SetXY($x + 12.50, $y);
        
        $sentence = '1. Communicating to a person other than the person calling for those tender the amount or approximate amount of the proposed tender, ';
        $sentence .= 'except where the disclosure, in confidence of the approximate amount of the tender was necessary to obtain insurance premium quotations ';
        $sentence .= 'required for the preparation of the tender.';
        
        $this->MultiCell($cell_width - 14.50, $cell_height - 2, $sentence, 0, 'L', false);
        
        $y += 3 + 3 * ($cell_height - 2);
        
        $this->SetXY($x + 12.50, $y);
        
        $sentence = '2. Entering into any agreement or arrangement with any other person that he shall refrain from tendering or as to the amount of any tender to be submitted.';
        
        $this->MultiCell($cell_width - 14.50, $cell_height - 2, $sentence, 0, 'L', false);
        
        $y += 3 + 2 * ($cell_height - 2);
        
        $this->SetXY($x + 12.50, $y);
        
        $sentence = '3. Offering or paying or giving or agreement to pay or give any sum of money or valuable consideration directly or indirectly to any person for doing ';
        $sentence .= 'or having done or causing or have caused to be done in relation to any other tender or proposed tender for the said work any act or thing of the sort described above.';
        
        $this->MultiCell($cell_width - 14.50, $cell_height - 2, $sentence, 0, 'L', false);
        
        $y += 3 + 3 * ($cell_height - 2);
        
        $this->SetXY($x, $y);
        
        $sentence = 'In this certificate, the word "person" includes any persons and anybody of association, corporate or unincorporated; and "any agreement or arrangement" ';
        $sentence .= 'includes any such transaction, formal or informal, and whether legally binding or not.';
        
        $this->MultiCell($cell_width - 2, $cell_height - 2, $sentence, 0, 'L', false);
        
        $y += 10 + 2 * ($cell_height - 2);
        
        $this->SetXY($x + 25.0, $y);
        
        $this->MultiCell(25.0, $cell_height - 2, 'Signed:', 0, 'L', false);
        $this->Line($x + 50.0,$y + ($cell_height - 3),$x + 150.0,$y + ($cell_height - 3));
        
        $y += 3 + ($cell_height - 2);
        $this->SetXY($x + 25.0, $y);
        
        $this->MultiCell(25.0, $cell_height - 2, 'Print:', 0, 'L', false);        
        $this->Line($x + 50.0,$y + ($cell_height - 3),$x + 150.0,$y + ($cell_height - 3));
        
        $y += 3 + ($cell_height - 2);
        $this->SetXY($x + 25.0, $y);        
        
        $this->MultiCell(25.0, $cell_height - 2, 'On behalf of:', 0, 'L', false);        
        $this->Line($x + 50.0,$y + ($cell_height - 3),$x + 150.0,$y + ($cell_height - 3));
        
        $y += 3 + ($cell_height - 2);
        $this->SetXY($x + 25.0, $y);
        
        $this->MultiCell(25.0, $cell_height - 2, 'Position:', 0, 'L', false);
        $this->Line($x + 50.0,$y + ($cell_height - 3),$x + 150.0,$y + ($cell_height - 3));
        
        $y += 3 + ($cell_height - 2);
        $this->SetXY($x + 25.0, $y);
        
        $this->MultiCell(25.0, $cell_height - 2, 'Date:', 0, 'L', false);
        $this->Line($x + 50.0,$y + ($cell_height - 3),$x + 150.0,$y + ($cell_height - 3));
        
        $this->setPrintFooter(true);
    }
    
    public function printLastPage()
    {
        $this->setPrintHeader(false);
        
        $this->AddPage();
        
        $picture = public_path() . '/img/lastpage.jpg';

        $imgw = 210;
        $imgh = 297;

        $this->Image($picture, 0, 0, $imgw, $imgh);
        
        $this->setPrintFooter(false);
        
        $this->stopPageNums();
    }
}

class RemovalsPdfController extends Controller
{
    public function index()
    {
        
    }
    
    public function printReport(Request $request)
    {
        $removal_id = $request->input('removal_id');
        
        $pdf = new RemovalPDF($removal_id);
        
        $elems = $pdf->getElements();
        
        $pdf->AliasNbPages();
        
        // Cover Page
        $pdf->printCoverPage();
        
        // Document History
	$pdf->printDocumentHistory();
        
        // Preliminaries
	$pdf->printPreliminaries();
        
        // Site Location
        $pdf->printSiteLocation();
        
        // Specification for each area
        $pdf->printSpecifications();
        
        // Floor Plans
        $pdf->printFloorPlans();
        
        // Bulk Certificates
        $pdf->printBulkCertificates();
        
        // Form of Tender
        $pdf->printFormOfTender();
        
        // General Requirements
        $pdf->printGeneralRequirements();
        
        // Analysis of Tender Submission
        $pdf->printAnalysisOfTenderSubmission();
        
        // Non collusion and non-corruption
        $pdf->printNonCollusionAndNonCorruption();
        
        // Last Page        
        $pdf->printLastPage();

        $path_pdf = public_path() . '/reports/' . $elems['removal']->project_ref . '.pdf';
   
        $pdf->insertTOC(3);
        
        $pdf->Output($path_pdf,'F');
        
        return $elems['removal']->project_ref . '.pdf';
    }
}
