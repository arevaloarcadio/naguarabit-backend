<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Helpers\Api as ApiHelper;
use App\Traits\ApiController;
use App\Http\Resources\Data;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $resource = ApiHelper::resource();

        try{

            $validator = \Validator::make($request->all(),[
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

            $transaction = new Transaction;

            $data  =  new Data($user);
            $resource = array_merge($resource, $data->toArray($request));
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }
}
