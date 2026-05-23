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
    <title>Area Restrita!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   
</head>

<body>


    <nav class="navbar navbar-light bg-light px-4">
    <span class="navbar-brand">Bem vindo, <?= $_SESSION['nome'] ?>!</span>    
    <a href="logout.php" class="btn btn-danger btn-sm">Fazer logout</a>
    </nav>

    <div class="container text-center mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="lead text-center">
                    Bem vindo, <?= $_SESSION['nome'] ?>! <br>
                    Área Restrita, sinta-se livre para... fazer logout.
                </p>
            </div>
        </div>
        


    </div>

    <div class="row justify-content-center">
     <div class="col-lg-2">
    <form id="formcalc">
    <input type="number" name="val1" id="val1" class="form-control" placeholder="Valor 1">
    <input type="number" name="val2" id="val2" class="form-control" placeholder="Valor 2">
    <select name="operacao" id="operacao" class="form-control">
        <option value="somar">Somar</option>
        <option value="subtrair">Subtrair</option>
        <option value="multiplicar">Multiplicar</option>
        <option value="dividir">Dividir</option>
    </select>
    <button type="submit" class="btn btn-primary mt-2">Calcular</button>
</form>
<div id="resultado"></div>
</div>
</div>


   

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzmYGozFHsqGFes5BVZH4h2QEzTZGLMNGn1AJiDDLiMNGn1AJiDDLiMNMQEGeFACfYQDTZk" crossorigin="anonymous"></script>
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
                        '<div class="alert alert-danger">' + data.erro + '</div>';
                } else {
                    document.getElementById("resultado").innerHTML = 
                        '<div class="alert alert-success">Resultado: ' + data.resultado.toFixed(2) + '</div>';
                }
            });
        });
    </script>
</body>

</html>