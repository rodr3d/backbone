<?php

namespace App\controllers;
use App\models\ZipCodes; 

class ApiController extends Controller 
{
  
  public function codeZip($request, $response, $args){
          $codeZip = $args["codeZip"];

          $zips = ZipCodes::where('d_codigo', $codeZip)->get();
          if(count($zips) == 0){
            return;
          }
          $arrData = [];
          $arrSett = [];
          foreach($zips as $zip){
              if(count($arrData) == 0){
                $arrData = [
                            "zip_code" => $zip->d_codigo, 
                            "locality" => strtoupper($this->eliminar_tildes(trim($zip->d_ciudad))),
                            "federal_entity" => [
                                "key" => intval($zip->c_estado),
                                "name" => strtoupper($this->eliminar_tildes(trim($zip->d_estado))),
                                "code"=> ($zip->c_CP == " ") ? null : $zip->c_CP
                            ],
                            "settlements" => "",
                            "municipality" => [
                                "key" => intval($zip->c_mnpio),
                                "name" => strtoupper($this->eliminar_tildes(trim($zip->D_mnpio))), 
                            ]
                ];
            }
            array_push($arrSett, [
                "key" => intval($zip->id_asenta_cpcons),
                "name" => strtoupper($this->eliminar_tildes(trim($zip->d_asenta))),
                "zone_type" => strtoupper($this->eliminar_tildes(trim($zip->d_zona))),
                "settlement_type" => ["name"=> $this->eliminar_tildes(trim($zip->d_tipo_asenta))]
                
            ]);
          }
          $arrData["settlements"] = $arrSett;
          header('Content-Type: application/json; charset=UTF-8');
          echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
  
    function eliminar_tildes($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
}
