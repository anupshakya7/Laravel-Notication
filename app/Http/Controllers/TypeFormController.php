<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TypeFormController extends Controller
{
    public function index(){
        return view('typeform.index');
    }

    public function getForm($formId){
        $token = config('services.typeform.access_token');
        
        $response = Http::withToken($token)->get("https://api.typeform.com/forms/{$formId}");
        
        if($response->successful()){
            return $response->json();
        }

        return response()->json([
            'error'=>'Unable to Fetch Form',
            'details'=>$response->json()
        ],$response->status());
    }

    public function edit(Request $request){
        
    }
}
