<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Restrita!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .navbar-custom { box-shadow: 0 2px 10px rgba(8, 3, 78, 0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark px-4 navbar-custom">
        <span class="navbar-brand fw-bold">Olá, <?= $_SESSION['nome'] ?>!</span>    
        <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-3">Bem-vindo a Área Restrita</h2>
                <p class="text-muted">Use nossa calculadora para realizar suas operações de forma mágica</p>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-lg-4 col-md-6">
                
                <div class="card card-custom p-4 text-start">
                    <h5 class="text-center mb-4">Calculadora</h5>
                    <form id="formcalc">
                        <div class="mb-3">
                            <label for="val1" class="form-label fw-semibold">Primeiro Valor</label>
                            <input type="number" name="val1" id="val1" class="form-control form-control-lg" placeholder="Ex: 10">
                        </div>
                        
                        <div class="mb-3">
                            <label for="val2" class="form-label fw-semibold">Segundo Valor</label>
                            <input type="number" name="val2" id="val2" class="form-control form-control-lg" placeholder="Ex: 5">
                        </div>
                        
                        <div class="mb-4">
                            <label for="operacao" class="form-label fw-semibold">Operação</label>
                            <select name="operacao" id="operacao" class="form-select form-select-lg">
                                <option value="somar">Somar</option>
                                <option value="subtrair">Subtrair</option>
                                <option value="multiplicar">Multiplicar</option>
                                <option value="dividir">Dividir</option>
                                <option value="potencia">Potencia</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Calcular</button>
                    </form>
                    
                    <div id="resultado" class="mt-4 text-center"></div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("formcalc").addEventListener("submit", function(e) {
            e.preventDefault();
            const dados = new FormData(this);

            fetch("calcular.php", {
                method: "POST",
                body: dados
            })
            .then(res => res.json())
            .then(data => {
                if (data.erro) {
                    document.getElementById("resultado").innerHTML = 
                        '<div class="alert alert-danger shadow-sm">' + data.erro + '</div>';
                } else {
                    document.getElementById("resultado").innerHTML = 
                        '<div class="alert alert-success shadow-sm fw-bold fs-5">Resultado: ' + data.resultado.toFixed(2) + '</div>';
                }
            });
        });
    </script>
</body>
</html>