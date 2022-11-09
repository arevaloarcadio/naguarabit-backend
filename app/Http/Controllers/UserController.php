<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\Api as ApiHelper;
use App\Traits\ApiController;
use App\Http\Resources\Data;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class UserController extends Controller
{
    use ApiController;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = ApiHelper::resource();

        try{

            $users = User::paginate(10);

            $data  =  new Data($users);
            $resource = array_merge($resource, $data->toArray($request));
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resource = ApiHelper::resource();

        try{

            $validator= \Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'user_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'nro_document' => 'required|string|max:20',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'observation' => 'nullable|string|max:255',
                'url_img_document' => 'nullable',
                'url_avatar' => 'nullable',
                'password' => 'required|confirmed',
            ]);

            if($validator->fails()){
                ApiHelper::setError($resource, 0, 422, $validator->errors());
                return $this->sendResponse($resource);
            }

            $user = new User;
            $user->name = $request->name;
            $user->user_name = $request->user_name;
            $user->email = $request->email;
            $user->nro_document = $request->nro_document;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->city = $request->city;
      			$user->observation = $request->observation;
      			$user->url_avatar = $request->url_avatar;
      			$user->url_img_document = $request->url_img_document;
            $user->is_admin  = 0;
            $user->is_active = 1;
            $user->password  = Hash::make($request->password);
            $user->save();
            
            /*if ($request->login) {
              
              $credentials = [
                'email' => $user->email,
                'password' => $request->password
              ];
              
              $token = JWTAuth::attempt($credentials);
              
              return $this->respondWithToken($token,$user);
            }*/

            $data  =  new Data($user);
            $resource = array_merge($resource, $data->toArray($request));
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
      $resource = ApiHelper::resource();

      try{

          $user = User::find($id);

          $data  =  new Data($user);
          $resource = array_merge($resource, ['data' => $user]);
        ApiHelper::success($resource);
      }catch(\Exception $e){
        ApiHelper::setException($resource, $e);
      }

      return $this->sendResponse($resource);
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
        $resource = ApiHelper::resource();

        try{

            $validator= \Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'user_name' => 'required|string|max:255|unique:users,user_name,'.$id,
                'email' => 'required|email|max:255|unique:users,email,'.$id,
                'nro_document' => 'required|string|max:20',
                'phone' => 'required|string|max:20',
                'country' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'observation' => 'nullable|string|max:255',
                'url_img_document' => 'nullable',
                'url_avatar' => 'nullable',
                'password' => 'required|confirmed',
            ]);

            if($validator->fails()){
                ApiHelper::setError($resource, 0, 422, $validator->errors());
                return $this->sendResponse($resource);
            }

            $user =  User::find($id);
            $user->name = $request->name;
            $user->user_name = $request->user_name;
            $user->email = $request->email;
            $user->nro_document = $request->nro_document;
            $user->phone = $request->phone;
            $user->country = $request->country;
            $user->city = $request->city;
      			$user->observation = $request->observation;
      			$user->url_avatar = $request->url_avatar;
      			$user->url_img_document = $request->url_img_document;
            $user->is_admin  = 0;
            $user->is_active = 1;
            $user->password  = !is_null($request->password) ? Hash::make($request->password) : $user->password;
            $user->save();

            $data  =  new Data($user);
            $resource = array_merge($resource, $data->toArray($request));
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = ApiHelper::resource();

        try{

            $user = User::where('id',$id)->update(['is_active' => false]);

            $resource = array_merge($resource, ['data' => []]);
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function active($id)
    {
        $resource = ApiHelper::resource();

        try{

            $user = User::where('id',$id)->update(['is_active' => true]);

            $resource = array_merge($resource, ['data' => []]);
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }
}
