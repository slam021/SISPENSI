<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CoreCandidate;
use App\Models\FinancialFlow;
use App\Models\CoreTimses;
use App\Models\FinancialCategory;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class RecapitulationReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        if(!$start_month = Session::get('start_month')){
            $start_month = date('m');
        }else{
            $start_month = Session::get('start_month');
        }
        if(!$end_month = Session::get('end_month')){
            $end_month = date('m');
        }else{
            $end_month = Session::get('end_month');
        }
        if(!$year = Session::get('year')){
            $year = date('Y');
        }else{
            $year = Session::get('year');
        }

        $monthlist = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );

        $year_now 	=	date('Y');
        for($i=($year_now-2); $i<($year_now+2); $i++){
            $yearlist[$i] = $i;
        } 

        if(!Session::get('start_date')){
            $start_date     = date('Y-m-d');
        }else{
            $start_date = Session::get('start_date');
        }
        if(!Session::get('end_date')){
            $end_date     = date('Y-m-d');
        }else{
            $end_date = Session::get('end_date');
        }

        $listfinancialcategory = FinancialCategory::where('data_state', '=', 0)
        ->pluck('financial_category_name', 'financial_category_id');

        $listcoretimses = CoreTimses :: where('data_state', 0)
        ->get()
        ->pluck('timses_name', 'timses_id');

        $listcorecandidate = CoreCandidate :: where('data_state', 0)
        ->get()
        ->pluck('candidate_full_name', 'candidate_id');
    
        $financialflow = FinancialFlow::where('financial_flow.data_state', '=', 0)
        // ->join('financial_category', 'financial_flow.financial_category_id', '=', 'financial_category.financial_category_id')
        
        // ->where('financial_category.data_state', '=', 0)
        // ->where('financial_flow.financial_category_id', $financial_category_id)
        // ->where('financial_flow.candidate_id', $candidate_id)
        // ->where('financial_flow.timses_id', $timses_id)
        // ->where('financial_flow.financial_flow_date','>=',$start_date)
        // ->where('financial_flow.financial_flow_date','<=',$end_date)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year);
        // ->first();

        $financial_category_id = Session::get('financial_category_id');
        $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');
        
        if($financial_category_id||$financial_category_id!=null||$financial_category_id!=''){
            $financialflow   = $financialflow->where('financial_category_id', $financial_category_id);
        }
        if($candidate_id||$candidate_id!=null||$candidate_id!=''){
            $financialflow   = $financialflow->where('candidate_id', $candidate_id);
        }
        if($timses_id||$timses_id!=null||$timses_id!=''){
            $financialflow   = $financialflow->where('timses_id', $timses_id);
        }
        $financialflow   = $financialflow->get();

        return view('content.RecapitulationReport_view.ReportRecapitulation', compact('start_month','end_month', 'monthlist', 'year' , 'yearlist', 'year_now', 'listfinancialcategory', 'financial_category_id', 'timses_id', 'candidate_id', 'listcoretimses', 'listcorecandidate', 'financialflow', 'start_date', 'end_date'));
    }

    public function filterRecapitulationReport(Request $request){
        $start_month  = $request->start_month;
        $end_month    = $request->end_month;
        $start_date  = $request->start_date;
        $end_date    = $request->end_date;
        $year         = $request->year;
        $timses_id    = $request->timses_id;
        $candidate_id = $request->candidate_id;
        $financial_category_id = $request->financial_category_id;

        Session::put('timses_id', $timses_id);
        Session::put('financial_category_id', $financial_category_id);
        Session::put('candidate_id', $candidate_id);
        Session::put('start_month',$start_month);
        Session::put('end_month',$end_month);
        Session::put('start_date',$start_date);
        Session::put('end_date',$end_date);
        Session::put('year',$year);
// dd($request->all());
        return redirect('/report-recap');
    }

    public function filterResetRecapitulationReport(){
        Session::put('start_month');
        Session::put('end_month');
        Session::put('start_date');
        Session::put('end_date');
        Session::put('year');
        Session::put('year');
        Session::forget('timses_id');
        Session::forget('candidate_id');
        Session::forget('financial_category_id');

        return redirect('/report-recap');
    }

    public function getCategoryName($financial_category_id){
        $data = FinancialCategory::where('financial_category_id',$financial_category_id)
        ->first();

        return $data['financial_category_name'];
    }

    public function getTimsesName($timses_id){
        $data = CoreTimses::where('timses_id', $timses_id)
        ->first();

        return $data['timses_name'];
    }

    public function getCandidateName($candidate_id){
        $data = CoreCandidate::where('candidate_id',$candidate_id)
        ->first();

        return $data['candidate_full_name'];
    }

    public function printLedgerReport()
    {
        if(!$start_month = Session::get('start_month')){
            $start_month = date('m');
        }else{
            $start_month = Session::get('start_month');
        }
        if(!$end_month = Session::get('end_month')){
            $end_month = date('m');
        }else{
            $end_month = Session::get('end_month');
        }
        if(!$year = Session::get('year')){
            $year = date('Y');
        }else{
            $year = Session::get('year');
        }

        $monthlist = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );

        $year_now 	=	date('Y');
        for($i=($year_now-2); $i<($year_now+2); $i++){
            $yearlist[$i] = $i;
        } 
    
        $financialflow = FinancialFlow::where('financial_flow.data_state', '=', 0)
        // ->join('financial_category', 'financial_flow.financial_category_id', '=', 'financial_category.financial_category_id')
        
        // ->where('financial_category.data_state', '=', 0)
        // ->where('financial_flow.financial_category_id', $financial_category_id)
        // ->where('financial_flow.candidate_id', $candidate_id)
        // ->where('financial_flow.timses_id', $timses_id)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year);
        // ->first();

        $financial_category_id = Session::get('financial_category_id');
        $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');
        
        if($financial_category_id||$financial_category_id!=null||$financial_category_id!=''){
            $financialflow   = $financialflow->where('financial_category_id', $financial_category_id);
        }
        if($candidate_id||$candidate_id!=null||$candidate_id!=''){
            $financialflow   = $financialflow->where('candidate_id', $candidate_id);
        }
        if($timses_id||$timses_id!=null||$timses_id!=''){
            $financialflow   = $financialflow->where('timses_id', $timses_id);
        }
        $financialflow   = $financialflow->get();

        //-----------TCPF-----------
        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(10, 10, 10, 10); // put space of 10 on top

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('helvetica', 'B', 20);

        $pdf::AddPage();

        $pdf::SetFont('helvetica', '', 8);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">LAPORAN REKAPITULASI</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size:12px\">PERIODE : ".$monthlist[$start_month]." - ".$monthlist[$end_month] ." ".$year."</div></td>
            </tr>
        </table>
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');

        if ($financial_category_id == null ){
            $category_name = '-';
        }else{
            $category_name = $this->getCategoryName($financial_category_id);
        }

        if ($candidate_id == null ){
            $candidate_name = '-';
        }else{
            $candidate_name = $this->getCandidateName($candidate_id);
        }

        if ($timses_id == null ){
            $timses_name = '-';
        }else{
            $timses_name = $this->getTimsesName($timses_id);
        }

        $tbl = "
        <br>
        <br>
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
            <tr>
                <td width=\"20%\"><div style=\"text-align: lef=ft; font-size:12px;font-weight: bold\">Kategori</div></td>
                <td width=\"5%\"><div style=\"text-align: center; font-size:12px; font-weight: bold\">:</div></td>
                <td width=\"65%\"><div style=\"text-align: left; font-size:12px; font-weight: bold\">".$category_name."</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: lef=ft; font-size:12px;font-weight: bold\">Kandidate</div></td>
                <td width=\"5%\"><div style=\"text-align: center; font-size:12px; font-weight: bold\">:</div></td>
                <td width=\"65%\"><div style=\"text-align: left; font-size:12px; font-weight: bold\">".$candidate_name."</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: lef=ft; font-size:12px;font-weight: bold\">Timses</div></td>
                <td width=\"5%\"><div style=\"text-align: center; font-size:12px; font-weight: bold\">:</div></td>
                <td width=\"65%\"><div style=\"text-align: left; font-size:12px; font-weight: bold\">".$timses_name."</div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: lef=ft; font-size:12px;font-weight: bold\">Posisi Saldo</div></td>
                <td width=\"5%\"><div style=\"text-align: center; font-size:12px; font-weight: bold\">:</div></td>
                <td width=\"65%\"><div style=\"text-align: left; font-size:12px; font-weight: bold\"></div></td>
            </tr>
            <tr>
                <td width=\"20%\"><div style=\"text-align: lef=ft; font-size:12px;font-weight: bold\">Saldo Awal</div></td>
                <td width=\"5%\"><div style=\"text-align: center; font-size:12px; font-weight: bold\">:</div></td>
                <td width=\"65%\"><div style=\"text-align: left; font-size:12px; font-weight: bold\"></div></td>
            </tr>
        </table>";
        $pdf::writeHTML($tbl, true, false, false, false, '');
        

        $no = 1;
        $tblStock1 = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
            <tr>
                <td width=\"4%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">No</div></td>
                <td width=\"10%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Tanggal</div></td>
                <td width=\"15%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Kandidat</div></td>
                <td width=\"15%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Timses</div></td>
                <td width=\"14%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Pemasukan</div></td>
                <td width=\"14%\" rowspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Pengeluaran </div></td>
                <td width=\"28%\" colspan=\"2\"><div style=\"text-align: center; font-weight: bold\">Saldo </div></td>
            </tr>
            
            <tr>
                <td width=\"14%\"><div style=\"text-align: center; font-weight: bold\">Pemasukan </div></td>
                <td width=\"14%\"><div style=\"text-align: center; font-weight: bold\">Pengeluaran </div></td>
            </tr>
        
            ";
        function rupiah($angka){
            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
            return $hasil_rupiah;
        }
        $tblStock2 = " ";
        $no = 1;
        foreach ($financialflow as $key => $val) {

            if ($val['candidate_id'] == null ){
                $candidate_name = '-';
                $timses_name = $this->getTimsesName($val['timses_id']);
            }else{
                $candidate_name = $this->getCandidateName($val['candidate_id']);
                $timses_name = '-';
            }

            if ($val->financial_category_type == 1){
                $income = rupiah($val['financial_flow_nominal']);
                $expenditure = '-';
            }else{
                $income = '-';
                $expenditure = rupiah($val['financial_flow_nominal']);
            }

            $tblStock2 .="
                    <tr>			
                        <td style=\"text-align:center\">$no.</td>
                        <td style=\"text-align:center\">".$val['financial_flow_date']."</td>
                        <td> ".$candidate_name."</td>
                        <td> ".$timses_name."</td>
                        <td><div style=\"text-align: right;\">".$income."</div></td>
                        <td><div style=\"text-align: right;\">".$expenditure."</div></td>
                        <td><div style=\"text-align: right;\"></div></td>
                        <td><div style=\"text-align: right;\"></div></td>
                    </tr>
                    
                ";
            $no++;
        }
        $tblStock4 = " 
        </table>
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
            <tr>
                <td style=\"text-align:right; font-style: italic;\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
            </tr>
        </table>";

        $pdf::writeHTML($tblStock1.$tblStock2.$tblStock4, true, false, false, false, '');


        $filename = 'Lap_Rekapitulasi_.pdf';
        $pdf::Output($filename, 'I');

        return redirect('/ledger');
    }

    public function exportLedgerReport()
    {
        if(!$start_month = Session::get('start_month')){
            $start_month = date('m');
        }else{
            $start_month = Session::get('start_month');
        }
        if(!$end_month = Session::get('end_month')){
            $end_month = date('m');
        }else{
            $end_month = Session::get('end_month');
        }
        if(!$year = Session::get('year')){
            $year = date('Y');
        }else{
            $year = Session::get('year');
        }

        $monthlist = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );

        $year_now 	=	date('Y');
        for($i=($year_now-2); $i<($year_now+2); $i++){
            $yearlist[$i] = $i;
        } 
    
        $financialflow = FinancialFlow::where('financial_flow.data_state', '=', 0)
        // ->join('financial_category', 'financial_flow.financial_category_id', '=', 'financial_category.financial_category_id')
        
        // ->where('financial_category.data_state', '=', 0)
        // ->where('financial_flow.financial_category_id', $financial_category_id)
        // ->where('financial_flow.candidate_id', $candidate_id)
        // ->where('financial_flow.timses_id', $timses_id)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year);
        // ->first();

        $financial_category_id = Session::get('financial_category_id');
        $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');
        
        if($financial_category_id||$financial_category_id!=null||$financial_category_id!=''){
            $financialflow   = $financialflow->where('financial_category_id', $financial_category_id);
        }
        if($candidate_id||$candidate_id!=null||$candidate_id!=''){
            $financialflow   = $financialflow->where('candidate_id', $candidate_id);
        }
        if($timses_id||$timses_id!=null||$timses_id!=''){
            $financialflow   = $financialflow->where('timses_id', $timses_id);
        }
        $financialflow   = $financialflow->get();
        
        //--------------SpreadsheetPHP--------------
        $spreadsheet = new Spreadsheet();
        
        if(count($financialflow)>=0){
            
            $spreadsheet->getProperties()->setCreator("SISPENSI")
                                ->setLastModifiedBy("SISPENSI")
                                ->setTitle("Laporan Rekapitulasi")
                                ->setSubject("")
                                ->setDescription("Laporan Rekapitulasi")
                                ->setKeywords("Laporan Rekapitulasi")
                                ->setCategory("Laporan Rekapitulasi");
                                
            $sheet = $spreadsheet->getActiveSheet(0);
            $spreadsheet->getActiveSheet()->setTitle("Laporan Rekapitulasi");
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    
            $spreadsheet->getActiveSheet()->mergeCells("B1:G1");

            $spreadsheet->getActiveSheet()->mergeCells("B8:B9");
            $spreadsheet->getActiveSheet()->mergeCells("C8:C9");
            $spreadsheet->getActiveSheet()->mergeCells("D8:D9");
            $spreadsheet->getActiveSheet()->mergeCells("E8:E9");
            $spreadsheet->getActiveSheet()->mergeCells("F8:F9");

            
            $spreadsheet->getActiveSheet()->mergeCells("G8:H8");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);

            $spreadsheet->getActiveSheet()->getStyle('B8:H8')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B9:H9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $spreadsheet->getActiveSheet()->getStyle('B8:H8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B9:H9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $spreadsheet->getActiveSheet()->mergeCells("B5:C5");
            $spreadsheet->getActiveSheet()->getStyle('B5:D5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('B5:D5')->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->mergeCells("B6:C6");
            $spreadsheet->getActiveSheet()->getStyle('B6:D6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('B6:D6')->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->mergeCells("B7:C7");
            $spreadsheet->getActiveSheet()->getStyle('B7:D7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('B7:D7')->getFont()->setBold(true);


            if ($financial_category_id == null ){
                $category_name = '-';
            }else{
                $category_name = $this->getCategoryName($financial_category_id);
            }
    
            if ($candidate_id == null ){
                $candidate_name = '-';
            }else{
                $candidate_name = $this->getCandidateName($candidate_id);
            }
    
            if ($timses_id == null ){
                $timses_name = '-';
            }else{
                $timses_name = $this->getTimsesName($timses_id);
            }
            
            $sheet->setCellValue('B1',"Laporan Rekapitulasi Dari Periode ".$monthlist[$start_month]." s.d ".$monthlist[$end_month]." ".$year);	
            $sheet->setCellValue('B5',"Kategori");
            $sheet->setCellValue('D5', $category_name);
            $sheet->setCellValue('B6',"Kandidat");
            $sheet->setCellValue('D6', $candidate_name);
            $sheet->setCellValue('B7',"Timses");
            $sheet->setCellValue('D7', $timses_name);
            $sheet->setCellValue('B8',"No");
            $sheet->setCellValue('C8',"Tanggal");
            $sheet->setCellValue('D8',"Kandidat");
            $sheet->setCellValue('E8',"Timses");
            $sheet->setCellValue('F8',"Pemasukan");
            $sheet->setCellValue('G8',"Pengeluaran");
            $sheet->setCellValue('H8',"Saldo");

            
            $sheet->setCellValue('H9',"Pemasukan");
            $sheet->setCellValue('I9',"Pengeluaran");
            
            
            $j=10;
            $no=0;
            
            foreach($financialflow as $key=>$val){

                if(is_numeric($key)){
                    
                    $spreadsheet->setActiveSheetIndex(0);
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':H'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j.':H'.$j)->getNumberFormat()->setFormatCode('0.00');

                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                        $no++;
                        $sheet->setCellValue('B'.$j, $no);
                        $sheet->setCellValue('C'.$j, $val['financial_flow_date']);
                        $sheet->setCellValue('D'.$j, $val['Candidate_id']);
                        $sheet->setCellValue('E'.$j, $val['timses_id']);
                        $sheet->setCellValue('F'.$j, $val['financial_category_id']);
                        $sheet->setCellValue('G'.$j, $val['financial_flow_nominal']);
                        $sheet->setCellValue('H'.$j, $val['financial_flow_nominal']);
                        $sheet->setCellValue('I'.$j, $val['financial_flow_nominal']);
                        
                        
                    
                }else{
                    continue;
                }
                $j++;
        
            }
            $spreadsheet->getActiveSheet()->mergeCells('B'.$j.':H'.$j);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('B'.$j, Auth::user()->name.", ".date('d-m-Y H:i'));

            $filename='Laporan_Rekapitulasi.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        }else{
            echo "Maaf data yang di eksport tidak ada !";
        }
    }
}

