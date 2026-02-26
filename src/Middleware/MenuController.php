<?php 
namespace App\Ramal\Middleware;
use App\Ramal\Model\SetorModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Ramal\DB\Setup;

class MenuController  {

    private SetorModel $menurepo;
    private $twig;

    public function __construct($twig)
    {
        //$this->menurepo =  SetorModel::
        $this->twig = $twig;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $capsule = Setup::get();
        $itens =  $capsule->table('setor')->select("*")
                  ->whereNull('deletado_em')->get()->toArray();

        $this->twig->getEnvironment()->addGlobal('menu_item', $itens);
        return $handler->handle($request);

    }
    
}