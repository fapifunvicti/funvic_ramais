<?php
namespace App\Ramal\Model;

use Illuminate\Database\Eloquent\Model;

class AlocacaoModel extends Model {

    protected $table = 'alocacao_ramal';
    protected $primaryKey = 'idalocacao';
    public $timestamps = false;
    protected $fillable = ['setor_idsetor', 'ramal_id', 'inserido_em', 'atualizado_em', 'deletado_em'];

}
