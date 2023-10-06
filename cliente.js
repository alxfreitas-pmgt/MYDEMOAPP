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
function iniciarTesteCarga() {
    var tipoTeste = document.getElementById('tipoTeste').value;
    var urlRemota = document.getElementById('urlRemota').value;
    var quantidadeConexoes = parseInt(document.getElementById('quantidadeConexoes').value);

    // Envie os parâmetros para o servidor usando AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'teste_de_carga.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var resposta = JSON.parse(xhr.responseText);
            if (resposta.status === 'iniciado') {
                // Teste de carga iniciado com sucesso
                alert('Teste de carga iniciado!');
            } else {
                // O servidor retornou um erro
                alert('Erro ao iniciar o teste de carga: ' + resposta.erro);
            }
        }
    };

    xhr.send('tipoTeste=' + tipoTeste + '&urlRemota=' + urlRemota + '&quantidadeConexoes=' + quantidadeConexoes);
}

// Quando o botão "Salvar" for clicado
document.getElementById('salvarTempo').addEventListener('click', function () {
    var tempo = parseInt(document.getElementById('tempoRefresh').value) || 30;
    atualizarContadorRegressivo(tempo);
});

// Quando o botão "Redefinir" for clicado
document.getElementById('redefinirTempo').addEventListener('click', function () {
    document.getElementById('tempoRefresh').value = 30;
    atualizarContadorRegressivo(30);
});

// Quando o botão "Iniciar Teste" for clicado
document.getElementById('iniciarTeste').addEventListener('click', function () {
    iniciarTesteCarga();
});

// Chame a função de atualização inicialmente
atualizarInformacoesSessao();

// Chame a função de atualização a cada segundo (1000 ms)
setInterval(atualizarInformacoesSessao, 1000);
