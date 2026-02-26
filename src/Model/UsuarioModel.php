<?php
namespace App\Ramal\Model;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model {

    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';
    public $timestamps = false;
    protected $fillable = ['idusuario', 'email', 'senha', 'inserido_em', 'atualizado_em', 'deletado_em'];

}
