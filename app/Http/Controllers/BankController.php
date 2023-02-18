<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Helpers\Api as ApiHelper;
use App\Traits\ApiController;
use App\Http\Resources\Data;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class BankController extends Controller
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

            $banks = Bank::orderBy('codpais')->orderBy('nombre')->get();

            return response()->json(['records' => $banks]);
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function byCountry(Request $request,$country)
    {
        $resource = ApiHelper::resource();

        try{

            $banks = Bank::where('codpais',$country)->orderBy('nombre')->get();

            return response()->json(['records' => $banks]);
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function byCountryUser(Request $request,$country)
    {
        $resource = ApiHelper::resource();

        try{

            $banks = Bank::where('codpais',$country)->orderBy('nombre')->get();

            return response()->json(['records' => $banks]);
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

            $bank = new Bank;
            $bank->code = $request->code;
            $bank->name = $request->name;
            $bank->country = $request->country;
            $bank->type_payment = $request->type_payment;
            $bank->observation = $request->observation;
            $bank->save();
            
            
            $data  =  new Data($bank);
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

          $bank = Bank::select(
                'formapago_origen.id',
                'formapago_origen.codigo',
                'formapago_origen.nombre',
                'formapago_origen.nombretitular',
                'formapago_origen.doctitular',
                'formapago_origen.nrocuenta',
                'formapago_origen.tipocuenta',
                'formapago_origen.nombre_largo as descripcion',
                'formapago_origen.tipocuenta_desc',
                'formapago_origen.ciudad',
                'banks_pais.codpais',
                'banks_pais.tipo_pago',
                'banks_pais.nombre as nombrebank'
            )
            ->join('formapago_origen','formapago_origen.cod_banco','banks_pais.codigo')
            ->where('formapago_origen.activo',1)
            ->where('banks_pais.activo',1)
            ->where('banks_pais.codigo',$id)
            ->first();
            
            return response()->json(['records' => [$bank]]);
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

            $bank = Bank::find($id);
            $bank->code = $request->code;
            $bank->name = $request->name;
            $bank->country = $request->country;
            $bank->type_payment = $request->type_payment;
            $bank->observation = $request->observation;
            $bank->save();

            $data  =  new Data($bank);
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

            $bank = Bank::where('id',$id)->update(['is_active' => false]);

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

            $bank = Bank::where('id',$id)->update(['is_active' => true]);

            $resource = array_merge($resource, ['data' => []]);
          ApiHelper::success($resource);
        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }
}
