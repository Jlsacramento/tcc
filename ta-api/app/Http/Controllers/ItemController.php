<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Iten;
use DB;
use Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $itens = Iten::all();

        if(count($itens) < 1) {
            return response()->json([
                'message' => 'Não há itens para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'itens' => $itens
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
            $iten = new Iten;

            $data = $request->all();

            $messages = [
                'ite_nome.required' => 'O nome do Item é obrigatório',
                'ite_nome.unique' => 'Esse Item já existe em nosso sistema',
                'ite_nome.max' => 'O tamanho máximo do Item são 45 caracteres',
                'ite_preco.required' => 'O nome do Item é obrigatório',
                'tip_ite_id.required' => 'O nome do Item é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'ite_nome' => 'required|unique:itens|max:45',
                'ite_preco' => 'required',
                'tip_ite_id' => 'required'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $iten->fill($data);
    
            if($iten->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Item adicionado com sucesso.',
                    'item' => $iten
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um Item.'
                ], 400);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um Itemi.'
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
        $item = Iten::find($id);

        if($item) {
            return response()->json([
                'item' => $item,
            ], 200);
        }else if (!$item) {
            return response()->json([
                'message' => 'O item não foi encontrada no nosso sistema.',
            ], 404);
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
            
            $item = Iten::find($id);

            if (!$item) {
                return response()->json([
                    'message' => 'O item não foi encontrada no nosso sistema.',
                ], 404);
            }
    
            $data = $request->all();

            $messages = [
                'ite_nome.required' => 'O nome do Item é obrigatório',
                'ite_nome.max' => 'O tamanho máximo do Item são 45 caracteres',
                'ite_preco.required' => 'O nome do Item é obrigatório',
                'tip_ite_id.required' => 'O nome do Item é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'ite_nome' => 'required|max:45',
                'ite_preco' => 'required',
                'tip_ite_id' => 'required'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $item->fill($data);
    
            if($item->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Item atualizado com sucesso.',
                    'item' => $item
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar atualizar um Item.'
                ], 400);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar um Item.'
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
        $item = Iten::find($id);

        if(!$item) {
            return response()->json([
                'message' => 'O item selecionado não existe.'
            ], 404);
        }
        
        if($item->delete()) {
            return response()->json([
                'message' => 'O item foi deletado com sucesso.'
            ], 200);
        }else {
            return response()->json([
                'message' => 'Erro ao tentar deletar o item selecionado.'
            ], 401);
        }
    }
}
