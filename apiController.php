<?php

namespace App\controllers;
use App\models\ZipCodes; 

class ApiController extends Controller 
{
  
  public function codeZip($request, $response, $args){
          $codeZip = $args["codeZip"];

          $zips = ZipCodes::where('d_codigo', $codeZip)->get();
          $arrData = [];
          $arrSett = [];
          foreach($zips as $zip){
              if(count($arrData) == 0){
                  $arrData = [
                              "zip_code" => $zip->d_codigo, 
                              "locality" => strtoupper($zip->d_ciudad), 
                              "federal_entity" => [
                                  "key" => intval($zip->c_estado),
                                  "name" => strtoupper($zip->d_estado),
                                  "code"=>$zip->c_CP
                              ],
                              "settlements" => "",
                              "municipality" => [
                                  "key" => intval($zip->c_mnpio),
                                  "name" => strtoupper($zip->D_mnpio), 
                              ]
                  ];
              }
              array_push($arrSett, [
                  "key" => intval($zip->id_asenta_cpcons),
                  "name" => strtoupper($zip->d_asenta),
                  "zone_type" => strtoupper($zip->d_zona),
                  "settlement_type" => ["name"=> $zip->d_tipo_asenta]

              ]);
          }
          $arrData["settlements"] = $arrSett;

          echo json_encode($arrData);
    }
}
