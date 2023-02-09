<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\FinancialFlow;
use App\Models\ProgramTimsesActivity;
use App\Models\FinancialCategory;
use App\Models\Program;
use App\Models\CoreCandidate;
use App\Models\CoreTimses;
use App\Models\CoreTimsesMember;
use Elibyy\TCPDF\Facades\TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class DataTimsesMemberReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user_id = Auth::id();
        $timses_id = CoreTimses::where('core_timses.data_state', 0)
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('system_user.user_id', $user_id)
        ->first()->timses_id;
        // dd($timses_id);

        $coretimsesmember = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        return view('content/DataTimsesMemberReport_view/ReportDataTimsesMember', compact('user_id', 'timses_id', 'coretimsesmember'));
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


    public function printDataTimsesMemberReport()
    {
        $user_id = Auth::id();
        $timses_id = CoreTimses::where('core_timses.data_state', 0)
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('system_user.user_id', $user_id)
        ->first()->timses_id;
        // dd($timses_id);

        $coretimsesmember = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        $pdf = new TCPDF('L', PDF_UNIT, 'F4', true, 'UTF-8', false);

        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);

        $pdf::SetMargins(10, 10, 10, 10); // put space of 10 on top

        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        $pdf::SetFont('helvetica', 'B', 20);

        $pdf::AddPage('L', 'A4');

        $pdf::SetFont('helvetica', '', 8);

        $timses_name = CoreTimses::where('core_timses.data_state', 0)
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('system_user.user_id', $user_id)
        ->first()->timses_name;

        $tbl = "
        <table cellspacing=\"0\" cellpadding=\"2\" border=\"0\">
            <tr>
                <td><div style=\"text-align: center; font-size:14px; font-weight: bold\">LAPORAN DATA ANGGOTA TIMSES</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: left; font-size:12px\">Nama Timses    : ".$timses_name."</div></td>
            </tr>
            <tr>
                <td><div style=\"text-align: left; font-size:12px\">Jumlah Anggota : ".count($coretimsesmember)."</div></td>
            </tr>
        </table>
        
        ";
        $pdf::writeHTML($tbl, true, false, false, false, '');
        
        $tblTA1 = "
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"1\" width=\"100%\">
            <tr>
                <th width=\"4%\" ><div style=\"text-align: center; font-weight: bold\">No</div></th>
                <th width=\"18%\" ><div style=\"text-align: center; font-weight: bold\">Nama Anggota</div></th>
                <th width=\"18%\" ><div style=\"text-align: center; font-weight: bold\">Tempat, Tanggal Lahir</div></th>
                <th width=\"10%\" ><div style=\"text-align: center; font-weight: bold\">Umur</div></th>
                <th width=\"20%\" ><div style=\"text-align: center; font-weight: bold\">Alamat</div></th>
                <th width=\"10%\" ><div style=\"text-align: center; font-weight: bold\">Agama</div></th>
                <th width=\"10%\" ><div style=\"text-align: center; font-weight: bold\">No. Telp</div></th>
                <th width=\"10%\" ><div style=\"text-align: center; font-weight: bold\">Kelamin</div></th>
            </tr>
        ";

        $no = 1;
        // $lahir = new DateTime(date($val['timses_member_date_of_birth']));
        // $today = new DateTime('today');
        // $umur  = $today->diff($lahir);

        $gender =[
                    ''  => '',
                    '1' => 'Laki-laki',
                    '2' => 'Perempuan',
                ];

        $tblTA2= "";
        foreach ($coretimsesmember as $key => $val) {

                $tblTA2 .="
                <tr>			
                    <td style=\"text-align:center\">$no.</td>
                    <td> ".$val['timses_member_name']."</td>
                    <td> ".$val['timses_member_place_of_birth'].", ".date('d-m-Y', strtotime($val['timses_member_date_of_birth']))."</td>
                    <td> ".$val['timses_member_name']."</td>
                    <td> ".$val['timses_member_address']."</td>
                    <td> ".$val['timses_member_religion']."</td>
                    <td> ".$val['timses_member_phone']."</td>
                    <td> ".$gender[$val['timses_member_gender']]."</td>
                </tr>
                ";
                $no++;
        }
        // dd($total_nominal);

        $tblTA3 = "
        </table>
        <table cellspacing=\"0\" cellpadding=\"1\" border=\"0\">
            <tr>
                <td width=\"100%\" style=\"text-align:right; font-style: italic;\">".Auth::user()->name.", ".date('d-m-Y H:i')."</td>
            </tr>
        </table>
        ";

        $pdf::writeHTML($tblTA1.$tblTA2.$tblTA3, true, false, false, false, '');

        $filename = 'Laporan_Pengeluaran.pdf';
        $pdf::Output($filename, 'I');
    }

    public function exportDataTimsesMemberReport()
    {
        $user_id = Auth::id();
        $timses_id = CoreTimses::where('core_timses.data_state', 0)
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('system_user.user_id', $user_id)
        ->first()->timses_id;
        // dd($timses_id);

        $coretimsesmember = CoreTimsesMember::select('core_timses_member.*')
        // ->join('system_user', 'system_user.user_id', '=', 'core_timses_member.user_id')
        ->where('core_timses_member.data_state','=',0)
        ->where('core_timses_member.timses_id', $timses_id)
        ->get();

        $timses_name = CoreTimses::where('core_timses.data_state', 0)
        ->join('system_user', 'core_timses.user_id', '=', 'system_user.user_id')
        ->where('system_user.user_id', $user_id)
        ->first()->timses_name;

        $spreadsheet = new Spreadsheet();

        if(count($coretimsesmember)>=0){
            $spreadsheet->getProperties()->setCreator("Laporan Data Anggota Timses")
                                        ->setLastModifiedBy("Laporan Data Anggota Timses")
                                        ->setTitle("Laporan Data Anggota Timses")
                                        ->setSubject("")
                                        ->setDescription("Laporan Data Anggota Timses")
                                        ->setKeywords("Report")
                                        ->setCategory("Laporan Data Anggota Timses");
                                        
            $sheet = $spreadsheet->getActiveSheet(0);
            $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    
            $spreadsheet->getActiveSheet()->mergeCells("B1:I1");
            $spreadsheet->getActiveSheet()->mergeCells("B2:I2");
            $spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(16);
            $spreadsheet->getActiveSheet()->getStyle('B4:I4')->getFont()->setBold(true);

            $spreadsheet->getActiveSheet()->getStyle('B4:I4')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $spreadsheet->getActiveSheet()->getStyle('B4:I4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $sheet->setCellValue('B1',"Laporan Data Anggota Timses");	
            $sheet->setCellValue('B2', "Nama Timses    : ". $timses_name);	
            $sheet->setCellValue('B2', "Jumlah Anggota    : ". count($coretimsesmember));	
            $sheet->setCellValue('B4',"No");
            $sheet->setCellValue('C4',"Nama Anggota");
            $sheet->setCellValue('D4',"TTL");
            $sheet->setCellValue('E4',"Umur");
            $sheet->setCellValue('F4',"Alamat");
            $sheet->setCellValue('G4',"Agama");
            $sheet->setCellValue('H4',"No. Telp");
            $sheet->setCellValue('I4',"Kelamin");
            
            $j=5;
            $i=6;
            $no=0;

            $gender =[
                ''  => '',
                '1' => 'Laki-laki',
                '2' => 'Perempuan',
            ];
            
            $total_nominal= 0;

            foreach($coretimsesmember as $key=>$val){

                if(is_numeric($key)){
                    
                    $sheet = $spreadsheet->getActiveSheet(0);
                    $spreadsheet->getActiveSheet()->setTitle("Laporan Data Anggota Timses");
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j.':I'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
                    $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $spreadsheet->getActiveSheet()->getStyle('C'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('F'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                    $spreadsheet->getActiveSheet()->getStyle('G'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('H'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $spreadsheet->getActiveSheet()->getStyle('I'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                    $no++;
                    $sheet->setCellValue('B'.$j, $no);
                    $sheet->setCellValue('C'.$j,$val['timses_member_name']);
                    $sheet->setCellValue('D'.$j,$val['timses_member_place_of_birth'].", ".date('d-m-Y', strtotime($val['timses_member_date_of_birth'])));
                    $sheet->setCellValue('E'.$j, $val['timses_member_name']);
                    $sheet->setCellValue('F'.$j, $val['timses_member_address']);
                    $sheet->setCellValue('G'.$j, $val['timses_member_religiaon']);
                    $sheet->setCellValue('H'.$j, $val['timses_member_phone']);
                    $sheet->setCellValue('I'.$j, $gender[$val['timses_member_gender']]);

                    // $total_nominal += $val['financial_flow_nominal'];
                
                }
                $j++;
                $i++;

                // $total_nominal += $nominal;
            }
            // dd($nominal);

            // $spreadsheet->getActiveSheet()->mergeCells('B'.$i.':I'.$i);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
             
            // $spreadsheet->getActiveSheet()->mergeCells('B'.$j.':I'.$j);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getFont()->setBold(true);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j.':I'.$j)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j.':G'.$j)->getFill()->getStartColor()->setRGB('FFFF00');
            // $spreadsheet->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            // $sheet->setCellValue('B'.$j, "Total : ". rupiah($total_nominal));
            $sheet->setCellValue('B'.$j, Auth::user()->name.", ".date('d-m-Y H:i'));
            


            $filename='Laporan_Data_Anggota_Timses.xls';
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
