<?php
session_start();

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script>
    function valida() {
    var login = document.forms["formlogin"]["login"].value;
    var senha = document.forms["formlogin"]["senha"].value;

    if (login == "" || senha == "") {
        alert("Preencha todos os campos!");
        return false;
    } else {
        return true;
    }
}
  </script>
</head>

<body>

  <div class="container text-center mt-5">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <p class="lead text-center">Faça o login
        </p>
          <?php if ($erro){ ?>
          <div class="alert alert-danger"><?= $erro ?></div>
          <?php }; ?>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mx-auto">
        <form method="POST" name="formlogin" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group mb-2">
            <label for="cadastrarEmail">Login</label>
            <input type="text" class="form-control" id="login" name="login" placeholder="Seu login">

          </div>
          <div class="form-group mb-3">
            <label for="criarSenha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
          </div>

          <button type="submit" onclick="return valida();" class="btn btn-primary">Login</button>
          <a href="cadastrar.php" class="btn btn-secondary">Cadastrar</a>
        </form>
      </div>
    </div>   

         

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzmYGozFHsqGFes5BVZH4h2QEzTZGLMNGn1AJiDDLiMNMQEGeFACfYQDTZk" crossorigin="anonymous"></script>
      
</body>

</html>