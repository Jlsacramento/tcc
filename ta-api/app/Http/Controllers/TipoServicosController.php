<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoServico;
use Validator;
use DB;

class TipoServicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $typeService = TipoServico::all();

        if(count($typeService) < 1) {
            return response()->json([
                'message' => 'Não há tipos de serviços para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'type_services' => $typeService
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
            $typeService = new TipoServico;

            $data = $request->all();

            $messages = [
                'tip_ser_nome.required' => 'O nome do Tipo do Serviço é obrigatório',
                'tip_ser_nome.unique' => 'Esse Tipo de Serviço já existe em nosso sistema',
                'tip_ser_nome.max' => 'O tamanho máximo do Tipo do Serviço são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'tip_ser_nome' => 'required|unique:tipo_servicos|max:45',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator,
                ], 400);
            }
    
            $typeService->fill($data);
    
            if($typeService->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Tipo de Serviço adicionado com sucesso.',
                    'type_service' => $typeService
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um Tipo de Serviço.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um Tipo de Serviço.'
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
        $typeService = TipoServico::find($id);

        if($typeService) {
            return response()->json([
                'type_service' => $typeService,
            ], 200);
        }else if (!$typeService) {
            return response()->json([
                'message' => 'O Tipo de Serviço não foi encontrado no nosso sistema.',
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
            
            $typeService = TipoServico::find($id);

            if (!$typeService) {
                return response()->json([
                    'message' => 'O Tipo de Serviço selecionado não foi encontrado em nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();

            $messages = [
                'tip_ser_nome.required' => 'O nome do Tipo do Serviço é obrigatório',
                'tip_ser_nome.unique' => 'Esse Tipo de Serviço já existe em nosso sistema',
                'tip_ser_nome.max' => 'O tamanho máximo do Tipo do Serviço são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'tip_ser_nome' => 'required|unique:tipo_servicos|max:45',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator,
                ], 400);
            }
    
            $typeService->fill($data);
    
            if($typeService->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Tipo de Serviço atualizado com sucesso.',
                    'type_iten' => $typeService
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar atualizar um Tipo de Serviço.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar um Tipo de Serviço.'
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
            $typeService = TipoServico::find($id);
    
            if(!$typeService) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O Tipo do Serviço selecionado não existe.'
                ], 404);
            }
            
            if($typeService->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O Tipo do Serviço foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o Tipo do Serviço selecionado.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o Tipo do Serviço selecionado.'
            ], 400);
        }
    }
}
