<?php
namespace App\Ramal\Services;

use \App\Ramal\DB\Setup as DBSetup;

use function Symfony\Component\Clock\now;

class AlocacaoRamalService {

    public function listarTodos(?array $parametros = null){
        $capsule = DBSetup::get();
        $query = $capsule->table('alocacao_ramal')->selectRaw(
                        "
                            alocacao_ramal.idalocacao,
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
                $query->where('alocacao_ramal.idalocacao', '=', (int)$parametros['id']);
            }


            if(isset($parametros['idramal']) && $parametros['idramal'] != null){
                $query->where('alocacao_ramal.ramal_id', '=', (int)$parametros['idramal']);
            }


            if(isset($parametros['idsetor']) && $parametros['idsetor'] != null){
                $query->where('alocacao_ramal.setor_idsetor', '=', (int)$parametros['idsetor']);
            }


        }

        //$query->whereNull('alocacao_ramal.deletado_em');
        return $query;

    }

    public function Deletar(int $id) : bool{
        $capsule = DBSetup::get();
        $ramal =  $capsule->table('alocacao_ramal')
                   ->where('idalocacao', '=', $id);

        if(!$ramal->first()){
            return false;
        }

        if(!$ramal->update([
            'deletado_em' => now()
        ])){
            return false;
        }

        return true;
        
    }

    public function Restaurar(int $id) : bool{
        $capsule = DBSetup::get();
        $ramal =  $capsule->table('alocacao_ramal')
                   ->where('idalocacao', '=', $id);

        if(!$ramal->first()){
            return false;
        }

        if(!$ramal->update([
            'deletado_em' => \null
        ])){
            return false;
        }

        return true;
        
    }

    public function DeletarPermanente(int $id): bool {
        $capsule = DBSetup::get();
        $ramal = \App\Ramal\Model\AlocacaoModel::findOrFail($id);
        return $ramal->delete();
        
    }

    public function Count(): int {
        $capsule = DBSetup::get();
        return $capsule->table('alocacao_ramal')->count();
    }
}