<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Atendimento;
use Validator;
use DB;
use App\User;
use App\Mesa;

class AtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $attendance = Atendimento::all();

        if(count($attendance) < 1) {
            return response()->json([
                'message' => 'Não há atendimentos para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'attendance' => $attendance
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            DB::beginTransaction();
            $attendance = new Atendimento;

            $data = $request->all();

            $messages = [
                'mes_id.required' => 'A mesa é obrigatória',
                'usu_id.required' => 'O cliente é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'mes_id' => 'required|numeric',
                'usu_id' => 'required|numeric',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $client = User::where('usu_id', $data['usu_id'])
                            ->where('tip_usu_id', 3)
                            ->get();

            if (count($client) < 1) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O cliente selecionado não possui perfil de cliente',
                ], 400);
            }

            $table = Mesa::find($data['mes_id']);

            if (!$table) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa selecionada não existe em nosso sistema',
                ], 400);
            }

            $table = Mesa::where('mes_id', $data['mes_id'])
                        ->where('mes_liberada', 1)
                        ->get();

            if (count($table) < 1) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa selecionada não está livre para uso',
                ], 400);
            }
            
            $table = Mesa::find($data['mes_id']);

            $table->mes_liberada = 2;
    
            $attendance->fill($data);
    
            if($attendance->save() && $table->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Atendimento adicionado com sucesso.',
                    'attendance' => $attendance,
                    'table' => $table
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um atendimento.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um atendimentoo.'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $attendance = Atendimento::find($id);

        if($attendance) {
            return response()->json([
                'attendance' => $attendance,
            ], 200);
        }else if (!$attendance) {
            return response()->json([
                'message' => 'O atendimento não foi encontrado em nosso sistema.',
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            DB::beginTransaction();
            $attendance = Atendimento::find($id);
    
            if(!$attendance) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O atendimento selecionado não existe.'
                ], 404);
            }
            
            if($attendance->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O atendimento foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o atendimento selecionado.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o atendimento selecionado.'
            ], 400);
        }
    }

    public function freeTable(Request $request, $id) {
        try {
            DB::beginTransaction();
            
            $attendance = Atendimento::find($id);

            if (!$attendance) {
                return response()->json([
                    'message' => 'O atendimento não foi encontrado em nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();

            $messages = [
                'fun_id.required' => 'O funcionário é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'fun_id' => 'required|numeric',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $employee = User::where('usu_id', $data['fun_id'])
                            ->where('tip_usu_id', 2)
                            ->get();

            if (count($employee) < 1) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O Garçom selecionado não possui perfil de garçom',
                ], 400);
            }

            $table = Mesa::find($attendance->mes_id);

            if (!$table) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa selecionada não existe em nosso sistema',
                ], 400);
            }

            $table = Mesa::where('mes_id', $attendance->mes_id)
                        ->where('mes_liberada', 2);

            if (!$table) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa selecionada não tem solicitação de um cliente',
                ], 400);
            }

            $table = Mesa::find($attendance->mes_id);
    
            $table->mes_liberada = 0;

            $attendance->fun_id = $data['fun_id'];
    
            if($attendance->save() && $table->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Mesa liberada para atendimento com sucesso.',
                    'attendance' => $attendance,
                    'table' => $table
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar liberar uma mesa.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar liberar uma mesa.'
            ], 400);
        }
    }
}
