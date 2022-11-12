<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'jwt', ['except' => ['login']]
        );
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
      

        $credentials = $request->only('email', 'password');

        $user = User::where('email',$request->email)->where('activo',true)->first();

        try {

            if (!$token = JWTAuth::attempt($credentials)) {
                     
                $user = User::where('user_name',$request->email)->where('activo',true)->first();
               
                if (!is_null($user)) {
                    
                    $credentials = [
                        'email' => $user->email,
                        'password' => $request->password
                    ];
                    
                    if (!$token = JWTAuth::attempt($credentials)) {
                       return response()->json(['error' => 'invalid_credentials'], 400);   
                    }
                
                }else{
                    return response()->json(['error' => 'invalid_credentials'], 400); 
                }
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        
        return $this->respondWithToken($token,$user);
    }
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh(),auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token,$user): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
