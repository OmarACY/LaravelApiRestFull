<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = user::USUARIO_REGULAR;

        $usuario = User::create($campos);

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $usuario = User::findOrFail($user->id);

        return $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = User::findOrFail($user->id);

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $rules);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if(!$user->esVerificado()){
                return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
            }

            $user->admin = $request->admin;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token | User::generarVerificationToken();
            $user->email = $request->email;
        }

        if(!$user->isDirty()){
            return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar', 'code' => 422], 422);
        }

        $user->save();

        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);

        $user->delete();

        return $this->showOne($user);
    }
}
