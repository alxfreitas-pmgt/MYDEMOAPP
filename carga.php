<!DOCTYPE html>
<html>
<head>
    <title>Teste de Carga</title>
    <script>
        var isTesting = false;
        var xhrs = [];
        var logContainer;

        function startTest() {
            if (isTesting) {
                alert("Os testes já estão em execução.");
                return;
            }

            var url = document.getElementById("url").value;
            var numConnections = parseInt(document.getElementById("numConnections").value);
            logContainer = document.getElementById("logContainer");

            if (!url || isNaN(numConnections) || numConnections <= 0) {
                alert("Por favor, insira uma URL válida e um número válido de conexões.");
                return;
            }

            isTesting = true;
            document.getElementById("startBtn").disabled = true;
            document.getElementById("stopBtn").disabled = false;

            startConnections(url, numConnections, 0);
        }

        function startConnections(url, numConnections, currentConnection) {
            if (currentConnection < numConnections) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", url, true);
                xhrs.push(xhr);

                xhr.onload = function() {
                    var logEntry = document.createElement("p");
                    logEntry.textContent = "Conexão " + (currentConnection + 1) + " - Status: " + xhr.status + " " + xhr.statusText;
                    logContainer.appendChild(logEntry);

                    startConnections(url, numConnections, currentConnection + 1);
                };

                xhr.onerror = function() {
                    var logEntry = document.createElement("p");
                    logEntry.textContent = "Conexão " + (currentConnection + 1) + " - Erro de conexão";
                    logContainer.appendChild(logEntry);

                    startConnections(url, numConnections, currentConnection + 1);
                };

                xhr.send();
            } else {
                stopTest();
            }
        }

        function stopTest() {
            if (!isTesting) {
                alert("Os testes não estão em execução.");
                return;
            }

            for (var i = 0; i < xhrs.length; i++) {
                xhrs[i].abort();
            }

            xhrs = [];
            isTesting = false;
            document.getElementById("startBtn").disabled = false;
            document.getElementById("stopBtn").disabled = true;
        }
    </script>
</head>
<body>
    <h1>Teste de Carga</h1>
    <form>
        <label for="url">URL:</label>
        <input type="text" id="url" name="url" required><br><br>

        <label for="numConnections">Quantidade de Conexões:</label>
        <input type="number" id="numConnections" name="numConnections" required><br><br>

        <button type="button" id="startBtn" onclick="startTest()">Iniciar Testes</button>
        <button type="button" id="stopBtn" onclick="stopTest()" disabled>Parar Testes</button>
    </form>

    <div id="logContainer" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll;"></div>
</body>
</html>
