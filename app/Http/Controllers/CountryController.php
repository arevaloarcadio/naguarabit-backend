<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\EntityComment;
use App\Helpers\Api as ApiHelper;
use App\Traits\ApiController;
use App\Http\Resources\Data;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CountryController extends Controller
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

            $countries = Country::orderBy('nombre')->get();

            return response()->json(['records' => $countries]);
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
                'code' => 'required|string|max:255|unique:banks,code',
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'type_payment' => 'required|string|max:255',
                'observation' => 'nullable|string|max:255',
            ]);


            if($validator->fails()){
                ApiHelper::setError($resource, 0, 422, $validator->errors());
                return $this->sendResponse($resource);
            }

            $country = new Country;
            $country->code = $request->code;
            $country->name = $request->name;
            $country->country = $request->country;
            $country->type_payment = $request->type_payment;
            $country->observation = $request->observation;
            $country->save();
            
            
            $data  =  new Data($country);
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

          $country = Country::find($id);

          $data  =  new Data($country);
          $resource = array_merge($resource, ['data' => $country]);
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
                'code' => 'required|string|max:255|unique:banks,code,'.$id,
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'type_payment' => 'required|string|max:255',
                'observation' => 'nullable|string|max:255',
            ]);

            if($validator->fails()){
                ApiHelper::setError($resource, 0, 422, $validator->errors());
                return $this->sendResponse($resource);
            }

            $country = Country::find($id);

            //TODO. mejora: IMPEDIR la actualizacion del codigo del banco solo cuando tiene transacciones relacionadas
            $country->code = $request->code;

            $country->name = $request->name;

           //TODO. mejora. IMPEDIR actualizacion del pais del banco cuando YA TIENE transacciones relacionadas
           //si el banco fue registrado en un pais equivocado, debe borrarse
            $country->country = $request->country;

            $country->type_payment = $request->type_payment;
            $country->observation = $request->observation;
            $country->save();

            $data  =  new Data($country);
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

            $country = Country::where('id',$id)->update(['is_active' => false]);

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

            $country = Country::where('id',$id);
            $country->update(['is_active' => true]);

            $resource = array_merge($resource, ['data' => []]);
            ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function activeOff($id)
    {
        $resource = ApiHelper::resource();
        try{
          
          $country = Country::where('id',$id);
          $country->update(['is_active' => false]);
          $resource = array_merge($resource, ['data' => []]);
          ApiHelper::success($resource);
        
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    //TODO.
    // MEJORA: agregar observaciones del banco en tabla aparte, llamada entity_comment
    public function addComment(Request $request, $id){
      try{
        $country = Country::find($id);
        $comment = $request->observation;
        
        $comment = new EntityComment();
        //$comment->save();
  
        $data  =  new Data($country);
        $resource = array_merge($resource, ['data' => $country]);
        ApiHelper::success($resource);
      }catch(\Exception $e){
        ApiHelper::setException($resource, $e);
      }
  
    }

  
}
