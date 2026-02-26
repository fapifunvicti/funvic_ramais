<?php

namespace App\Ramal\Services;
use \App\Ramal\DB\Setup as DBSetup;


class UsuarioService {

     public function listarTodas(array $parametros){
        $capsule = DBSetup::get();
        $query = $capsule->table('usuario')->select();
                         


        if($parametros){
            
            if(isset($parametros['orderby'])){
                $query->orderBy($parametros['orderby']['nome'],$parametros['orderby']['order']);
            }

            if(isset($parametros['id']) &&  $parametros['id'] != null){
                $query->where('usuario.idusuario', '=', (int)$parametros['id']);
            }

            if(isset($parametros['email']) &&  $parametros['email'] != null){
                $query->where('usuario.email', '=', (int)$parametros['email']);
            }

        }

        $query->whereNull('setor.deletado_em');
        return $query->get()->toArray();
     }

    public function checarUsuarioExiste(int $id) {
            $capsule = DBSetup::get();
            $usuario = $capsule->table('usuario')->select()
                      ->whereNull('deletado_em')
                      ->where('idusuario', '=', $id)
                      ->first();
            if(!$usuario) return null;
            return $usuario;
    }




    public function checarEmailExiste(string $email) {
            $capsule = DBSetup::get();
            $usuario = $capsule->table('usuario')
                                ->select()
                      ->whereNull('deletado_em')
                      ->where('email', '=', $email)
                      ->first();
                   
            if(!$usuario) return null;
            return $usuario;
    }

    public function realizarlogin(string $login, string $senha){
        $usuario = \null;
        if(!($usuario = $this->checarEmailExiste($login))){
                return null;
        }

        
        if(!\password_verify($senha, $usuario->senha)){
                return null;
        }

        return $usuario;

    }

}