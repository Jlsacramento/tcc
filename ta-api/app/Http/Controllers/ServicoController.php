<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servico;
use Validator;
use DB;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $services = Servico::all();

        if(count($services) < 1) {
            return response()->json([
                'message' => 'Não há serviços para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'services' => $services
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            DB::beginTransaction();
            $service = new Servico;

            $data = $request->all();

            $messages = [
                'ser_nome.required' => 'O nome do Serviço é obrigatório',
                'ser_nome.unique' => 'Esse Serviço já existe em nosso sistema',
                'ser_nome.max' => 'O tamanho máximo do Serviço são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'ser_nome' => 'required|unique:servicos|max:45',
                'ser_preco' => 'required'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $service->fill($data);
    
            if($service->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Serviço adicionado com sucesso.',
                    'type_service' => $service
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um Serviço.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um Serviço.'
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
        $service = Servico::find($id);

        if($service) {
            return response()->json([
                'service' => $service,
            ], 200);
        }else if (!$service) {
            return response()->json([
                'message' => 'O serviço não foi encontrado em nosso sistema.',
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
        try {
            DB::beginTransaction();
            
            $service = Servico::find($id);

            if (!$service) {
                return response()->json([
                    'message' => 'O serviço não foi encontrado em nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();

            $messages = [
                'ser_nome.required' => 'O nome do Serviço é obrigatório',
                'ser_nome.unique' => 'Esse Serviço já existe em nosso sistema',
                'ser_nome.max' => 'O tamanho máximo do Serviço são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'ser_nome' => 'required|unique:servicos|max:45',
                'ser_preco' => 'required'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $service->fill($data);
    
            if($service->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Serviço atualizado com sucesso.',
                    'type_iten' => $service
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar atualizar um serviço.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar um serviço.'
            ], 400);
        }
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
            $service = Servico::find($id);
    
            if(!$service) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O serviço selecionado não existe.'
                ], 404);
            }
            
            if($service->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O serviço foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o serviço selecionado.'
                ], 401);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o serviço selecionado.'
            ], 401);
        }
    }
}
