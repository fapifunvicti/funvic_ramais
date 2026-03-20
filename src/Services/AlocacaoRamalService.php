<?php
namespace App\Ramal\Services;

use \App\Ramal\DB\Setup as DBSetup;

class AlocacaoRamalService {

    public function listarTodos(?array $parametros = null){
        $capsule = DBSetup::get();
        $query = $capsule->table('alocacao_ramal')->selectRaw(
                        "
                            alocacao_ramal.setor_idsetor,
                            alocacao_ramal.ramal_id,
                            setor.nome as setor_nome,
                            ramal.nome as responsavel,
                            ramal.numero,
                            alocacao_ramal.inserido_em,
                            alocacao_ramal.atualizado_em,
                            alocacao_ramal.deletado_em
                        
                        "
                        )

                        ->join('setor', 'setor.idsetor', '=', 'alocacao_ramal.setor_idsetor', 'left')
                        ->join('ramal', 'ramal.idramal', '=', 'alocacao_ramal.ramal_id', 'left');
                         


        if($parametros){
            
            if(isset($parametros['orderby'])){
                $query->orderBy($parametros['orderby']['nome'],$parametros['orderby']['order']);
            }

            if(isset($parametros['id']) &&  $parametros['id'] != null){
                $query->where('alocacao_ramal.ramal_id', '=', (int)$parametros['id']);
            }


            if(isset($parametros['idsetor']) && $parametros['idsetor'] != null){
                $query->where('alocacao_ramal.setor_idsetor', '=', (int)$parametros['idsetor']);
            }


        }

        //$query->whereNull('alocacao_ramal.deletado_em');
        return $query;

    }

    public function Count(): int {
        $capsule = DBSetup::get();
        return $capsule->table('alocacao_ramal')->count();
    }
}