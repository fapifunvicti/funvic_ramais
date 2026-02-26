<?php
namespace App\Ramal\Services;

use \App\Ramal\DB\Setup as DBSetup;

class SetorService {
    public function listarTodos(?array $parametros = null){
        $capsule = DBSetup::get();
        $query = $capsule->table('setor')->select();
                         


        if($parametros){
            
            if(isset($parametros['orderby'])){
                $query->orderBy($parametros['orderby']['nome'],$parametros['orderby']['order']);
            }

            if(isset($parametros['id']) &&  $parametros['id'] != null){
                $query->where('setor.idsetor', '=', (int)$parametros['id']);
            }


        }

        $query->whereNull('setor.deletado_em');
        return $query->get()->toArray();

    }
}