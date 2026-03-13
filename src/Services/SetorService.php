<?php
namespace App\Ramal\Services;

use \App\Ramal\DB\Setup as DBSetup;

use function Symfony\Component\Clock\now;

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

    public function adicionarSetor(array $post): bool {
        
        $setor = new \App\Ramal\Model\SetorModel();
        $setor->nome = $post['nome'];
        
        if(!$setor->save()){
            return false;
        }

        return true;
    }

    public function editarSetor(int $id, array $post): \App\Ramal\Model\SetorModel|bool {
        $setor =  \App\Ramal\Model\SetorModel::findOrFail($id);
        if(!$setor){
            return false;
        }

        $setor->nome = $post['nome'];
        
        if(!$setor->save()){
            return false;
        }

        return $setor;

    }

    public function SoftRemoveSetor(int $id): bool {
        
        $setor =  \App\Ramal\Model\SetorModel::findOrFail($id);
        if(!$setor){
            return false;
        }

        $setor->deletado_em = now();

        return true;
    }
}