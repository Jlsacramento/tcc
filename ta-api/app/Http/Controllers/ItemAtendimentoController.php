<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItensAtendimento;
use Validator;
use DB;
use App\Iten;

class ItemAtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $itemsAttendance = ItensAtendimento::all();

        if(count($itemsAttendance) < 1) {
            return response()->json([
                'message' => 'Não há itens atendidos para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'items_attendance' => $itemsAttendance
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
            $itemAttendance = new ItensAtendimento;

            $data = $request->all();

            $messages = [
                'ate_id.required' => 'O atendimento é obrigatório',
                'ite_id.required' => 'O item é obrigatório',
            ];
    
            $validator = Validator::make($request->all(), [
                'ate_id' => 'required|numeric',
                'ite_id' => 'required|numeric'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            $iten = Iten::find($data['ite_id']);

            if(!$iten) {
                return response()->json([
                    'message' => 'O item selecionado não existe em nosso sistema.'
                ], 404);
            }

            $itemAttendance->ite_preco = $iten->ite_preco;
    
            $itemAttendance->fill($data);
    
            if($itemAttendance->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Item atendido adicionado com sucesso.',
                    'item_attendance' => $itemAttendance
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um item atendido.'
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
        $itemAttendance = ItensAtendimento::find($id);

        if($itemAttendance) {
            return response()->json([
                'item_attendance' => $itemAttendance,
            ], 200);
        }else if (!$itemAttendance) {
            return response()->json([
                'message' => 'O item atendido não foi encontrado em nosso sistema.',
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
    public function update(Request $request, $id)
    {
        //
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
            $items_attendance = ItensAtendimento::find($id);
    
            if(!$items_attendance) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O item atendido selecionado não existe.'
                ], 404);
            }
            
            if($items_attendance->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O item atendido foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o item atendido selecionado.'
                ], 401);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o item atendido selecionado.'
            ], 401);
        }
    }
}
