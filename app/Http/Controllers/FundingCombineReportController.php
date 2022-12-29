<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialFlow;
use App\Models\FinancialCategory;
use App\Models\CoreTimsesMember;
use App\Models\CoreCandidate;
// use App\Models\Program;
use Elibyy\TCPDF\Facades\TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class FundingCombineReportController extends Controller
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

        
        $coretimsesmember = CoreTimsesMember::select('timses_member_id', 'timses_member_name')
        ->where('data_state', '=', 0)
        ->get()
        ->pluck('timses_member_name', 'timses_member_id');

        // $listcorecandidate = CoreCandidate :: where('data_state', 0)
        // ->get()
        // ->pluck('candidate_full_name', 'candidate_id');

        // dd($start_date);
        
        $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);

            $timses_member_id = Session::get('timses_member_id');

            if($timses_member_id||$timses_member_id!=null||$timses_member_id!=''){
                $fundingcombine   = $fundingcombine->where('timses_member_id', $timses_member_id);
            }
            $fundingcombine   = $fundingcombine->get();
        return view('content/FundingCombineReport_view/ReportFundingCombine', compact('fundingcombine', 'start_date', 'end_date', 'coretimsesmember', 'timses_member_id'));
    }

    public function filterFundingCombineReport(Request $request)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $timses_member_id = $request->timses_member_id;
        // $candidate_id = $request->candidate_id;

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('timses_member_id', $timses_member_id);
        // Session::put('candidate_id', $candidate_id);

        return redirect('/report-combine');
    }

    public function filterResetfundingcombineReport()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('timses_member_id');
        // Session::forget('candidate_id');

        return redirect('/report-combine');
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

    public function getTimsesMemberName($timses_member_id)
    {
        $data = CoreTimsesMember::where('timses_member_id', $timses_member_id)
        ->first();

        if($data == null){
            "-";
        }else{
            return $data['timses_member_name'];
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

    public function printFundingCombineReport()
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

        $coretimsesmember = CoreTimsesMember::select('timses_member_id', 'timses_member_name')
        ->where('data_state', '=', 0)
        ->get()
        ->pluck('timses_member_name', 'timses_member_id');

        // $listcorecandidate = CoreCandidate :: where('data_state', 0)
        // ->get()
        // ->pluck('candidate_full_name', 'candidate_id');

        // dd($start_date);
        
        $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);

            $timses_member_id = Session::get('timses_member_id');

            if($timses_member_id||$timses_member_id!=null||$timses_member_id!=''){
                $fundingcombine   = $fundingcombine->where('timses_member_id', $timses_member_id);
            }
            $fundingcombine   = $fundingcombine->get();

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
                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">LAPORAN PEMASUKAN DAN PENGELUARAN</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: center; font-size:12px\">PERIODE : ".date('d M Y', strtotime($start_date))." s.d. ".date('d M Y', strtotime($end_date))."</div></td>
            </tr>
        </table>
        
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');
        
        $tblComb1 = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
            <tr>
                <th width=\"5%\" ><div style=\"text-align: center; font-weight: bold\">No</div></th>
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Tanggal</div></th>
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Kategori</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Kandidat</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Timses</div></th>
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Pemasukan</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Pengeluaran</div></th>
            </tr>
        ";

        function rupiah($angka){
            $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
            return $hasil_rupiah;
        }

        $type =[
            ''  => '',
            '1' => 'Pemasukan',
            '2' => 'Pengeluaran',
        ];
        $total_income= 0;
        $total_expenditure = 0;
        $balance = 0;
        $no = 1;
        $tblComb2= "";
        foreach ($fundingcombine as $key => $val) {
            if ($val->candidate_id == null ){
                $candidate_name = '-';
                $timses_name = $this->getTimsesMemberName($val['timses_member_id']);
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

            $tblComb2 .="
            <tr>			
                <td style=\"text-align:center\">$no.</td>
                <td> ".date('d/m/Y', strtotime($val['financial_flow_date']))."</td>
                <td> ".$this->getCategoryName($val['financial_category_id'])."</td>
                <td> ".$candidate_name."</td>
                <td> ".$timses_name."</td>
                <td style=\"text-align:right\"> ".$income."</td>
                <td style=\"text-align:right\"> ".$expenditure."</td>
                
            </tr>
            ";

            $no++;
            
            if($val['financial_category_type'] == 1){
                $total_income += $val['financial_flow_nominal'];
            }
            if($val['financial_category_type'] == 2){
                $total_expenditure += $val['financial_flow_nominal'];
            }
            $balance = $total_income - $total_expenditure;
        }
// dd($total_income);
        $tblComb3 = "
        </table>
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
        <tr>
            <td width=\"64%\" style=\"text-align:left; font-style: italic;\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
            <td width=\"20%\" style=\"text-align:right; font-weight: bold;\">".'Total Pemasukan:'."</td>
            <td width=\"17%\" style=\"text-align:right; font-weight: bold;\">".rupiah($total_income)."</td>
        </tr>
        <tr>
            <td width=\"64%\" style=\"text-align:left; font-style: italic;\"></td>
            <td width=\"20%\" style=\"text-align:right; font-weight: bold;\">".'Total Pengeluaran:'."</td>
            <td width=\"17%\" style=\"text-align:right; font-weight: bold;\">".rupiah($total_expenditure)."</td>
        </tr>
        <tr>
            <td width=\"64%\" style=\"text-align:left; font-style: italic;\"></td>
            <td width=\"20%\" style=\"text-align:right; font-weight: bold;\">".'Sisa Saldo:'."</td>
            <td width=\"17%\" style=\"text-align:right; font-weight: bold;\">".rupiah($balance)."</td>
        </tr>
        </table>
        ";

        $pdf::writeHTML($tblComb1.$tblComb2.$tblComb3, true, false, false, false, '');

        $filename = 'Laporan_Pemasukan dan Pengeluaran.pdf';
        $pdf::Output($filename, 'I');
    }

    public function exportfundingcombineReport()
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

        $coretimsesmember = CoreTimsesMember::select('timses_member_id', 'timses_member_name')
        ->where('data_state', '=', 0)
        ->get()
        ->pluck('timses_member_name', 'timses_member_id');

        // $listcorecandidate = CoreCandidate :: where('data_state', 0)
        // ->get()
        // ->pluck('candidate_full_name', 'candidate_id');

        // dd($start_date);
        
        $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 1)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date);

            $timses_member_id = Session::get('timses_member_id');

            if($timses_member_id||$timses_member_id!=null||$timses_member_id!=''){
                $fundingcombine   = $fundingcombine->where('timses_member_id', $timses_member_id);
            }
            $fundingcombine   = $fundingcombine->get();

        $spreadsheet = new Spreadsheet();

        if(count($fundingcombine)>=0){
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
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
    
            $spreadsheet->getActiveSheet()->mergeCells("B1:H1");
            $spreadsheet->getActiveSheet()->mergeCells("B2:H2");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle('B4:H4')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B4:H4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B4:H4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->setCellValue('B1',"Laporan Pemasukan & Pengeluaran");	
            $sheet->setCellValue('B2',date('d M Y', strtotime($start_date))." s.d. ".date('d M Y', strtotime($end_date)));	
            $sheet->setCellValue('B4',"No");
            $sheet->setCellValue('C4',"Tanggal");
            $sheet->setCellValue('D4',"Kategori");
            $sheet->setCellValue('E4',"Kandidat");
            $sheet->setCellValue('F4',"Timses");
            $sheet->setCellValue('G4',"Pemasukan");
            $sheet->setCellValue('H4',"Pengeluaran");
            
            $j=5;
            $k=6;
            $l=7;
            $m=8;

            $no=0;
            function rupiah($angka){
                $hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
                return $hasil_rupiah;
            }
            
            $type =[
                ''  => '',
                '1' => 'Pemasukan',
                '2' => 'Pengeluaran',
            ];
            $total_income= 0;
            $total_expenditure = 0;
            $balance = 0;
            foreach($fundingcombine as $key=>$val){

                if(is_numeric($key)){
                    
                    $sheet = $spreadsheet->getActiveSheet(0);
                    $spreadsheet->getActiveSheet()->setTitle("Laporan Pemasukan & Pengeluaran");
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':H'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $no++;
                    $sheet->setCellValue('B'.$j, $no);
                    $sheet->setCellValue('C'.$j, date('d-m-Y', strtotime($val['financial_flow_date'])));
                    $sheet->setCellValue('D'.$j, $this->getCategoryName($val['financial_category_id']));
                    if($val['candidate_id'] == null){
                        $sheet->setCellValue('E'.$j, '-');
                    }else{
                        $sheet->setCellValue('E'.$j, $this->getCandidateName($val['candidate_id']));
                    }
                    if($val['timses_member_id'] == null){
                        $sheet->setCellValue('F'.$j, '-');
                    }else{
                        $sheet->setCellValue('F'.$j, $this->getTimsesMemberName($val['timses_member_id']));
                    }
                    if($val['financial_category_type'] == 1){
                        $sheet->setCellValue('G'.$j, rupiah($val['financial_flow_nominal']));
                        $sheet->setCellValue('H'.$j, '-');
                    }
                    if($val['financial_category_type'] == 2){
                        $sheet->setCellValue('G'.$j, '-');
                        $sheet->setCellValue('H'.$j, rupiah($val['financial_flow_nominal']));
                    }
                }
                
                $j++;
                $k++;
                $l++;
                $m++;

                if($val['financial_category_type'] == 1){
                    $total_income += $val['financial_flow_nominal'];
                }
                if($val['financial_category_type'] == 2){
                    $total_expenditure += $val['financial_flow_nominal'];
                }
                $balance = $total_income - $total_expenditure;
        
            }
            
            $spreadsheet->getActiveSheet()->mergeCells('B'.$j.':H'.$j);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j.':H'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            
            $spreadsheet->getActiveSheet()->mergeCells('B'.$k.':H'.$k);
            $spreadsheet->getActiveSheet()->getStyle('B'.$k)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$k.':H'.$k)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$k)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->getActiveSheet()->mergeCells('B'.$l.':H'.$l);
            $spreadsheet->getActiveSheet()->getStyle('B'.$l)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('B'.$l.':H'.$l)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B'.$l)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $spreadsheet->getActiveSheet()->mergeCells('B'.$m.':H'.$m);
            $spreadsheet->getActiveSheet()->getStyle('B'.$m)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j.':G'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
            $sheet->setCellValue('B'.$j, "Total Pemasukan : ". rupiah($total_income));
            $sheet->setCellValue('B'.$k, "Total Pengeluaran : ". rupiah($total_expenditure));
            $sheet->setCellValue('B'.$l, "Sisa saldo : ". rupiah($balance));
            $sheet->setCellValue('B'.$m, Auth::user()->name.", ".date('d-m-Y H:i'));

            $filename='Laporan_Pemasukan & Pengeluaran.xls';
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
