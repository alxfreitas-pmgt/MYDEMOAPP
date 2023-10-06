<?php
// Esta é uma aplicação de demonstração e teste de alta disponibilidade de containers com teste de carga para auto escalação de novos containers.
// O desenvolvimento dessa aplicação foi realizado pelo ChatGPT em parceria com o arquiteto de soluções Alex Freitas.

// Definir o tempo limite de execução para 30 segundos (pode ser ajustado conforme necessário)
set_time_limit(30);

// Verificar se o arquivo de sessão já existe, senão, criar uma nova
if (!file_exists('sessao.txt')) {
    $sessao = [
        'hostname' => gethostname(),
        'ip' => $_SERVER['SERVER_ADDR'],
        'numConexoes' => 0,
        'servidorNovo' => true, // Marca como servidor novo inicialmente
    ];

    file_put_contents('sessao.txt', json_encode($sessao));
}

// Ler as informações de sessão a partir do arquivo
$sessao = json_decode(file_get_contents('sessao.txt'), true);

// Atualizar o número de conexões simulando com um número aleatório (para fins de demonstração)
$sessao['numConexoes'] = rand(1, 100);

// Marcar como servidor não novo após a primeira leitura
$sessao['servidorNovo'] = false;

// Escrever as informações atualizadas de volta no arquivo de sessão
file_put_contents('sessao.txt', json_encode($sessao));

// Responder com as informações da sessão em JSON
header('Content-Type: application/json');
echo json_encode($sessao);
