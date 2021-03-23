<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use DateInterval;
use DateTime;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $data['token'] = $token;
        $data['ttl'] = config('jwt.ttl');
        $data['ttl_type'] = 'minutes';
        $data['createdAt'] = (new DateTime())->format(DateTime::ATOM);
        $data['expiredAt'] = (new DateTime())->add(new DateInterval('PT' . $data['ttl'] . 'M'))->format(DateTime::ATOM);
        return response()->json(compact('data'));
    }


    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } 
        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getCode());
        } 
        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getCode());
        } 
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getCode());
        }
        return response()->json(compact('user'));
    }
}
