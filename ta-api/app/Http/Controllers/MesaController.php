<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mesa;
use Validator;
use DB;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tables = Mesa::all();

        if(count($tables) < 1) {
            return response()->json([
                'message' => 'Não há mesas para serem exibidas.'
            ], 404);
        }

        return response()->json([
            'tables' => $tables
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

            $table = new Mesa;
    
            $data = $request->all();
    
            $messages = [
                'mes_nome.required' => 'O nome da mesa é obrigatório',
                'mes_nome.unique' => 'Essa mesa já existe em nosso sistema',
                'mes_nome.max' => 'O tamanho máximo do nome da mesa são 45 caracteres',
            ];
    
            $validator = Validator::make($request->all(), [
                'mes_nome' => 'required|unique:mesas|max:45',
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $table->mes_liberada = 1;
    
            $table->fill($data);
    
            if($table->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Mesa adicionada com sucesso.',
                    'tables' => $table
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar uma mesa.'
                ], 401);
            }           
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar uma mesa.'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $table = Mesa::find($id);

        if($table) {
            return response()->json([
                'table' => $table,
            ], 200);
        }else if (!$table) {
            return response()->json([
                'message' => 'A mesa não foi encontrada no nosso sistema.',
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

            $table = Mesa::find($id);
    
            if (!$table) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa não foi encontrada no nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();
    
            $messages = [
                'mes_nome.required' => 'O nome da mesa é obrigatório',
                'mes_nome.max' => 'O tamanho máximo do nome da mesa são 45 caracteres',
                'mes_liberada.required' => 'O status da mesa é obrigatório',
                'mes_liberada.numeric' => 'No status só é permitido números',
            ];
    
            $validator = Validator::make($request->all(), [
                'mes_nome' => 'required|max:45',
                'mes_liberada' => 'required|numeric'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $table->fill($data);
    
            if($table->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Mesa atualizada com sucesso.',
                    'table' => $table
                ], 200);
            }else {
                return response()->json([
                    'message' => 'Erro ao tentar atualizar a mesa selecionada.'
                ], 400);
            }
                    
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar a mesa selecionada.'
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
            $table = Mesa::find($id);
    
            if(!$table) {
                DB::rollBack();
                return response()->json([
                    'message' => 'A mesa selecionada não existe.'
                ], 404);
            }
            
            if($table->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'A mesa foi deletada com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar a mesa selecionada.'
                ], 400);
            }            
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar a mesa selecionada.'
            ], 401);
        }
    }

    public function freeTables() {
        $tables = Mesa::where('mes_liberada', 1)
                        ->get();

        if(count($tables) < 1) {
            return response()->json([
                'message' => 'Não há mesas para serem exibidas.'
            ], 404);
        }

        return response()->json([
            'tables' => $tables
        ], 200);
    }
}
