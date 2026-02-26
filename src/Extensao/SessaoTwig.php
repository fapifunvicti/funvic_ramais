<?php
namespace App\Ramal\Extensao;

class SessaoTwig extends \Twig\Extension\AbstractExtension
{
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('session', [$this, 'getSession']),
            new \Twig\TwigFunction('auth', [$this, 'isAuthenticated']),
            new \Twig\TwigFunction('user', [$this, 'getUser']),
        ];
    }
    
    public function getSession($key = null)
    {
        if ($key) {
            return $_SESSION[$key] ?? null;
        }
        return $_SESSION;
    }
    
    public function isAuthenticated()
    {
        return $_SESSION['usuario_logado'] ?? false;
    }
    
    public function getUser()
    {
        return $_SESSION['login'] ?? null;
    }
}