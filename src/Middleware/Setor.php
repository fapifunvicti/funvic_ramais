<?php

namespace App\Ramal\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response;
use App\Ramal\DB\Setup as DBSetup;
use App\Ramal\Services\SetorService;

class Setor {
    private $twig;

    public function __construct($twig){
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $uri = $request->getUri()->getPath();

        $id = null;

        if(\preg_match("/\/setor\/(\d+)/", $uri, $matches)){
            $id = $matches[1];
        }

        $setorService = new SetorService();
        $setor = $setorService->listarTodos(['id' => (int)$id]);

        if(!$setor){
            $this->twig->getEnvironment()->addGlobal('g_nome_setor', "Setor Desconhecido");
            return $handler->handle($request);
        }

        $this->twig->getEnvironment()->addGlobal('g_nome_setor', $setor[0]->nome);
        return $handler->handle($request);
    }
}