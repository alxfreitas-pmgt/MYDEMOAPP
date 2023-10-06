// Esta é uma aplicação de demonstração e teste de alta disponibilidade de containers com teste de carga para auto escalação de novos containers.
// O desenvolvimento dessa aplicação foi realizado pelo ChatGPT em parceria com o arquiteto de soluções Alex Freitas.

// Função para atualizar as informações da sessão
function atualizarInformacoesSessao() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'atualizar_sessao.php', true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var resposta = JSON.parse(xhr.responseText);

            // Atualize as informações na página
            document.getElementById('hostname').textContent = resposta.hostname;
            document.getElementById('ip').textContent = resposta.ip;
            document.getElementById('numConexoes').textContent = resposta.numConexoes;

            // Verifique se há uma mudança no servidor e exiba em azul temporariamente
            if (resposta.servidorNovo) {
                document.getElementById('hostname').classList.add('servidor-novo');
                setTimeout(function () {
                    document.getElementById('hostname').classList.remove('servidor-novo');
                }, 15000); // Remova a classe após 15 segundos (tempo de exibição)
            }
        }
    };

    xhr.send();
}

// Função para atualizar o contador regressivo
function atualizarContadorRegressivo(tempo) {
    var contador = tempo;
    var contadorElement = document.getElementById('contador-regressivo');

    var intervalo = setInterval(function () {
        contadorElement.textContent = contador;
        contador--;

        if (contador < 0) {
            clearInterval(intervalo);
            // Quando o contador regressivo atingir 0, atualize a página
            location.reload();
        }
    }, 1000);
}

// Função para iniciar o teste de carga
function iniciarTeste(tipoTeste) {
    // Enviar o tipo de teste ao servidor
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'teste_de_carga.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var resultado = JSON.parse(xhr.responseText);
            exibirResultadoTeste(resultado);
        }
    };
    xhr.send('tipoTeste=' + tipoTeste);
}

// Função para exibir o resultado do teste de carga
function exibirResultadoTeste(resultado) {
    // Lógica para exibir o resultado do teste, por exemplo, em uma área de log
    var logArea = document.getElementById('log');
    logArea.innerHTML = ''; // Limpar o conteúdo anterior da área de log
    resultado.forEach(function (log) {
        var logItem = document.createElement('div');
        logItem.textContent = 'Origem: ' + log.origem + ', Destino: ' + log.destino + ', Porta Destino: ' + log.portaDestino + ', Status: ' + log.status;
        logArea.appendChild(logItem);
    });
}

// Quando o botão "Iniciar Teste" for clicado
document.getElementById('iniciarTeste').addEventListener('click', function () {
    var tipoTeste = document.querySelector('input[name="tipoTeste"]:checked').value;
    iniciarTeste(tipoTeste);
});
