<?php
namespace App\Ramal\DatatableData;

use App\Ramal\Datatable\QueryDTable;



class DTListarRamais  extends QueryDTable {

    public function __construct(?array &$request = [])
    {
        $this->capsule =  \App\Ramal\DB\Setup::get();
        $this->query =  [
            $this->capsule::raw("
                alocacao_ramal.*,
                setor.nome setor_nome,
                ramal.nome ramal_nome,
                ramal.numero ramal_numero
                
            ")
        ];

       parent::__construct('alocacao_ramal', $this->query, $request);


       $instance = $this->Execute()->GetInstance()
       ->select($this->query)
                     ->join('ramal', 'ramal.idramal', '=', 'alocacao_ramal.ramal_id')
                     ->join('setor', 'setor.idsetor', '=', 'alocacao_ramal.setor_idsetor');

        $tipo_id = is_numeric((int)$request['tipo']) ? (int)$request['tipo'] : null;

        if($tipo_id && \is_numeric($tipo_id)){
            $instance->Where('setor.idsetor', '=', $tipo_id);
        }

        $this->Order([
                    'inserido_em' => 'desc',
                ])

                ->Search([
                    'setor.nome',
                    'ramal.nome',
                    'ramal.numero',
                ])


                ->Columns(
                   null,
                [
                    'setor.nome',
                    'ramal.nome',
                    'ramal.numero',
                ]);


        $this->count = $instance->count();

    }

    #[\Override]
    public function SetColSearch(string $column, string $searchvalue)
    {
        parent::SetColSearch($column, $searchvalue);
        switch($column){
            case 'ramal_nome':
                $this->GetInstance()
                ->where('ramal.nome', 'LIKE', "%{$searchvalue}%");
                break;

            case 'ramal_numero':
                $this->GetInstance()
                ->where('ramal.numero', 'LIKE', "%{$searchvalue}%");
                break;
            case 'setor_nome':
                $this->GetInstance()
                ->where('setor.nome', 'LIKE', "%{$searchvalue}%");
                break;
            default:
                $this->GetInstance()
                ->where(    'setor.nome', 'LIKE', "%{$searchvalue}%");
                break;
        }
    }

    #[\Override]
    public function  setSearch(string $column, string $search){
        parent::setSearch($column, $search);
            
        if(\is_numeric($search)){
            $this->instance
                ->Where('ramal.numero', 'LIKE', "%{$search}%");
        }else {
        
            $this->instance
                ->Where('ramal.nome', 'LIKE', "%{$search}%");

        }
        
        /*
        switch($column){
            case 'ramal.nome':
                $this->instance
                ->Where('ramal.nome', 'LIKE', "%{$search}%");
                break;

            case 'ramal.numero':
                $this->instance
                ->Where('ramal.numero', 'LIKE', "%{$search}%");
                break;
            case 'setor.nome':
                $this->instance
                ->Where('ramal.numero', 'LIKE', "%{$search}%");
                break;
        }*/
        
    }

}