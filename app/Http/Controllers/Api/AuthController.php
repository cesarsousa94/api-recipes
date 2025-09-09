<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(name="Auth", description="Autenticação")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/auth/register",
     *   tags={"Auth"},
     *   summary="Cadastro de usuário",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(required={"name","email","password"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string", format="password")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Usuário criado")
     * )
     */
    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users',
            'password'=>'required|string|min:6',
        ]);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);
        return response()->json(['data'=>$user], 201);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/login",
     *   tags={"Auth"}, summary="Login",
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="email", type="string"),
     *       @OA\Property(property="password", type="string", format="password")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Token gerado")
     * )
     */
    public function login(Request $r)
    {
        $cred = $r->validate(['email'=>'required|email','password'=>'required']);
        $user = User::where('email',$cred['email'])->first();
        if (!$user || !Hash::check($cred['password'], $user->password)) {
            return response()->json(['message'=>'Credenciais inválidas'], 401);
        }
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token'=>$token, 'user'=>$user]);
    }

    /**
     * @OA\Post(
     *   path="/api/auth/logout",
     *   tags={"Auth"}, summary="Logoff (revoga token)",
     *   security={{"sanctum":{}}},
     *   @OA\Response(response=204, description="OK")
     * )
     */
    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
