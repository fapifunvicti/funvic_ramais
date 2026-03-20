<?php
namespace App\Ramal\Model;

use Illuminate\Database\Eloquent\Model;

class RamalModel extends Model {

    protected $table = 'ramal';
    protected $primaryKey = 'idramal';
    public $timestamps = false;
    protected $fillable = ['idramal', 'nome', 'inserido_em', 'atualizado_em', 'deletado_em'];

}
