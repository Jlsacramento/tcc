<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoIten;
use Validator;
use DB;

class TipoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tipoItens = TipoIten::all();

        if(count($tipoItens) < 1) {
            return response()->json([
                'message' => 'Não há tipos de itens para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'type_itens' => $tipoItens
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
            $typeIten = new TipoIten;

            $data = $request->all();

            $messages = [
                'tip_ite_nome.required' => 'O nome do Tipo do Item é obrigatório',
                'tip_ite_nome.unique' => 'Esse Tipo de Item já existe em nosso sistema',
                'tip_ite_nome.max' => 'O tamanho máximo do Tipo do Item são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'tip_ite_nome' => 'required|unique:tipo_itens|max:45',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $typeIten->fill($data);
    
            if($typeIten->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Tipo de Item adicionado com sucesso.',
                    'type_iten' => $typeIten
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um Tipo de Item.'
                ], 400);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um Tipo de Item.'
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
        $typeIten = TipoIten::find($id);

        if($typeIten) {
            return response()->json([
                'type_iten' => $typeIten,
            ], 200);
        }else if (!$typeIten) {
            return response()->json([
                'message' => 'O Tipo de Item não foi encontrado no nosso sistema.',
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
            
            $typeIten = TipoIten::find($id);

            if (!$typeIten) {
                return response()->json([
                    'message' => 'O Tipo de Item selecionado não foi encontrada no nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();

            $messages = [
                'tip_ite_nome.required' => 'O nome do Tipo do Item é obrigatório',
                'tip_ite_nome.max' => 'O tamanho máximo do Tipo do Item são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'tip_ite_nome' => 'required|max:45',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $typeIten->fill($data);
    
            if($typeIten->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Tipo de Item atualizado com sucesso.',
                    'type_iten' => $typeIten
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar atualizar um Tipo de Item.'
                ], 400);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar um Tipo de Item.'
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
            $typeIten = TipoIten::find($id);
    
            if(!$typeIten) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O Tipo do Item selecionado não existe.'
                ], 404);
            }
            
            if($typeIten->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O Tipo do Item foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o Tipo do Item selecionado.'
                ], 401);
            }
          }
          catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o Tipo do Item selecionado.'
            ], 401);
          }
    }
}
