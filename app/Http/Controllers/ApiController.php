<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
Use App\Models\User;
Use App\Models\SystemLoginLog;
Use App\Models\Program;
Use App\Models\CoreCandidate;
Use App\Models\CoreTimsesMember;
Use App\Models\FinancialFlow;
Use App\Models\DocumentationProgram;

class ApiController extends Controller
{

    public function __construct()
    {
    }

    public function login(Request $request){
        $fields = $request->validate([
            'username'   => 'required|string',
            'password'   => 'required|string',
        ]);

        $user = User::join('system_user_group', 'system_user_group.user_group_id', 'system_user.user_group_id')
        ->join('preference_company', 'preference_company.company_id', 'system_user.company_id')
        ->where('name', $fields['username'])
        ->first();

        if (empty($user)) {
            return response(['message' => 'Username tidak ditemukan',401]);
        }

        if(!Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Password salah'
            ],401);
        }

        
        $login_log = array(
            'user_id'          => $user['user_id'],
            'company_id'       => $user['company_id'],
            'log_time'         => date("Y-m-d H:i:s"),
            'log_status'       => 0,
            'created_at'       => date("Y-m-d H:i:s")
        );

        SystemLoginLog::create($login_log);
        
        $token = $user->createToken('token-name')->plainTextToken;
        $response = [
            'data'  => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function logout(Request $request){

        $login_log = array(
            'user_id'          => $request['user_id'],
            'company_id'       => $request['company_id'],
            'log_time'         => date("Y-m-d H:i:s"),
            'log_status'       => 1,
            'created_at'       => date("Y-m-d H:i:s")
        );

        SystemLoginLog::create($login_log);

        return [
            'message' => 'Logged Out'
        ];
    }

    public function changePasswordUser(Request $request){
        $fields = $request->validate([
            'old_password'  => 'required',
            'new_password'  => 'required',
            'user_id'       => 'required',
        ]);

        $user = User::findOrFail($fields['user_id']);
        
        if(!Hash::check($fields['old_password'], $user->password)){ 
            return response([
                'message' => 'Password lama tidak sesuai'
            ],401);
        }

        $user->password = Hash::make($fields['new_password']);
        if($user->save()){
            return response([
                'message' => 'Ganti Password Berhasil',
                'statusCode' =>  201
            ],201);
        }else{
            return response([
                'message' => 'Ganti Password Tidak Berhasil',
                'statusCode' => 401
            ],401);
        }
    }

    public function getProgram(){
    
        $data = Program::where('data_state',0)
        ->get();

        return json_encode($data);
    }

    public function postProgram(Request $request){
        
        $data = array(
            // 'location_id'                    => $fields['location_id'], 
            // 'period_id'                      => $fields['period_id'], 
            // 'candidate_id'                   => $candidate_id, 
            'timses_member_id'               => $request['timses_member_id'], 
            'program_organizer'              => 2, 
            'program_name'                   => $request['program_name'], 
            'program_description'            => $request['program_description'], 
            'program_address'                => $request['program_address'], 
            'program_date'                   => $request['program_date'], 
            'program_fund'                   => $request['program_fund'], 
            'created_id'                     => $request['timses_member_id'],
            'created_at'                     => date('Y-m-d'),
        );
        // dd($data);                  

        // if($request->program_organizer == 1){
        //     $candidate_id = CoreCandidate::select('candidate_id')
        //     ->where('data_state','=',0)->first()->candidate_id;

        //     $timses_member_id = null;
        // }else{
        //     $candidate_id = null;
        //     $timses_member_id = $request['timses_member_id'];
        // }

        $program_id = Program::orderBy('program_id', 'DESC')->first()->program_id;

        $data_financial_flow = [
            'program_id'                     => $program_id + 1,
            'financial_category_id'          => 8,
            'financial_category_type'        => 2,
            // 'candidate_id'                   => $candidate_id, 
            'timses_member_id'               => $request['timses_member_id'], 
            'financial_flow_nominal'         => $request['program_fund'],
            'financial_flow_description'     => $request['program_description'],
            'financial_flow_date'            => $request['program_date'],
            'created_id'                     => $request['created_id'],
            'created_at'                     => date('Y-m-d'),
        ];

        // dd($data);

        $core_candidate_id = CoreCandidate::select('candidate_id')
        ->where('data_state','=',0)->first()->candidate_id;
        if($request->program_organizer == 1){
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

            $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
            $last_balance_candidate->last_balance -= $request['program_fund'];
            $last_balance_candidate->save();

        }else{
            $candidate_id = CoreCandidate::select('candidate_id')
            ->where('data_state','=',0)->first()->candidate_id;

            $timses_member_id = $request['timses_member_id'];

            $last_balance_candidate = CoreCandidate::findOrFail($candidate_id);
            $last_balance_candidate->last_balance -= $request['program_fund'];
            $last_balance_candidate->save();

            $last_balance_timses = CoreTimsesMember::findOrFail($request['timses_member_id']);
            $last_balance_timses->last_balance -= $request['program_fund'];
            $last_balance_timses->save();
        }

        $data_program = Program::create($data);
        $data_financialflow = FinancialFlow::create($data_financial_flow);

        return $data_program;

    }

    public function postDocumentationProgram(Request $request){

        // $program_id = Program::orderBy('program_id', 'DESC')->first()->program_id;

        $request->hasFile('program_documentation_file');
            $resorce            = $request->file('program_documentation_file');
            $program_documentation_file    = time().'_'.$resorce->getClientOriginalName();
            $resorce->storeAs('public/program_documentation_file', $program_documentation_file);
        
        $data = array(
            'program_id'                     => $request['program_id'],
            'program_documentation_file'     => $program_documentation_file, 
            'created_id'                     => $request['created_id'],
            'created_at'                     => date('Y-m-d'),
        );

        $data_documentation_program = DocumentationProgram::create($data);

        return $data_documentation_program;

    }
}
