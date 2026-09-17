<?php

require __DIR__ . '/vendor/autoload.php';

// Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? ($_POST['action'] ?? 'dashboard');
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    // Autenticação
    case $action === 'login' && $method === 'GET':
        AutenticacaoController::telaLogin();
        break;

    case $action === 'login' && $method === 'POST':
        AutenticacaoController::login();
        break;

    case $action === 'register' && $method === 'GET':
        AutenticacaoController::telaCadastro();
        break;

    case $action === 'register' && $method === 'POST':
        AutenticacaoController::cadastrar();
        break;

    case $action === 'logout':
        AutenticacaoController::logout();
        break;

    // Dashboard
    case $action === 'dashboard':
        IdeiaController::dashboard();
        break;

    // Ideias
    case $action === 'ideas':
        IdeiaController::listar();
        break;

    case $action === 'idea-create' && $method === 'GET':
        IdeiaController::telaCriar();
        break;

    case $action === 'idea-create' && $method === 'POST':
        IdeiaController::criar();
        break;

    case $action === 'idea-edit' && $method === 'GET':
        IdeiaController::telaEditar();
        break;

    case $action === 'idea-update' && $method === 'POST':
        IdeiaController::atualizar();
        break;

    case $action === 'idea-delete' && $method === 'POST':
        IdeiaController::excluir();
        break;

    case $action === 'idea-details':
        IdeiaController::detalhes();
        break;

    case $action === 'idea-random':
        IdeiaController::aleatoria();
        break;

    // Rota padrão
    default:
        header('Location: index.php?action=dashboard');
        break;
}
