<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServicosAtendimento;
use Validator;
use DB;
use App\Servico;

class ServicoAtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $servicesAttendance = ServicosAtendimento::all();

        if(count($servicesAttendance) < 1) {
            return response()->json([
                'message' => 'Não há serviços atendidos para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'services_attendance' => $servicesAttendance
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
            DB::beginTransaction();
            $serviceAttendance = new ServicosAtendimento;

            $data = $request->all();

            $messages = [
                'ate_id.required' => 'O atendimento é obrigatório',
                'ser_id.required' => 'O serviço é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'ate_id' => 'required|numeric',
                'ser_id' => 'required|numeric'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $service = Servico::find($data['ser_id']);

            if(!$service) {
                return response()->json([
                    'message' => 'O serviço selecionado não existe em nosso sistema.'
                ], 404);
            }

            $serviceAttendance->ser_preco = $service->ser_preco;
    
            $serviceAttendance->fill($data);
    
            if($serviceAttendance->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Serviço atendido adicionado com sucesso.',
                    'service_attendance' => $serviceAttendance
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um Serviço Atendido.'
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
        $servicesAttendance = ServicosAtendimento::find($id);

        if($servicesAttendance) {
            return response()->json([
                'service_attendance' => $servicesAttendance,
            ], 200);
        }else if (!$servicesAttendance) {
            return response()->json([
                'message' => 'O serviço atendido não foi encontrado em nosso sistema.',
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
            $servicesAttendance = ServicosAtendimento::find($id);
    
            if(!$servicesAttendance) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O serviço atendido selecionado não existe.'
                ], 404);
            }
            
            if($servicesAttendance->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O serviço atendido foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o serviço atendido selecionado.'
                ], 401);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o serviço atendido selecionado.'
            ], 401);
        }
    }
}
