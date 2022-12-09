<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialFlow;
use App\Models\FinancialCategory;
use App\Models\CoreTimses;
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
        if(!Session::get('financial_flow_code')){
            $financial_flow_code     = '';
        }else{
            $financial_flow_code = Session::get('financial_flow_code');
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

        $code = [
            ''  => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];
        // dd($start_date);
        if ($financial_flow_code == '') {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', '!=', null)
            ->get();
        } else {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', $financial_flow_code)
            ->get();
        }

        return view('content/FundingCombineReport_view/ReportFundingCombine', compact('fundingcombine', 'start_date', 'end_date', 'code', 'financial_flow_code'));
    }

    public function filterFundingCombineReport(Request $request)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $financial_flow_code = $request->financial_flow_code;
        // $candidate_id = $request->candidate_id;

        // dd( $financial_flow_code);

        Session::put('start_date', $start_date);
        Session::put('end_date', $end_date);
        Session::put('financial_flow_code', $financial_flow_code);
        // Session::put('candidate_id', $candidate_id);

        return redirect('/report-combine');
    }

    public function filterResetfundingcombineReport()
    {
        Session::forget('start_date');
        Session::forget('end_date');
        Session::forget('financial_flow_code');
        // Session::forget('candidate_id');

        return redirect('/report-combine');
    }

    public function getCategoryName($financial_category_id)
    {
        $data = FinancialCategory::where('financial_category_id',$financial_category_id)
        ->first();

        return $data['financial_category_name'];
    }

    public function getTimsesName($timses_id)
    {
        $data = CoreTimses::where('timses_id', $timses_id)
        ->first();

        return $data['timses_name'];
    }

    public function getCandidateName($candidate_id)
    {
        $data = CoreCandidate::where('candidate_id',$candidate_id)
        ->first();

        return $data['candidate_full_name'];
    }

    public function printFundingCombineReport()
    {
        if(!Session::get('financial_flow_code')){
            $financial_flow_code     = '';
        }else{
            $financial_flow_code = Session::get('financial_flow_code');
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

        $code = [
            ''  => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];
        // dd($start_date);
        if ($financial_flow_code == '') {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', '!=', null)
            ->get();
        } else {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', $financial_flow_code)
            ->get();
        }

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
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Kategori</div></th>
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Tipe</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Kandidat</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Timses</div></th>
                <th width=\"15%\" ><div style=\"text-align: center; font-weight: bold\">Tanggal</div></th>
                <th width=\"17%\" ><div style=\"text-align: center; font-weight: bold\">Nominal</div></th>
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

        $no = 1;
        $tblComb2= "";
        foreach ($fundingcombine as $key => $val) {
            if ($val->candidate_id == null ){
                $tblComb2 .="
                <tr>			
                    <td style=\"text-align:center\">$no.</td>
                    <td> ".$this->getCategoryName($val['financial_category_id'])."</td>
                    <td>".$type[$val['financial_category_type']]."</td>
                    <td style=\"text-align:center\">".'-'."</td>
                    <td> ".$this->getTimsesName($val['timses_id'])."</td>
                    <td> ".date('d-m-Y', strtotime($val['financial_flow_date']))."</td>
                    <td style=\"text-align:right\"> ".rupiah($val['financial_flow_nominal'])."</td>
                    
                </tr>
                ";
                $no++;
            }else{
                $tblComb2 .="
                <tr>			
                    <td style=\"text-align:center\">$no.</td>
                    <td> ".$this->getCategoryName($val['financial_category_id'])."</td>
                    <td>".$type[$val['financial_category_type']]."</td>
                    <td> ".$this->getCandidateName($val['candidate_id'])."</td>
                    <td style=\"text-align:center\">".'-'."</td>
                    <td> ".date('d-m-Y', strtotime($val['financial_flow_date']))."</td>
                    <td style=\"text-align:right\"> ".rupiah($val['financial_flow_nominal'])."</td>
                    
                </tr>
                ";
                $no++; 
            }
        }

        $tblComb3 = "
        </table>
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
            <tr>
                <td style=\"text-align:right\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tblComb1.$tblComb2.$tblComb3, true, false, false, false, '');

        $filename = 'Laporan_Pemasukan dan Pengeluaran.pdf';
        $pdf::Output($filename, 'I');
    }

    public function exportfundingcombineReport()
    {
        if(!Session::get('financial_flow_code')){
            $financial_flow_code     = '';
        }else{
            $financial_flow_code = Session::get('financial_flow_code');
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

        $code = [
            ''  => '',
            '1' => 'Kandidat',
            '2' => 'Timses',
        ];
        // dd($start_date);
        if ($financial_flow_code == '') {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', '!=', null)
            ->get();
        } else {
            $fundingcombine = FinancialFlow::where('data_state', '=', 0)
            // ->where('financial_flow.financial_category_type', '=', 2)
            ->where('financial_flow_date','>=',$start_date)
            ->where('financial_flow_date','<=',$end_date)
            ->where('financial_flow_code', $financial_flow_code)
            ->get();
        }

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

            $spreadsheet->getActiveSheet()->getStyle('B4:H4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
          

            $sheet->setCellValue('B1',"Laporan Pemasukan & Pengeluaran");	
            $sheet->setCellValue('B2',date('d M Y', strtotime($start_date))." s.d. ".date('d M Y', strtotime($end_date)));	
            $sheet->setCellValue('B4',"No");
            $sheet->setCellValue('C4',"Kategori");
            $sheet->setCellValue('D4',"Tipe");
            $sheet->setCellValue('E4',"Kandidat");
            $sheet->setCellValue('F4',"Timses");
            $sheet->setCellValue('G4',"Tanggal");
            $sheet->setCellValue('H4',"Nominal");
            
            $j=5;
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

            foreach($fundingcombine as $key=>$val){

                if(is_numeric($key)){
                    
                    $sheet = $spreadsheet->getActiveSheet(0);
                    $spreadsheet->getActiveSheet()->setTitle("Laporan Pengeluaran");
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':H'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                    $no++;
                    $sheet->setCellValue('B'.$j, $no);
                    $sheet->setCellValue('C'.$j, $this->getCategoryName($val['financial_category_id']));
                    $sheet->setCellValue('D'.$j, $type[$val['financial_category_type']]);
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
                    $sheet->setCellValue('G'.$j, date('d-m-Y', strtotime($val['financial_flow_date'])));
                    $sheet->setCellValue('H'.$j, rupiah($val['financial_flow_nominal']));
                }
                $j++;
        
            }
            $spreadsheet->getActiveSheet()->mergeCells('B'.$j.':H'.$j);
            $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('B'.$j, Auth::user()->name.", ".date('d-m-Y H:i'));


            $filename='Laporan_Pengeluaran.xls';
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
