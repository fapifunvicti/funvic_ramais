<?php

namespace App\Ramal\Datatable;
use Exception;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Query\Builder;
use App\Config;
use DateTime;
use Illuminate\Support\Facades\App;

class QueryDTable  {

    protected array $query  = [];
    protected ?Builder $instance  = null; 
    protected ?Manager $capsule = null;
    protected string $table = "";
    protected int $count = 0;

    protected array $request = [];

    protected int $start = 0;
    protected int $length = 10;

    protected array $params = [];

    public function __construct(string $table, ?array $query = null, ?array $request = [])  {
            $this->table = $table;
            $this->query = $query ?? [];  
            $this->request = $request;
            $this->capsule = \App\Ramal\DB\Setup::get();
    }

    public function CreateInstance(string $table, Builder &$instance){
        $this->table = $table;
        $this->instance = $instance;
        return $this;

    }

    public function GetCapsule(): Manager {
        return $this->capsule;
    }


    public static function isDateFromStringValid(string $date, string $format): bool {
        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }

    /**
     * retorna inatancia de Query Builder Illuminate\Database\Query\Builder
     * @return Builder|null
     */
    public function GetInstance(): Builder {
        return $this->instance;
    }

    /**
     * Cria uma instancia de Illuminate\Database\Capsule\Manager 
     * @return \App\Ramal\Datatable\QueryDTable
     */
    public function Execute(): static {
        $this->instance = $this->capsule->table($this->table);
        return $this;
    }

    /**
     * passa o request $_REQUEST (recomendado) ou $_POST ou $_GET
     * @param array $request
     * @return void
     */
    public function UpdateRequest(array $request):void {
        $this->request = $request;
    }

    public function Count(): int{
        return $this->count;
    }

    /**
     * numero de registros iniciais (LIMIT)
     * @param int $start
     * @return void
     */
    public function setStart(int $start){
        $this->start = $start ?? 0;
    }

    /**
     * numeros de registros entre limit (OFFSET)
     * @param int $length
     * @return void
     */
    public function setLength(int $length){
        $this->length = $length;
    }

    /**
     * array de campos onde o campo "Pesquisar" vai acessar para preencher o datatable
     * @param array $columns
     * @return static
     */
    public function Search(array $columns){

        if (isset($this->request['search']['value']) && !empty($this->request['search']['value'])) {
                
            $value = $this->request['search']['value'];
            
            foreach($columns as $column){
                $this->setSearch($column, $value);
            }
        }

        return $this;
    }

    /**
     * Busca nos campos da Barra Pesquisar
     * @param string $column
     * @param string $search
     * @return void
     */
    public function setSearch(string $column, string $search){
        if(empty($column) || !$column|| mb_strlen($column) == 0){
            return;
        }
        if(empty($search) || !$search || mb_strlen($search) == 0) {
            return;
        }

        return;
    }

    /**
     * busca na campo da coluna selecionada (se existir)
     * @param string $column
     * @param string $searchvalue
     * @return void
     */
    public function SetColSearch(string $column, string $searchvalue){
        //if(empty($column) || !$column|| mb_strlen($column) == 0) return;
        //if(empty($searchvalue) || !$searchvalue || mb_strlen($searchvalue) == 0) return;
        return;
    }

    /**
     * monta a lista de quais colunas sao permitidas fazer a busca
     * para o datatable e retornar
     * @param mixed $allowed_columns array colunas permitidas
     * @param array $columns array  de colunas em string (nao use aliases do banco)
     * @return void
     */
    public function Columns(?array $allowed_columns, array $columns){

        $allowed = $allowed_columns ? \array_intersect($allowed_columns, $columns) : $columns;
  
        $columns = $this->request['columns'] ?? null;

        if(!$columns) 
            return;

        foreach($columns as $column){
            $search = $this->request['search']['value'];
            $name = $column['data'];
            if(empty($search) || !$search) continue;

            foreach($allowed as $fields){
                if(\str_contains( $fields, $name)){        
                    $this->SetColSearch($name, $search);
                }
            }
            /*
            if(\str_contains($this->request['columns'][$index]['data'], $allowed[$index])){        
                $this->SetColSearch($name, $search);
            }*/



        }



        /*
        if (isset($this->request['columns']) && is_array($this->request['columns'])) {
            foreach ($this->request['columns'] as $index => $column) {
                // Verifica se há um valor de busca para esta coluna
                if (isset($column['search']['value']) && !empty($column['search']['value'])) {
                    $columnSearchValue = $column['search']['value'];
                    $columnName = $column['data']; 

                    if($allowed_columns && in_array($columnName, $allowed_columns)){
                        foreach($columns as $column){
                            $this->SetColSearch($column, $columnSearchValue);
                        }
                    }else {
                            if($columnSearchValue && !empty($columnSearchValue)){
                                foreach($columns as $column){
                                    $this->SetColSearch($column, $columnSearchValue);
                                }
                            }
                    }
                    
                }
            }
        }
        */
    }

    /**
     * monta a ordenação dos datdos do datatable
     * o array deve ser:
     *  ['nome_da_coluna' => 'asc' ] ou ['nome_da_coluna' => 'asc' ]
     * @param mixed $orderby_columns
     * @param mixed $instance
     * @return static
     */
    public function Order(?array $orderby_columns) {
        
        if($orderby_columns){
            foreach($orderby_columns as $key => $order){
                $this->instance->orderBy((string)$key, $order);
            }
            return $this;
        }
        

        if (!isset($this->request['order'])){
            return $this;
        }
        
        foreach($this->request['order'] as $order){
            $colunaIndex = (int) $order['column'];
            $dir = isset($order['dir']) ? $order['dir'] : 'desc';
            $col_name = $this->request['columns'][$colunaIndex]['data'] ?? "";
                $this->instance->orderBy($col_name, $dir);
        }
        /*
        if (isset($this->request['order']) && is_array($this->request['order']) && !empty($this->request['order'])) {
            
            foreach($this->request['order'] as $order){
                    $colunaIndex = (int) $order['column'];
                    $dir = isset($order['dir']) ? $order['dir'] : 'desc';
                    $col_name = $this->request['columns'][$colunaIndex]['data'] ?? "";

                    if(!$instance){
                        $this->instance->orderBy($col_name, $dir);
                    }else {
                        $instance->orderBy($col_name, $dir);
                    }

            }
            }else {
                if($orderby_columns){
                    foreach($orderby_columns as $key => $order){
                        $this->instance->orderBy($key, $order);
                    }
                }
               
        }
        */

        return $this;
    }

    /**
     * * processa os parametros vindo da URL mesmo sendo POST ou GET
     * o formato: [ ['campo', 'operador', 'valor'] ]
     * para multiplos parametros: [ ['campo', 'operador', 'valor'], 
     *                            ['campo', 'operador', 'valor'] ,
     *                            ['campo', 'operador', 'valor'] ]  
     * @param array $params
     * @return void
     */
    public function ProcessParams(array $params): void {

        foreach($params as $param){
            list($campo,$operador, $valor) = $param;
            //$this->instance->where($campo, $operador, $valor);
            $this->params[] = [$campo, $operador, $valor];
        }

        return;
    }

    public function Render(){

        
        $start = isset($this->request['start']) ? (int)$this->request['start'] : 0;
        $length = isset($this->request['length']) ? (int)$this->request['length'] : 10;

        
        $this->instance->offset($start);
        $this->instance->limit($length);

        $instance_local = $this->capsule->table($this->table);

        if($this->params){
            foreach($this->params as $param){
                list($campo, $operador, $valor) = $param;
                $this->instance->where($campo, $operador, $valor);   
                $instance_local->where($campo, $operador, $valor);
            }
        }

        //$this->GetCount();
        $count = $this->count <= 0 ? $this->instance->count() : $this->count;
        $data = $this->instance->get(); 

        $json_header = [
            'draw' => !isset($this->request['draw']) ? 1 : (int)$this->request['draw'],
            "recordsTotal"    => $this->capsule->table($this->table)->count(), //$instance_local->count(),
            "recordsFiltered" => $count,
            "data" => $data,

        ];

        header('Content-Type: application/json; charset=utf-8');
        ob_start();
        echo json_encode($json_header);
        ob_end_flush();
    }

}