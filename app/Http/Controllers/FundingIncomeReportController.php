<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialFlow;
use App\Models\FinancialCategory;
use App\Models\CoreTimses;
use App\Models\CoreCandidate;
use Elibyy\TCPDF\Facades\TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FundingIncomeReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
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

        $coretimses = CoreTimses::select('timses_id', 'timses_name')
        ->where('data_state', '=', 0)
        ->orderBy('timses_name', 'ASC')
        ->get()
        ->pluck('timses_name', 'timses_id');

        // $listcorecandidate = CoreCandidate :: where('data_state', 0)
        // ->get()
        // ->pluck('candidate_full_name', 'candidate_id');

        // $code = [
        //     ''  => '',
        //     '1' => 'Kandidat',
        //     '2' => 'Timses',
        // ];
        // dd($start_date);
        
        $fundingincome = FinancialFlow::where('data_state', '=', 0)
            ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);
            // ->where('timses_id', '=', '')
            // ->get();

        // $candidate_id = Session::get('candidate_id');
        $timses_id = Session::get('timses_id');

        // if($candidate_id||$candidate_id!=null||$candidate_id!=''){
        //     $fundingincome   = $fundingincome->where('candidate_id', $candidate_id);
        // }
        if($timses_id||$timses_id!=null||$timses_id!=''){
            $fundingincome   = $fundingincome->where('timses_id', $timses_id);
        }
        $fundingincome   = $fundingincome->get();

        return view('content/FundingIncomeReport_view/ReportFundingIncome', compact('fundingincome', 'start_date', 'end_date', 'coretimses', 'timses_id'));
    }

    public function filterFundingIncomeReport(Request $request)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $timses_id = $request->timses_id;
        // $candidate_id = $request->candidate_id;

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('timses_id', $timses_id);
        // Session::put('candidate_id', $candidate_id);
        return redirect('/report-income');
    }

    public function filterResetFundingIncomeReport()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('timses_id');
        // Session::forget('candidate_id');

        return redirect('/report-income');
    }

    public function getCategoryName($financial_category_id)
    {
        $data = FinancialCategory::where('financial_category_id',$financial_category_id)
        ->first();

        if($data == null){
            "-";
        }else{
            return $data['financial_category_name'];
        }
    }

    public function getTimsesName($timses_id)
    {
        $data = CoreTimses::where('timses_id', $timses_id)
        ->first();

        if($data == null){
            "-";
        }else{
            return $data['timses_name'];
        }

    }

    public function getCandidateName($candidate_id)
    {
        $data = CoreCandidate::where('candidate_id',$candidate_id)
        ->first();
        if($data == null){
            "-";
        }else{
            return $data['candidate_full_name'];
        }
    }

    public function printFundingIncomeReport()
    {
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

        $coretimses = CoreTimses::select('timses_id', 'timses_name')
        ->where('data_state', '=', 0)
        ->get()
        ->pluck('timses_name', 'timses_id');

        $fundingincome = FinancialFlow::where('data_state', '=', 0)
            ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);
    
            $timses_id = Session::get('timses_id');

            if($timses_id||$timses_id!=null||$timses_id!=''){
                $fundingincome   = $fundingincome->where('timses_id', $timses_id);
            }
        $fundingincome   = $fundingincome->get();

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
                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">LAPORAN PEMASUKAN</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size:12px\">PERIODE : ".date('d M Y', strtotime($start_date))." s.d. ".date('d M Y', strtotime($end_date))."</div></td>
            </tr>
        </table>
        
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');
        
        $tblIncome1 = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
            <tr>
                <th width=\"5%\" ><div style=\"text-align: center; font-weight: bold\">No</div></th>
                <th width=\"20%\" ><div style=\"text-align: center; font-weight: bold\">Tanggal</div></th>
                <th width=\"25%\" ><div style=\"text-align: center; font-weight: bold\">Kategori Pemasukan</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Kandidat</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Timses</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Nominal</div></th>
            </tr>
        ";

        function rupiah($angka){
            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
            return $hasil_rupiah;
        }
        $no = 1;
        $total_nominal = 0;
        $tblIncome2= "";
        foreach ($fundingincome as $key => $val) {
            if ($val->candidate_id == null ){
                $tblIncome2 .="
                <tr>			
                    <td style=\"text-align:center\">$no.</td>
                    <td> ".date('d/m/Y', strtotime($val['financial_flow_date']))."</td>
                    <td> ".$this->getCategoryName($val['financial_category_id'])."</td>
                    <td style=\"text-align:center\">".'-'."</td>
                    <td> ".$this->getTimsesName($val['timses_id'])."</td>
                    <td style=\"text-align:right\"> ".rupiah($val['financial_flow_nominal'])."</td>
                    
                </tr>
                ";
                $no++;
            }else{
                $tblIncome2 .="
                <tr>			
                    <td style=\"text-align:center\">$no.</td>
                    <td> ".date('d/m/Y', strtotime($val['financial_flow_date']))."</td>
                    <td> ".$this->getCategoryName($val['financial_category_id'])."</td>
                    <td> ".$this->getCandidateName($val['candidate_id'])."</td>
                    <td style=\"text-align:center\">".'-'."</td>
                    <td style=\"text-align:right\"> ".rupiah($val['financial_flow_nominal'])."</td>
                    
                </tr>
                ";
                $no++; 
            }
            $total_nominal += $val['financial_flow_nominal'];
            
        }

        $tblIncome3 = "
        </table>
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td width=\"30%\" style=\"text-align:left; font-style: italic;\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
                <td width=\"51%\" style=\"text-align:right; font-weight: bold;\">".'Total :'."</td>
                <td width=\"20%\" style=\"text-align:right; font-weight: bold;\">".rupiah($total_nominal)."</td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tblIncome1.$tblIncome2.$tblIncome3, true, false, false, false, '');

        $filename = 'Laporan_Pemasukan.pdf';
        $pdf::Output($filename, 'I');
    }

    public function exportFundingIncomeReport()
    {
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

        $coretimses = CoreTimses::select('timses_id', 'timses_name')
        ->where('data_state', '=', 0)
        ->get()
        ->pluck('timses_name', 'timses_id');

        $fundingincome = FinancialFlow::where('data_state', '=', 0)
            ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);
    
            $timses_id = Session::get('timses_id');

            if($timses_id||$timses_id!=null||$timses_id!=''){
                $fundingincome   = $fundingincome->where('timses_id', $timses_id);
            }
        $fundingincome   = $fundingincome->get();

        $spreadsheet = new Spreadsheet();

        if(count($fundingincome)>=0){
            $spreadsheet->getProperties()->setCreator("IBS CJDW")
                                        ->setLastModifiedBy("IBS CJDW")
                                        ->setTitle("Voucher Report")
                                        ->setSubject("")
                                        ->setDescription("Voucher Report")
                                        ->setKeywords("Voucher, Report")
                                        ->setCategory("Voucher Report");
                                        
            $sheet = $spreadsheet->getActiveSheet(0);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    
            $spreadsheet->getActiveSheet()->mergeCells("B1:G1");
            $spreadsheet->getActiveSheet()->mergeCells("B2:G2");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle('B4:G4')->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->getStyle('B4:G4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue('B1',"Laporan Pemasukan");	
            $sheet->setCellValue('B2',date('d M Y', strtotime($start_date))." s.d. ".date('d M Y', strtotime($end_date)));	
            $sheet->setCellValue('B4',"No");
            $sheet->setCellValue('C4',"Tanggal");
            $sheet->setCellValue('D4',"Kategori Pemasukan");
            $sheet->setCellValue('E4',"Kandidat");
            $sheet->setCellValue('F4',"Timses");
            $sheet->setCellValue('G4',"Nominal");
            
            $j=5;
            $i=6;
            $no=0;
            function rupiah($angka){
                $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                return $hasil_rupiah;
            }
            $total_nominal = 0;

            foreach($fundingincome as $key=>$val){

                if(is_numeric($key)){
                    
                    $sheet = $spreadsheet->getActiveSheet(0);
                    $spreadsheet->getActiveSheet()->setTitle("Laporan Voucher");
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':G'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $no++;
                    $sheet->setCellValue('B'.$j, $no);
                    $sheet->setCellValue('C'.$j, date('d/m/Y', strtotime($val['financial_flow_date'])));
                    $sheet->setCellValue('D'.$j, $this->getCategoryName($val['financial_category_id']));
                    if($val['candidate_id'] == null){
                        $sheet->setCellValue('E'.$j, '-');
                    }else{
                        $sheet->setCellValue('E'.$j, $this->getCandidateName($val['candidate_id']));
                    }
                    if($val['timses_id'] == null){
                        $sheet->setCellValue('F'.$j, '-');
                    }else{
                        $sheet->setCellValue('F'.$j, $this->getTimsesName($val['timses_id']));
                    }
                    
                    $sheet->setCellValue('G'.$j, rupiah($val['financial_flow_nominal']));
                
                    $total_nominal += $val['financial_flow_nominal'];
                }
                $j++;
                $i++;
        
            }

            $spreadsheet->getActiveSheet()->mergeCells('B'.$i.':G'.$i);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            
            $spreadsheet->getActiveSheet()->mergeCells('B'.$j.':G'.$j);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j.':G'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j.':G'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('B'.$j, "Total : ". rupiah($total_nominal));
            $sheet->setCellValue('B'.$i, Auth::user()->name.", ".date('d-m-Y H:i'));


            $filename='Laporan_Pemasukan.xls';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
        }else{
            echo "Maaf data yang di export tidak ada !";
        }
    }
}
