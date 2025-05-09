<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\SubCountry;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Constraint\Count;

class TypeFormController extends Controller
{
    public function index()
    {
        $path = public_path('build/assets/countries_state.json');
        $json = file_get_contents($path);
        $countryStates = json_decode($json, true);

        return view('typeform.index', compact('countryStates'));
    }

    public function index2()
    {
        return view('typeform.index2');
    }

    public function index3()
    {
        return view('typeform.index3');
    }

    public function getForm($formId)
    {
        $token = config('services.typeform.access_token');

        $response = Http::withToken($token)->get("https://api.typeform.com/forms/{$formId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'error' => 'Unable to Fetch Form',
            'details' => $response->json()
        ], $response->status());
    }

    public function edit(Request $request)
    {
        $formData = json_decode($request->typeform_data, true);

        $hasCountryField = collect($formData['fields'])->contains(function ($field) {
            return isset($field['ref']) && $field['ref'] === 'country_field_ref';
        });

        if (!$hasCountryField) {
            try {
                $formId = $formData['id'];
                
                $formGender = $formData['fields'][1]['ref'];
                $formAfterGender = $formData['fields'][2]['ref'];

                $selectedCountries = $request->country;
                $countriesState = json_decode(file_get_contents(public_path('build/assets/countries_state.json')), true);
                $accessToken = config('services.typeform.access_token');

                $fields = [];
                $logic = [];
                $logicCountryState = [];

                $countryChoices = array_map(fn($country) => ['label' => $country], $selectedCountries);
 
                $fields[] = [
                    "ref" => "country_field_ref",
                    "title" => "Which country are your from?",
                    "type" => "dropdown",
                    "properties" => [
                        "choices" => $countryChoices
                    ],
                ];

                foreach ($selectedCountries as $country) {
                    $countryData = collect($countriesState)->firstWhere('name', $country);
                    
                    if (empty($countryData) || empty($countryData['states'])) {
                        continue;
                    }

                    $stateFieldRef = preg_replace('/[^a-z0-9_\-]/', '_', strtolower($country) . '_state_field_ref');

                    $stateField = [
                        "ref" => $stateFieldRef,
                        "title" => "Which state are your from? ($country)",
                        "type" => "dropdown",
                        "properties" => [
                            "choices" => collect($countryData['states'])->map(fn($state) => ['label' => $state['name']])->values()->all()
                        ],
                    ];

                    $fields[] = $stateField;

                    //Logic for State according to Country
                    $logicCountryState[] = [
                        "action" => "jump",
                        "condition" => [
                            "op" => "equal",
                            "vars" => [
                                ["type" => "field", "value" => "country_field_ref"],
                                ["type" => "constant", "value" => $country],
                            ]
                        ],
                        "details" => [
                            "to" => [
                                "type" => "field",
                                "value" => $stateFieldRef,
                            ]
                        ]
                    ];

                    //Logic Redirecting to Thank You Field After Select State
                    $logic[] = [
                        "type" => "field",
                        "ref" => $stateFieldRef,
                        "actions" => [
                            [
                                "action" => "jump",
                                "condition" => [
                                    "op" => "always",
                                    "vars" => [],
                                ],
                                "details" => [
                                    "to" => [
                                        "type" => "field",
                                        "value" => "$formAfterGender"
                                    ]
                                ]
                            ],
                        ]
                    ];
                }


                $logic[] = [
                    "type" => "field",
                    "ref" => $formGender,
                    "actions" => [
                        [
                            "action" => "jump",
                            "condition" => [
                                "op" => "always",
                                "vars" => [],
                            ],
                            "details" => [
                                "to" => [
                                    "type" => "field",
                                    "value" => "country_field_ref",
                                ]
                            ]
                        ],
                    ]
                ];

                $logic[] = [
                    "type" => "field",
                    "ref" => "country_field_ref",
                    "actions" => $logicCountryState
                ];

                $formFields = $formData['fields'];

                $formData['fields']=[];
                
                foreach($formFields as $key=>$formField){
                    if($key == 0 || $key ==1){
                        $formData['fields'][] = $formField;
                    }
                }
                
                foreach ($fields as $field) {
                    $formData['fields'][] = $field;
                }

                foreach($formFields as $key=>$formField){
                    if($key >= 2){
                        $formData['fields'][] = $formField;
                    }
                }

                foreach ($logic as $condition) {
                    $formData['logic'][] = $condition;
                }

                $updateForm = Http::withToken($accessToken)->put("https://api.typeform.com/forms/{$formId}", $formData);

                if($updateForm->successful()){
                    return 'Succesfully Added Country and State Fields';
                }else{
                    return "Fail to Add Field";
                }
            }catch(\Exception $e){
                return $e->getMessage();
            }
        } else {
            return "Already Exists";
        }
    }

    public function edit2(Request $request){
        $formData = json_decode($request->typeform_data);

        try{
            $formId = $formData->id;

            $formGender = $formData->fields[1]->ref;
            $formAfterGender = $formData->fields[2]->ref;

            $regionList = Country::select('id','country')->where('level',0)->get();
            $countryList = Country::select('id','country')->where('level',1)->get();
            $stateList = SubCountry::select('id','geoname')->get();

            $accessToken = config('services.typeform.access_token');
            
            $fields = [];
            $logic = [];
            $logicCountryState = [];

            $regions = $regionList->map(fn($region)=>['label'=>$region->country])->toArray();
            $countries = $countryList->map(fn($country)=>['label'=>$country->country])->toArray();
            $states = $stateList->map(fn($state)=>['label'=>$state->geoname])->toArray();

            if(array_key_exists('region',$request->fields)){
                $fields[] = [
                    "ref" => "region_field_ref",
                    "title" => "Which Region are your from?",
                    "type" => "dropdown",
                    "properties" => [
                        "choices" => $regions
                    ],
                ];
            }

            if(array_key_exists('country',$request->fields)){
                $fields[] = [
                    "ref" => "country_field_ref",
                    "title" => "Which Country are your from?",
                    "type" => "dropdown",
                    "properties" => [
                        "choices" => $countries
                    ],
                ];
            }

            if(array_key_exists('state',$request->fields)){
                $fields[] = [
                    "ref" => "state_field_ref",
                    "title" => "Which State are your from?",
                    "type" => "dropdown",
                    "properties" => [
                        "choices" => $states
                    ],
                ];
            }

            
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function edit3(Request $request)
    {
        $formData = json_decode($request->typeform_data, true);
        $countryCheckbox = array_key_exists('country',$request->fields);
        $stateCheckbox = array_key_exists('state',$request->fields);

        $hasCountryField = collect($formData['fields'])->contains(function ($field) {
            return isset($field['ref']) && $field['ref'] === 'country_field_ref';
        });

        if (!$hasCountryField) {
            try {
                $formId = $formData['id'];
                
                $formGender = $formData['fields'][1]['ref'];
                $formAfterGender = $formData['fields'][2]['ref'];

                // $selectedCountries = $request->country;

                // $countriesState = json_decode(file_get_contents(public_path('build/assets/countries_state.json')), true);
                $countriesState = Country::with(['state'=>function($query){
                    $query->select('geoname','countrycode');
                }])->select('country','country_code')->where('level',1)->get();

                $accessToken = config('services.typeform.access_token');

                $fields = [];
                $logic = [];
                $logicCountryState = [];

                // $countryChoices = array_map(fn($country) => ['label' => $country], $selectedCountries);
                if($countryCheckbox){
                    $countryChoices = $countriesState->map(fn($country)=>['label'=>$country->country])->toArray();
                
                    $fields[] = [
                        "ref" => "country_field_ref",
                        "title" => "Which country are your from?",
                        "type" => "dropdown",
                        "properties" => [
                            "choices" => $countryChoices
                        ],
                    ];
                }

                if($countryCheckbox && $stateCheckbox){
                    foreach ($countriesState as $country) {
                        $stateFieldRef = preg_replace('/[^a-z0-9_\-]/', '_', strtolower($country->country) . '_state_field_ref');
                        $stateChoices = $country->state->map(fn($state)=>['label'=>$state->geoname])->toArray();
    
                        $stateField = [
                            "ref" => $stateFieldRef,
                            "title" => "Which state are your from? ($country->country)",
                            "type" => "dropdown",
                            "properties" => [
                                "choices" => $stateChoices
                            ],
                        ];
    
                        $fields[] = $stateField;
    
                        //Logic for State according to Country
                        $logicCountryState[] = [
                            "action" => "jump",
                            "condition" => [
                                "op" => "equal",
                                "vars" => [
                                    ["type" => "field", "value" => "country_field_ref"],
                                    ["type" => "constant", "value" => $country->country],
                                ]
                            ],
                            "details" => [
                                "to" => [
                                    "type" => "field",
                                    "value" => $stateFieldRef,
                                ]
                            ]
                        ];
    
                        //Logic Redirecting to Field After Select State
                        $logic[] = [
                            "type" => "field",
                            "ref" => $stateFieldRef,
                            "actions" => [
                                [
                                    "action" => "jump",
                                    "condition" => [
                                        "op" => "always",
                                        "vars" => [],
                                    ],
                                    "details" => [
                                        "to" => [
                                            "type" => "field",
                                            "value" => "$formAfterGender"
                                        ]
                                    ]
                                ],
                            ]
                        ];
                    }
                }

                if($countryCheckbox && !$stateCheckbox){
                    //Logic Redirecting to Field After Select State
                    $logicCountryState[] = 
                    [
                        "action" => "jump",
                        "condition" => [
                            "op" => "always",
                            "vars" => [],
                        ],
                        "details" => [
                            "to" => [
                                "type" => "field",
                                "value" => "$formAfterGender"
                            ]
                        ]
                    ];
                }
                
                $logic[] = [
                    "type" => "field",
                    "ref" => "country_field_ref",
                    "actions" => $logicCountryState
                ];
         
                $logic[] = [
                    "type" => "field",
                    "ref" => $formGender,
                    "actions" => [
                        [
                            "action" => "jump",
                            "condition" => [
                                "op" => "always",
                                "vars" => [],
                            ],
                            "details" => [
                                "to" => [
                                    "type" => "field",
                                    "value" => "country_field_ref",
                                ]
                            ]
                        ],
                    ]
                ];

                $formFields = $formData['fields'];

                $formData['fields']=[];
                
                foreach($formFields as $key=>$formField){
                    if($key == 0 || $key ==1){
                        $formData['fields'][] = $formField;
                    }
                }
                
                foreach ($fields as $field) {
                    $formData['fields'][] = $field;
                }

                foreach($formFields as $key=>$formField){
                    if($key >= 2){
                        $formData['fields'][] = $formField;
                    }
                }

                foreach ($logic as $condition) {
                    $formData['logic'][] = $condition;
                }

                $updateForm = Http::withToken($accessToken)->put("https://api.typeform.com/forms/{$formId}", $formData);

                if($updateForm->successful()){
                    return 'Succesfully Added Country and State Fields';
                }else{
                    return "Fail to Add Field";
                }
            }catch(\Exception $e){
                return $e->getMessage();
            }
        } else {
            return "Already Exists";
        }
    }
}
