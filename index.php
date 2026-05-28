<?php
session_start();

$pagina = $_GET['pagina'] ?? 'login';
$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'login') 
    {
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!$login || !$senha) {
        $erro = "Todos os campos devem ser preenchidos!";
    } else {
        $conexao = new mysqli("localhost", "root", "", "teste");

        if ($conexao->connect_error) {
            $erro = "Erro de conexão com o banco de dados!";
        } else {
            $stmt = $conexao->prepare("SELECT id, nome, senha FROM usuario WHERE login = ?");
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $stmt->bind_result($id, $nome, $senhaHash);
            $stmt->fetch();
            $stmt->close();
            $conexao->close();

            if ($nome && password_verify($senha, $senhaHash)) {
                $_SESSION['logged'] = true;
                $_SESSION['nome'] = $nome;
                header("Location: menu.php");
                exit;
            } else {
                $erro = "Login ou senha incorreto!";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'cadastro') {
    $nome  = $_POST['nome']  ?? '';
    $login = $_POST['login'] ?? '';
    $senha = $_POST['criarsenha'] ?? '';
    $senha2 = $_POST['confirmasenha'] ?? '';

    if (!$nome || !$login || !$senha) {
        $erro = "Todos os campos devem ser preenchidos!";
    } elseif ($senha != $senha2) {
        $erro = "Senhas estão diferentes!";
    } else {
        $conn = new mysqli("localhost", "root", "", "teste");

        if ($conn->connect_error) {
            $erro = "Erro de conexão com o banco de dados!";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuario (nome, login, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $login, $senhaHash);

            if ($stmt->execute()) {
                $sucesso = "Sucesso no cadastro!";
            } else {
                $erro = "Erro, login duplicado!";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem Vindo!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
    <script>
        function validaLogin() {
            var login = document.forms["formlogin"]["login"].value;
            var senha = document.forms["formlogin"]["senha"].value;
            if(login == null || login == "" || senha == null || senha == "") {
                alert("Preencha todos os campos!");
                return false;
            }
            return true;
        }

        function validaCadastro() {
            var nome   = document.forms["formcadastro"]["nome"].value;
            var login  = document.forms["formcadastro"]["login"].value;
            var senha  = document.forms["formcadastro"]["criarsenha"].value;
            var senha2 = document.forms["formcadastro"]["confirmasenha"].value;
            if (nome == null || nome == "" || login == null || login == "" || senha == null || senha == "") {
                alert("Preencha todos os campos!");
                return false;
            } else if (senha != senha2) {
                alert("As senhas não conferem!");
                return false;
            }
            return true;
        }
    </script>   
</head>
<body class="d-flex flex-column min-vh-100 justify-content-center">

    <div class="container text-center mb-1">
        <h1 class="display-5 fw-bold text-primary">Super Calculadora</h1>
        <p class="text-muted">Faça login ou cadastre-se para continuar.</p>
    </div>

    <a href="calc_v01.php" title="Vai clicar?">
    <img src="img/calctoon.png" class="img-fluid mx-auto d-block mb-4" style="width: 120px;" alt="Calculadora fofinha feliz">
    </a>

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">

                <?php if ($erro) { ?>
                    <div class="alert alert-danger shadow-sm"><?= $erro ?></div>
                <?php }; ?>

                <?php if ($sucesso){ ?>
                    <div class="alert alert-success shadow-sm"><?= $sucesso ?></div>
                <?php }; ?>

                <div class="card card-custom p-4">
                    <?php if (isset($_SESSION['logged'])) { ?>
                         <div class="alert alert-danger shadow-sm">Você está logado!</div>
                            <a href="logout.php" class="btn btn-primary btn-sm mb-3">Sair</a>
                             <a href="menu.php" class="btn btn-primary btn-sm">Voltar</a>
                    <?php } else { ?>

                    <?php if ($pagina == 'login') { ?>
                        <h4 class="mb-4">Entrar</h4>
                        <form method="POST" name="formlogin" action="?pagina=login" class="text-start">
                            <input type="hidden" name="acao" value="login">
                            
                            <div class="form-group mb-3">
                                <label for="login" class="fw-semibold mb-1">Login</label>
                                <input type="text" class="form-control form-control-lg" id="login" name="login" placeholder="Seu login">
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="senha" class="fw-semibold mb-1">Senha</label>
                                <input type="password" class="form-control form-control-lg" id="senha" name="senha" placeholder="Sua senha">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" onclick="return validaLogin();" class="btn btn-primary btn-lg">Login</button>
                                <a href="?pagina=cadastro" class="btn btn-outline-secondary">Criar uma conta</a>
                            </div>
                        </form>

                    <?php } else if ($pagina == 'cadastro') { ?>
                        <h4 class="mb-4">Criar Conta</h4>
                        <form method="POST" name="formcadastro" action="?pagina=cadastro" class="text-start"> 
                            <input type="hidden" name="acao" value="cadastro">
                            
                            <div class="form-group mb-3">
                                <label for="nome" class="fw-semibold mb-1">Seu nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome...">
                            </div>

                            <div class="form-group mb-3">
                                <label for="login" class="fw-semibold mb-1">Criar Login</label>
                                <input type="text" class="form-control" id="login" name="login" placeholder="Login...">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="criarsenha" class="fw-semibold mb-1">Senha</label>
                                <input type="password" class="form-control" id="criarsenha" name="criarsenha" placeholder="Crie uma senha...">
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="confirmasenha" class="fw-semibold mb-1">Confirmar senha</label>
                                <input type="password" class="form-control" id="confirmasenha" name="confirmasenha" placeholder="Repita a senha...">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" onclick="return validaCadastro();" class="btn btn-success btn-lg">Cadastrar</button>
                                <a href="?pagina=login" class="btn btn-outline-secondary">Já tenho conta</a>
                            </div>
                        </form>
                    <?php }; ?>
                </div>
                <?php } ?>
</div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>