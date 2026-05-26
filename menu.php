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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .navbar-custom { box-shadow: 0 2px 10px rgba(8, 3, 78, 0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark px-4 navbar-custom">
        <span class="navbar-brand fw-bold">Olá, <?= $_SESSION['nome'] ?>! <button type="button" class="btn btn-link p-0" style="vertical-align: middle;" data-bs-toggle="modal" data-bs-target="#modalCalc">    
            <i class="bi bi-calculator" style="font-size: 1rem; color: white;"></i>
        </button>  </span>
        
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
    </nav>

    <div class="container mt-5" id="conteudo-principal">
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
                                <option value="sqrt">Raiz Quadrada</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Calcular</button>
                    </form>
                    
                    <div id="resultado" class="mt-4 text-center"></div>
                </div>

            </div>
        </div>
    </div>
    
    


<!-- Modal -->
<div class="modal fade" id="modalCalc" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content d-flex flex-column justify-content-center" style="background-color: #111111; border-radius: 26px;">
            <div class="modal-body p-4">
                
                <!-- Display -->
                <div id="display" style="
                    background-color: #1a1a1a;
                    color: #fff;
                    font-size: 2rem;
                    text-align: right;
                    padding: 16px;
                    border-radius: 8px;
                    margin-bottom: 16px;
                    min-height: 70px;
                    word-break: break-all;
                ">0</div>

                <!-- Botões -->
                <div class="d-grid gap-2" style="grid-template-columns: repeat(4, 1fr); display: grid;">
                    <button onclick="limpa()" class="btn btn-secondary btn-lg">C</button>
                    <button onclick="porcentagem()" class="btn btn-secondary btn-lg">%</button>
                    <button onclick="invertesSinal()" class="btn btn-secondary btn-lg">+/-</button>
                    <button onclick="addChar('/')" class="btn btn-warning btn-lg">÷</button>

                    <button onclick="addChar('7')" class="btn btn-dark btn-lg">7</button>
                    <button onclick="addChar('8')" class="btn btn-dark btn-lg">8</button>
                    <button onclick="addChar('9')" class="btn btn-dark btn-lg">9</button>
                    <button onclick="addChar('*')" class="btn btn-warning btn-lg">×</button>

                    <button onclick="addChar('4')" class="btn btn-dark btn-lg">4</button>
                    <button onclick="addChar('5')" class="btn btn-dark btn-lg">5</button>
                    <button onclick="addChar('6')" class="btn btn-dark btn-lg">6</button>
                    <button onclick="addChar('-')" class="btn btn-warning btn-lg">−</button>

                    <button onclick="addChar('1')" class="btn btn-dark btn-lg">1</button>
                    <button onclick="addChar('2')" class="btn btn-dark btn-lg">2</button>
                    <button onclick="addChar('3')" class="btn btn-dark btn-lg">3</button>
                    <button onclick="addChar('+')" class="btn btn-warning btn-lg">+</button>

                    <button onclick="apaga()" class="btn btn-secondary btn-lg">⌫</button>
                    <button onclick="addChar('0')" class="btn btn-dark btn-lg">0</button>
                    <button onclick="addChar('.')" class="btn btn-dark btn-lg">.</button>
                    <button onclick="calcula()" class="btn btn-success btn-lg">=</button>
                </div>

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
        // codigo da calculadora javascript
        const modalCalc = document.getElementById('modalCalc');

        modalCalc.addEventListener('show.bs.modal', () => {
        document.body.style.backgroundImage = "url('img/calcbg.png')";
        });

        modalCalc.addEventListener('hide.bs.modal', () => {
        document.body.style.backgroundImage = "";
        });

         var expressao = "";

    function updateDisplay() {
        document.getElementById("display").innerText = expressao || "0";
    }

    function addChar(c) {
        expressao += c;
        updateDisplay();
    }

    function limpa() {
        expressao = "";
        updateDisplay();
    }

    function apaga() {
        expressao = expressao.slice(0, -1);
        updateDisplay();
    }

    function calcula() {
        try {
            expressao = String(eval(expressao));
            updateDisplay();
        } catch (e) {
            document.getElementById("display").innerText = "Erro";
            expressao = "";
        }
    }

    function porcentagem() {
    try {
        expressao = String(eval(expressao) / 100);
        updateDisplay();
    } catch(e) {
        document.getElementById("display").innerText = "Erro";
        expressao = "";
    }
}

    function invertesSinal() {
    if (expressao.startsWith('-')) {
        expressao = expressao.slice(1);
    } else {
        expressao = '-' + expressao;
    }
    updateDisplay();
}

    //adiciona display none a calculadora php quando eu uso modal
    modalCalc.addEventListener('show.bs.modal', () => {
    document.getElementById('conteudo-principal').classList.add('d-none');
        });

    modalCalc.addEventListener('hide.bs.modal', () => {
    document.getElementById('conteudo-principal').classList.remove('d-none');
        });

        //js para evitar val2 na raiz quadrada
        document.getElementById("operacao").addEventListener("change", function() {
    const val2 = document.getElementById("val2");
    const label2 = document.querySelector("label[for='val2']");
    
    if (this.value === "sqrt") {
        val2.disabled = true;
        val2.placeholder = "Não necessário";
        val2.value = "";
    } else {
        val2.disabled = false;
        val2.placeholder = "Ex: 5";
    }
});
    </script>
</body>
</html>