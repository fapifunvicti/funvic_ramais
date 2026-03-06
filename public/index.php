<?php 

include_once "../config.php";


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;


$bootstrap->Start();




$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


$twig = Twig::create(__DIR__ . '/../templates', ['cache' => false]);


//adicionar extensao para lidar com as sessoes de login
$twig->addExtension(new \App\Ramal\Extensao\SessaoTwig());

$db = $container->get('setup.db')();



$app->add( new \App\Ramal\Middleware\MenuController($twig));


$app->add(TwigMiddleware::create($app, $twig));


$app->get('/', function (Request $request, Response $response, $args){
    $view = Twig::fromRequest($request);
    return $view->render($response, "ramal/lista.html.twig",[]);
})->add(new \App\Ramal\Middleware\Setor($twig))
  ->add(new \App\Ramal\Middleware\AuthUsuario());


$app->group('/setor', function($app) {

    
    $app ->get('[/{id:\d+}]', function (Request $request, Response $response, $args) {
        $view = Twig::fromRequest($request);

        $alocRamalService = new \App\Ramal\Services\AlocacaoRamalService();

        $id = $args['id'] ?? null;
        if(!$id){
              return $view->render($response, "ramal/lista.html.twig",[]);
        }

        $setorService = new \App\Ramal\Services\SetorService();
        $setor  = $setorService->listarTodos(['id' => $id]) ?? "";
        $ramalCount = $alocRamalService->listarTodos(['idsetor' => (int)$id ])->count();

        return $view->render($response, "ramal/lista.html.twig",['id' => $id,
                                                                                           'setor' => $setor ? $setor[0]->nome : "", 
                                                                                           'totalRamais' => $ramalCount,
                                                                                            'usuario_logado' => $_SESSION['usuario_logado'] ?? false
                                                                                           ],
                                                                                          
                                                                                           );
    });

})
  ->add(new \App\Ramal\Middleware\Setor($twig))
  ->add(new \App\Ramal\Middleware\AuthUsuario());



$app->group('/login', function($app){
        $app ->get('', function (Request $request, Response $response, $args) {
            $view = Twig::fromRequest($request);

            return $view->render($response, 'login/login.html.twig', []);
        });

        $app->get('/logoff', function (Request $request, Response $response){
            session_destroy();
            return $response->withHeader("location", "/")
                 ->withStatus(302);
        });

        $app->post('', function (Request $request, Response $response) {
            $view = Twig::fromRequest($request);
            $erros = [];

            $post = $request->getParsedBody();

            if(empty($post['email'])){
                $erros[] = "Login (E-mail) Campo Obrigatório!";
            }
            
            if(empty($post['senha'])){
                $erros[] = "Campo Senha Obrigatório!";
            }

            $usuarioService = new \App\Ramal\Services\UsuarioService();
            $usuario =  $usuarioService->realizarlogin($post['email'], $post['senha']);

            if(!$usuario){
                return $view->render($response, 'login/login.html.twig', ['erros' => $erros]);
            }

            $_SESSION['usuario_logado'] = true;
            $_SESSION['login']['id'] = $usuario->idusuario;
            $_SESSION['login']['email'] = $usuario->email;


            return $response->withHeader("location", "/")
                 ->withStatus(302);
            //return $view->render($response, 'login/login.html.twig', []);
        });
})->add(new \App\Ramal\Middleware\AuthUsuario());



$app->group('/dt', function($app){
    $app->post('/ramal[/{tipo}]', function (Request $request, Response $response, $args){

        $post = $request->getParsedBody();
        $post = array_merge($post, $args);
        $dataTable = new \App\Ramal\DatatableData\DTListarRamais($post);
        $dataTable->Render();
        return $response->withHeader('content-type','application/json'); 
                       

    });
})->add(new \App\Ramal\Middleware\AuthUsuario());

$app->run();
$bootstrap->End();
