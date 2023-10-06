<?php
// Esta é uma aplicação de demonstração e teste de alta disponibilidade de containers com teste de carga para auto escalação de novos containers.
// O desenvolvimento dessa aplicação foi realizado pelo ChatGPT em parceria com o arquiteto de soluções Alex Freitas.

// Receber os parâmetros do teste de carga
$tipoTeste = $_POST['tipoTeste'];
$urlRemota = $_POST['urlRemota'];
$quantidadeConexoes = intval($_POST['quantidadeConexoes']);

// Função para realizar conexões simuladas
function realizarConexoes($destino, $quantidade)
{
    $resultado = [];

    // Implemente aqui a lógica para estabelecer conexões simuladas com o destino
    // Use a variável $quantidade para controlar o número de conexões

    // Adicione informações de log ao resultado
    for ($i = 1; $i <= $quantidade; $i++) {
        $log = [
            'origem' => gethostname(),
            'destino' => $destino,
            'portaDestino' => 8080, // Porta 8080 para todas as conexões
            'status' => 'sucesso', // Defina o status com base na conexão simulada
        ];
        $resultado[] = $log;
    }

    return $resultado;
}

// Iniciar o teste de carga com base no tipo selecionado
if ($tipoTeste === 'local') {
    $destino = gethostname();
} elseif ($tipoTeste === 'remoto') {
    // Verificar se a URL remota foi fornecida
    if (empty($urlRemota)) {
        echo json_encode(['status' => 'erro', 'erro' => 'A URL remota não foi especificada.']);
        exit();
    }
    $destino = $urlRemota;
} else {
    echo json_encode(['status' => 'erro', 'erro' => 'Tipo de teste inválido.']);
    exit();
}

// Realizar conexões simuladas
$resultadoTeste = realizarConexoes($destino, $quantidadeConexoes);

// Responder com o resultado do teste em JSON
header('Content-Type: application/json');
echo json_encode($resultadoTeste);
