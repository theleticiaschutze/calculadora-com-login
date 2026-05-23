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
                $sucesso = "Sucesso no cadastro! <a href='login.php' class='btn btn-primary btn-sm ms-2'>Faça o login</a>";
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script>
    function validaLogin() {
    var login = document.forms["formlogin"]["login"].value;
    var senha = document.forms["formlogin"]["senha"].value;

    if (login == "" || senha == "") {
        alert("Preencha todos os campos!");
        return false;
    } else {
        return true;
    }
}

function validaCadastro() {
        var nome   = document.forms["formcadastro"]["nome"].value;
        var login  = document.forms["formcadastro"]["login"].value;
        var senha  = document.forms["formcadastro"]["criarsenha"].value;
        var senha2 = document.forms["formcadastro"]["confirmasenha"].value;

        if (nome == "" || login == "" || senha == "") {
            alert("Preencha todos os campos!");
            return false;
        } else if (senha != senha2) {
            alert("As senhas não conferem!");
            return false;
        } else {
            return true;
        }
    }
  </script>
</head>
<body>

<div class="container text-center mt-5 col-lg-6 mx-auto">
    <h1 class="display-5">Faça login para usar a super calculadora!</h1>
    <p class="text-muted">Faça login ou cadastre-se para continuar.</p>
</div>

<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">

            <?php if ($erro) { ?>
                <div class="alert alert-danger"><?= $erro ?></div>
            <?php }; ?>

            <?php if ($sucesso){ ?>
                <div class="alert alert-success"><?= $sucesso ?></div>
            <?php }; ?>

    
   <?php if ($pagina == 'login') { ?>

   <div class="row">
      <div class="col-lg-8 mx-auto">
        <form method="POST" name="formlogin" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="acao" value="login">
          <div class="form-group mb-2">
            <label for="cadastraLogin">Login</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Seu login">

          </div>
          <div class="form-group mb-3">
            <label for="criarSenha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
          </div>

          <button type="submit" onclick="return validaLogin();" class="btn btn-primary">Login</button>
          <a href="?pagina=cadastro" class="btn btn-secondary">Cadastrar</a>
        </form>
      </div>
    </div>   <?php } elseif ($pagina == 'cadastro') { ?>

     <div class="row">
      <div class="col-lg-8 mx-auto">
        <form method="POST" name="formcadastro" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
            
        
            <input type="hidden" name="acao" value="cadastro">
        <div class="form-group mb-2">
            <label for="nome">Seu nome</label>
            <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome!">
          </div>

        <div class="form-group mb-2">
            <label for="login">Criar Login</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Seu Login aqui!">
          </div>
          <div class="form-group mb-2">
            <label for="criarSenha">Senha</label>
            <input type="password" class="form-control" id="criarsenha" name="criarsenha" placeholder="Senha">
          </div>
          <div class="form-group mb-2">
            <label for="criarSenha">Confirmar senha</label>
            <input type="password" class="form-control" id="confirmasenha" name="confirmasenha" placeholder="Confirme a senha">
          </div>

          <button type="submit" onclick="return validaCadastro();" class="btn btn-primary">Cadastrar</button>
           <a href="?pagina=login" class="btn btn-secondary">Já estou cadastrado!</a>
      </form>
          </div>



     <?php }; ?>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzmYGozFHsqGFes5BVZH4h2QEzTZGLMNGn1AJiDDLiMNMQEGeFACfYQDTZk" crossorigin="anonymous"></script>
    
</body>
</html>