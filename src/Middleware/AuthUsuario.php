<?php

namespace App\Ramal\Middleware;
use App\Ramal\Model\SetorModel;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response;



class AuthUsuario {
    public function __invoke(Request $request, Handler $handler)
    {
        // Verificar se usuário está logado na sessão
        if (isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true) {
            // Adicionar dados do usuário ao request
            $usuarioService = new \App\Ramal\Services\UsuarioService();
            $usuario_id = $usuarioService->checarUsuarioExiste((int)$_SESSION['login']['id']);
            $email = $usuarioService->checarEmailExiste($_SESSION['login']['email']);

            
            if(!$usuario_id || !$email){
                return $handler->handle($request);

            }
            $request = $request->withAttribute('g_user_logged', $_SESSION['usuario_logado'] ?? null);
            $request = $request->withAttribute('g_session_login', $_SESSION['login'] ?? null);
            return $handler->handle($request);
        }
        
        // Se não estiver logado, redirecionar para login
        return $handler->handle($request);
    }
}