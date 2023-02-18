<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{DestinationAccount,DestinationPayments,Transaction};
use App\Helpers\Api as ApiHelper;
use App\Traits\ApiController;
use App\Http\Resources\Data;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{	
	 use ApiController;

    public function store_payments_origin(Request $request)
    {
        $resource = ApiHelper::resource();

        try{

            $transaction = new Transaction;
            $transaction->login = $request['user']['login'];
            $transaction->origen_codpais = $request['cod_pais1'];
            $transaction->destino_codpais = $request['cod_pais2'];
            $transaction->origen_monto = $request['monto1'];
            $transaction->destino_monto = $request['monto2'];
            $transaction->monto_dolares = $request['monto3'];
            $transaction->tasa_dolar_origen = $request['tasa_origen'];
            $transaction->tasa_dolar_destino = $request['tasa_destino'];
            $transaction->id_formapago_origen = $request['origen']['id_formapago'];
            $transaction->status_PO = 'OK';
            $transaction->status_PD = 'PD_OK';
            $transaction->save();

            $transaction = Transaction::where('id',$transaction->id)->first();
           	
           	return response()->json([
           		'resultado' => 'EXITO',
           		'mensaje' => 'Datos de transacción guardados', 
           		'id_trans' => $transaction->id,
           		'transaction' => $transaction
           	]);

        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function store_destination_payments(Request $request)
    {
        $resource = ApiHelper::resource();

        try{

            $destination_payments = new DestinationPayments;
            $destination_payments->id_transaccion = $request['id_transaccion'];
            $destination_payments->login = $request['user']['login'];
            $destination_payments->cod_pais = $request['cod_pais2'];
            $destination_payments->monto = $request['monto2'];
            $destination_payments->cod_banco = $request['destino']['cod_banco'];
            $destination_payments->nroctabank = $request['destino']['nrocta'] ;
            $destination_payments->tipo_cta = $request['destino']['tipo_cta'];
            $destination_payments->doc_titular = $request['destino']['doctitular'];
            $destination_payments->nombre_titular = $request['destino']['nombretitular'];
            $destination_payments->email = $request['destino']['email'];
            $destination_payments->telefono =  $request['destino']['telefono'];
            $destination_payments->observ_user = $request['destino']['observ'];
            $destination_payments->save();

            $destination_payments = DestinationPayments::where('id',$destination_payments->id)->first();
            
            $destination_account = DestinationAccount::where('nrocta',$request['destino']['nrocta'])->first();
            
            if (is_null($destination_account)) {

                $destination_account = new DestinationAccount;
                $destination_account->cod_banco = $request['destino']['cod_banco'];
                $destination_account->nrocta = $request['destino']['nrocta'] ;
                $destination_account->tipo_cta = $request['destino']['tipo_cta'];
                $destination_account->doctitular = $request['destino']['doctitular'];
                $destination_account->nombretitular = $request['destino']['nombretitular'];
                $destination_account->email = $request['destino']['email'];
                $destination_account->telefono =  $request['destino']['telefono'];
                $destination_account->save();
            }

            return response()->json([
              'resultado' => 'EXITO',
              'mensaje' => 'Datos de transacción guardados', 
              'id_pago_destino' => $destination_payments->id,
              'destination_payments' => $destination_payments,
              'destination_account' => $destination_account
            ]);

        }catch(\Exception $e){
          ApiHelper::setException($resource, $e);
        }

        return $this->sendResponse($resource);
    }

    public function get_rate(Request $request)
    {
    	$resource = ApiHelper::resource();

        try{

          $ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL, 'https://api.yadio.io/json'); 
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    			curl_setopt($ch, CURLOPT_HEADER, 0); 
    			$data = curl_exec($ch); 
    			
    			if($data === false)
    			{
    			    return response()->json(['error' => curl_error($ch)],500);
    			}

    			curl_close($ch); 
    			
    			return response()->json(['records' => [json_decode($data)]]);
		    
        }catch(\Exception $e){
          	ApiHelper::setException($resource, $e);
        }
	
	    return $this->sendResponse($resource);
    }

    public function get_rate_by_currency(Request $request,$currency_id)
    {
    	$resource = ApiHelper::resource();

        try{

          $ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, 'https://api.yadio.io/rate/'.$currency_id); 
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        	curl_setopt($ch, CURLOPT_HEADER, 0); 
        	$data = curl_exec($ch); 
        	
        	if($data === false)
        	{
        	    return response()->json(['error' => curl_error($ch)],500);
        	}

        	curl_close($ch); 
        	
        	return response()->json(['records' => [json_decode($data)]]);
        }catch(\Exception $e){
          	ApiHelper::setException($resource, $e);
        }
	
	    return $this->sendResponse($resource);
    }
}
