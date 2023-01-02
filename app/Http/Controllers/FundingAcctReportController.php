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
class FundingAcctReportController extends Controller
{
    public function __construct(){
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

        // $financialflow_list = Session::get('financialflow_list');

        // $code=[
        //     '' => '',
        //     '1' => 'Kandidat',
        //     '2' => 'Timses',
        // ];

        $category_income = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 1)
        ->get();

        $category_expenditure = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 2)
        ->get();
        // dd($category_expenditure);

        // $star_date = "2022-09-20"; 
        // $threemonth = date('Y-m-d', strtotime('+2 year', strtotime($star_date)));
        // dd($threemonth);


        return view('content.FundingAcctReport_view.ReportFundingAcct', compact('start_month','end_month', 'monthlist', 'year' , 'yearlist', 'year_now', 'category_income', 'category_expenditure'));
    }

    public function filterFundingAcctReport(Request $request){
        $start_month  = $request->start_month;
        $end_month    = $request->end_month;
        $start_date  = $request->start_date;
        $end_date    = $request->end_date;
        $year         = $request->year;
        $timses_id    = $request->timses_id;
        $candidate_id = $request->candidate_id;
        $financial_category_id = $request->financial_category_id;
        $financialflow_list = $request->financialflow_list;

        Session::put('timses_id', $timses_id);
        Session::put('candidate_id', $candidate_id);
        Session::put('financial_category_id', $financial_category_id);
        Session::put('financialflow_list', $financialflow_list);
        Session::put('start_month',$start_month);
        Session::put('end_month',$end_month);
        Session::put('start_date',$start_date);
        Session::put('end_date',$end_date);
        Session::put('year',$year);
// dd($financialflow_list);
        return redirect('/report-funding');
    }

    public function filterResetFundingAcctReport(){
        Session::put('start_month');
        Session::put('end_month');
        Session::put('start_date');
        Session::put('end_date');
        Session::put('year');
        Session::put('year');
        Session::forget('timses_id');
        Session::forget('candidate_id');
        Session::forget('financial_category_id');
        Session::forget('financialflow_list');

        return redirect('/report-funding');
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

    public function getFinanciaLFlowNominal($financial_category_id)
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

        $data = FinancialFlow::where('data_state', '=', 0)
        ->whereMonth('financial_flow.financial_flow_date','>=',$start_month)
        ->whereMonth('financial_flow.financial_flow_date','<=',$end_month)
        ->whereYear('financial_flow.financial_flow_date',$year)
        ->where('financial_category_id', $financial_category_id)
        ->get();
    
        // $financialflow_list = Session::get('financialflow_list');

        // if($financialflow_list||$financialflow_list!=null||$financialflow_list!=''){
        //     if($financialflow_list ==  1){           
        //         $data   = $data->where('candidate_id', '!=', null);
        //     }else{
        //         $data   = $data->where('timses_id', '!=', null);
        //     }
        // }else{
        //     $data   = $data->where('candidate_id', '=', null);
        //     $data   = $data->where('timses_id', '=', null);
        // }

        $nominal = 0;
        foreach($data as $val){
            $nominal += $val['financial_flow_nominal'];
        }

        return $nominal;
    }

    public function printFundingAcctReport(){

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

        $financialflow_list = Session::get('financialflow_list');

        $code=[
            '' => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];

        $category_income = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 1)
        ->get();

        $category_expenditure = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 2)
        ->get();

        $pdf = new TCPDF('P', PDF_UNIT, 'F4', true, 'UTF-8', false);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(30, 10, 40, 10); // put space of 10 on top

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('helvetica', 'B', 20);

        $pdf::AddPage();

        $pdf::SetFont('helvetica', '', 10);

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">Laporan Perhitungan Keuangan ".$code[$financialflow_list]."</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size:12px\">Periode : ".$monthlist[$start_month]." - ".$monthlist[$end_month] ." ".$year."</div></td>
            </tr>
            <hr>
            <br>
        </table>
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');

        $tblIncome1 = "
        <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
            <tr>
                <td width=\"100%\" colspan=\"2\"><div style=\"text-align: left; font-weight: bold\">Kategori Pemasukan</div></td>
            </tr>
            
            </table>
            <br>
        ";
        $total_income = 0;
        $tblIncome2 = "";
        $space_category = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $space_nominal = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

        function rupiah($angka){
            $hasil_rupiah = number_format($angka,2,',','.');
            return $hasil_rupiah;
        }

        foreach ($category_income as $key => $val) {
            $total_income += $this->getFinanciaLFlowNominal($val->financial_category_id);
            $tblIncome2 .= "
            <br>
            <tr>
                <td>". $space_category ." ".$val['financial_category_name']."</td>
                <td style=\"text-align: right;\">".rupiah($this->getFinanciaLFlowNominal($val->financial_category_id))."</td>
            <tr>
            
            ";
        }
        $tblIncome3 ="
        <hr>
        <tr>
            <td><div style=\"text-align: left; font-weight: bold\">Total Pemasukan</div></td>
            <td style=\"text-align: right; font-weight: bold\">".rupiah($total_income)."</td>
        <tr>
        <hr>
        ";
        $pdf::writeHTML($tblIncome1.$tblIncome2.$tblIncome3, true, false, false, false, '');


        $tblExpend1 = "
        <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
            <tr>
                <td width=\"100%\" colspan=\"2\"><div style=\"text-align: left; font-weight: bold\">Kategori Pengeluaran</div></td>
            </tr>
            
            </table>
            <br>
        ";

        $total_expenditure  = 0;
        $tblExpend2 = "";
    
        foreach ($category_expenditure as $key => $val) {
            $total_expenditure += $this->getFinanciaLFlowNominal($val->financial_category_id);

            $tblExpend2 .= "
            <br>
            <tr>
                <td>". $space_category ." ".$val['financial_category_name']."</td>
                <td style=\"text-align: right;\"> ". rupiah($this->getFinanciaLFlowNominal($val->financial_category_id))."</td>
            <tr>
            ";
        }
        $tblExpend3 ="
        <hr>
        <tr>
            <td><div style=\"text-align: left; font-weight: bold\">Total Pengeluaran</div></td>
            <td style=\"text-align: right; font-weight: bold\">".rupiah($total_expenditure)."</td>
        <tr>
        <hr>
        <br>
        <tr>
            <td><div style=\"text-align: left; font-weight: bold\">Sisa Saldo</div></td>
            <td style=\"text-align: right; font-weight: bold\">".rupiah($total_income - $total_expenditure)."</td>
        <tr>
        <hr>
        <tr>
            <td style=\"text-align: left; font-style: italic;\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
        </tr>";
        $pdf::writeHTML($tblExpend1.$tblExpend2.$tblExpend3, true, false, false, false, '');

        $filename = 'Laporan_Perhitungan_Keuangan'.$start_month.'s.d.'.$end_month.'.pdf';
        $pdf::Output($filename, 'I');
    }

    public function exportFundingAcctReport(){
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

        $financialflow_list = Session::get('financialflow_list');

        $code=[
            '' => '',
            '1' => 'KANDIDATE',
            '2' => 'TIMSES',
        ];

        $category_income = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 1)
        ->get();

        $category_expenditure = FinancialCategory::where('financial_category.data_state', '=', 0)
        ->where('financial_category.financial_category_type', '=', 2)
        ->get();

        $spreadsheet = new Spreadsheet();

        // if(!empty($sales_invoice || $purchase_invoice || $expenditure)){
            $spreadsheet->getProperties()->setCreator("SISPENSI")
                                        ->setLastModifiedBy("SISPENSI")
                                        ->setTitle("Lap Perhitungan Keuangan")
                                        ->setSubject("")
                                        ->setDescription("Lap Perhitungan Keuangan")
                                        ->setKeywords("Lap, Perhitungan, Keuangan")
                                        ->setCategory("Lap Perhitungan Keuangan");
                            
            $sheet = $spreadsheet->getActiveSheet(0);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    
            $spreadsheet->getActiveSheet()->mergeCells("B1:C1");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->mergeCells("B2:C2");
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue('B1',"LAPORAN PERHITUNGAN KEUANGAN ".$code[$financialflow_list]."");	
            $sheet->setCellValue('B2', 'Periode '.$monthlist[$start_month]." - ".$monthlist[$end_month] ." ".$year);

            function idr($angka){
                $hasil_rupiah = number_format($angka,2,',','.');
                return $hasil_rupiah;
            }
            
            $j = 5;
            $i = 4;
            $total_income = 0;
            $total_expenditure = 0;
            $space_category = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $space_nominal = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            $spreadsheet->getActiveSheet()->mergeCells("B".$i.":C".$i."");
            $spreadsheet->getActiveSheet()->getStyle("B".$i.":C".$i)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i.':C'.$i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$i, "Kategori Pemasukan");

            foreach($category_income as $key => $val){
                if(is_numeric($key)){
                    
                    $spreadsheet->setActiveSheetIndex(0);
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':C'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getNumberFormat()->setFormatCode('0.00');
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    
                    $spreadsheet->getActiveSheet()->setCellValue('B'.($j), "Kategori Pemasukan");
                    $spreadsheet->getActiveSheet()->setCellValue('B'.$j, "              ". $val['financial_category_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('C'.$j, idr($this->getFinanciaLFlowNominal($val->financial_category_id)));
                    
                    $j++;
                    $total_income += $this->getFinanciaLFlowNominal($val->financial_category_id);
                }
            }
            $spreadsheet->getActiveSheet()->getStyle("B".$j.":C".$j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j.':C'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$j, "Total Pemasukan");
            $spreadsheet->getActiveSheet()->setCellValue('C'.$j, idr($total_income));

            $spreadsheet->getActiveSheet()->getStyle('B'.$j.':C'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $j++;

            $ji = $j++;
            $spreadsheet->getActiveSheet()->mergeCells("B".$ji.":C".$ji."");
            $spreadsheet->getActiveSheet()->getStyle("B".$ji.":C".$ji)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$ji.':C'.$ji)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$ji)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('C'.$ji)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$ji, "");

            $jik = $ji + 1;
            $spreadsheet->getActiveSheet()->mergeCells("B".$jik.":C".$jik."");
            $spreadsheet->getActiveSheet()->getStyle("B".$jik.":C".$jik)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$jik.':C'.$jik)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$jik)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $spreadsheet->getActiveSheet()->getStyle('C'.$jik)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$jik, "Kategori Pengeluaran");

            $x =  $jik + 1;
            foreach($category_expenditure as $keyX => $valX){
                if(is_numeric($key)){
                    $spreadsheet->setActiveSheetIndex(0);
                    $spreadsheet->getActiveSheet()->getStyle('B'.$x.':C'.$x)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
                    $spreadsheet->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $spreadsheet->getActiveSheet()->setCellValue('B'.$x, "              ". $valX['financial_category_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('C'.$x, idr($this->getFinanciaLFlowNominal($valX['financial_category_id'])));
                $x++;

                $total_expenditure += $this->getFinanciaLFlowNominal($valX['financial_category_id']);
            }
        }
        $spreadsheet->getActiveSheet()->getStyle("B".$x.":C".$x)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B'.$x.':C'.$x)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $spreadsheet->getActiveSheet()->setCellValue('B'.$x, "Total Pengeluaran");
        $spreadsheet->getActiveSheet()->setCellValue('C'.$x, idr($total_expenditure));
        $x++;

        $xi = $x++;
        $spreadsheet->getActiveSheet()->mergeCells("B".$xi.":C".$xi."");
        $spreadsheet->getActiveSheet()->getStyle("B".$xi.":C".$xi)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('B'.$xi.':C'.$xi)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->getStyle('B'.$xi)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('C'.$xi)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $xik =  $xi + 1;
        $spreadsheet->getActiveSheet()->getStyle('B'.$xik)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('C'.$xik)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $spreadsheet->getActiveSheet()->getStyle('B'.$xik.':C'.$xik)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->getActiveSheet()->getStyle("B".($xik).":C".$xik)->getFont()->setBold(true);	

        $last_balance = $total_income - $total_expenditure;

        $spreadsheet->getActiveSheet()->setCellValue('B'.($xik), "Sisa Saldo");
        $spreadsheet->getActiveSheet()->setCellValue('C'.($xik),  idr($last_balance));
        $xik++;
        $spreadsheet->getActiveSheet()->mergeCells('B'.$xik.':C'.$xik);
        $spreadsheet->getActiveSheet()->getStyle('B'.$xik)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('B'.$xik, Auth::user()->name.", ".date('d-m-Y H:i'));

            
            $filename='Laporan_Rugi_Laba_'.$start_month.'_s.d._'.$end_month.'.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
    
    }
}
