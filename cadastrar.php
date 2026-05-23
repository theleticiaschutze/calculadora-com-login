<?php
session_start();

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome']  ?? '';
    $login = $_POST['login'] ?? '';
    $senha = $_POST['criarsenha'] ?? '';
    $senha2 = $_POST['confirmasenha'] ?? '';

    if (!$nome || !$login || !$senha) {
        $erro = "Todos os campos devem ser preenchidos!";
    } elseif ($senha != $senha2) {
        $erro = "Senhas estão diferentes!";
    } else {
        $conexao = new mysqli("localhost", "root", "", "teste");

        if ($conexao->connect_error) {
            $erro = "Erro de conexão com o banco de dados!";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conexao->prepare("INSERT INTO usuario (nome, login, senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $login, $senhaHash);

            if ($stmt->execute()) {
                $sucesso = "Sucesso no cadastro! <a href='login.php' class='btn btn-primary btn-sm ms-2'>Faça o login</a>";
            } else {
                $erro = "Erro, login duplicado!";
            }

            $stmt->close();
            $conexao->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastre-se</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script>
    function valida() {
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

<div class="container text-center mt-5">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <p class="lead text-center">Cadastre-se para obter acesso!</p>

        <?php if ($erro): ?>
          <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
          <div class="alert alert-success"><?= $sucesso ?></div>
        <?php endif; ?>

      </div>
    </div>  

    <div class="row">
      <div class="col-lg-8 mx-auto">
        <form method="POST" name="formcadastro" action="<?php echo $_SERVER['PHP_SELF']; ?>">          

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

          <button type="submit" onclick="return valida();" class="btn btn-primary">Cadastrar</button>
           <a href="login.php" class="btn btn-secondary">Já estou cadastrado!</a>
      </form>
          </div>

      

      
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzmYGozFHsqGFes5BVZH4h2QEzTZGLMNGn1AJiDDLiMNMQEGeFACfYQDTZk" crossorigin="anonymous"></script>
</body>

</html>