<?php
namespace App\Ramal\Model;

use Illuminate\Database\Eloquent\Model;

class SetorModel extends Model {

    protected $table = 'setor';
    protected $primaryKey = 'idsetor';
    public $timestamps = false;
    protected $fillable = ['idgrupo', 'nome', 'inserido_em', 'atualizado_em', 'deletado_em'];

}
