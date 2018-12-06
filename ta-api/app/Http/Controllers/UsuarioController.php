<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::all();

        if(count($users) < 1) {
            return response()->json([
                'message' => 'Não há usuários para serem exibidos.'
            ], 404);
        }

        return response()->json([
            'users' => $users
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
            $user = new User;

            $data = $request->all();

            $messages = [
                'usu_nome.required' => 'O nome do usuário é obrigatório',
                'usu_nome.max' => 'O tamanho máximo do usuário são 45 caracteres',
                'email.required' => 'O e-mail é obrigatório',
                'email.unique' => 'Esse usuário já existe em nosso sistema',
                'email.email' => 'O e-mail digitado não é um e-mail valido',
                'password.required' => 'A senha é obrigatória',
                'password.confirmed' => 'A duas senhas devem ser iguais',
                'password.min' => 'A senha deve ter no mínimo 6 caracteres',
                'password.max' => 'A senha deve ter no máximo 45 caracteres',
                'password_confirmation.required' => 'A confirmação de senha é obrigatória',
                'tip_usu_id.required' => 'O tipo do usuário é obrigatório',
                'tip_usu_id.numeric' => 'O tipo de usuário deve ser númerico',
            ];
    
            $validator = Validator::make($request->all(), [
                'usu_nome' => 'required|max:45',
                'email' => 'required|email|unique:usuarios',
                'password' => 'required|confirmed|min:6|max:45',
                'password_confirmation' => 'required',
                'tip_usu_id' => 'required|numeric'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $user->fill($data);
            
            if($user->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Usuário adicionado com sucesso.',
                    'user' => $user
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar adicionar um usuário.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar adicionar um usuário.'
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
        $user = User::find($id);

        if($user) {
            return response()->json([
                'user' => $user,
            ], 200);
        }else if (!$user) {
            return response()->json([
                'message' => 'O usuário não foi encontrado em nosso sistema.',
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
            
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'O usuário não foi encontrado em nosso sistema.',
                ], 200);
            }
    
            $data = $request->all();

            $messages = [
                'usu_nome.required' => 'O nome do usuário é obrigatório',
                'usu_nome.max' => 'O tamanho máximo do usuário são 45 caracteres',
                'email.required' => 'O e-mail é obrigatório',
                'email.email' => 'O e-mail digitado não é um e-mail valido',
                'password.required' => 'A senha é obrigatória',
                'password.confirmed' => 'A duas senhas devem ser iguais',
                'password.min' => 'A senha deve ter no mínimo 6 caracteres',
                'password.max' => 'A senha deve ter no máximo 45 caracteres',
                'password_confirmation.required' => 'A confirmação de senha é obrigatória',
                'tip_usu_id.required' => 'O tipo do usuário é obrigatório',
                'tip_usu_id.numeric' => 'O tipo de usuário deve ser númerico',
            ];
    
            $validator = Validator::make($request->all(), [
                'usu_nome' => 'required|max:45',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6|max:45',
                'password_confirmation' => 'required',
                'tip_usu_id' => 'required|numeric'
            ], $messages);
    
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }
    
            $user->fill($data);
    
            if($user->save()) {
                DB::commit();
                return response()->json([
                    'message' => 'Usuário atualizado com sucesso.',
                    'user' => $user
                ], 201);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar atualizar um usuário.'
                ], 400);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar atualizar um usuário.'
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
            $user = User::find($id);
    
            if(!$user) {
                DB::rollBack();
                return response()->json([
                    'message' => 'O usuário selecionado não existe.'
                ], 404);
            }
            
            if($user->delete()) {
                DB::commit();
                return response()->json([
                    'message' => 'O usuário foi deletado com sucesso.'
                ], 200);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao tentar deletar o usuário selecionado.'
                ], 401);
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao tentar deletar o usuário selecionado.'
            ], 401);
        }
    }
}
