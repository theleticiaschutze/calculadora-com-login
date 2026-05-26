<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
     <script>
        function valida() {
            var val1 = document.forms["calc"]["val1"].value;
            var val2 = document.forms["calc"]["val2"].value;
            if (val1 == "" || val2 == "") {
                alert("Favor informar os dois valores!");
                return false;
            } else if (isNaN(val1) || isNaN(val2)) {
                alert("O valor informado nãe é um número!");
                return false;
            } else {
                return true;
            }
        }
    </script>
</head>
<body>
    <h1>Super Calculadora 2000</h1>
    
        <form name="calc" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            Primeiro Valor: <input type="text" name="val1" /><br /> 
            Segundo Valor: <input type="text" name="val2" /><br /> 
            Tipo de Operação: <select name="op">
                <option value="somar">Somar</option>
                <option value="subtrair">Subtrair</option>
                <option value="multiplicar">Multiplicar</option>
                <option value="dividir">Dividir</option>
                            </select><br />
            <input type="submit" value="Calcular!" onclick="return valida();" />
        </form>
        <br><br>
        <a href="index.php"> <<< Voltar </a>
    <?php
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $val1 = isset($_POST['val1']) ? $_POST['val1'] : 0;
        $val2 = isset($_POST['val2']) ? $_POST['val2'] : 0;
        $op = $_POST['op'];

        if ($op == 'somar') {$resul = $val1 + $val2;
        } elseif ($op == 'subtrair') { $resul = $val1 - $val2;
        } elseif ($op == 'multiplicar') { $resul = $val1 * $val2;
        } elseif ($op == 'dividir') {$resul = ($val2 != 0) ? $val1 / $val2 : "Não divida por 0!!!!!!!!";
        }

        if (is_string($resul)) { echo $resul;
        } else {
        printf("Resultado: %.2f %s %.2f = %.2f", $val1, $op, $val2, $resul);
        }
       
    } 
    
    ?>

</body>
    
    

</html>