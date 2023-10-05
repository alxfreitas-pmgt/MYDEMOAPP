<!DOCTYPE html>
<html>
<head>
    <title>Teste de Carga</title>
    <script>
        var isTesting = false;
        var xhrs = [];

        function startTest() {
            if (isTesting) {
                alert("Os testes já estão em execução.");
                return;
            }

            var url = document.getElementById("url").value;
            var numConnections = parseInt(document.getElementById("numConnections").value);

            if (!url || isNaN(numConnections) || numConnections <= 0) {
                alert("Por favor, insira uma URL válida e um número válido de conexões.");
                return;
            }

            for (var i = 0; i < numConnections; i++) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", url, true);
                xhrs.push(xhr);
                xhr.send();
            }

            isTesting = true;
            document.getElementById("startBtn").disabled = true;
            document.getElementById("stopBtn").disabled = false;
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
</body>
</html>