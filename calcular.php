<?php
$val1 = (float)$_POST['val1'];
$val2 = (float)$_POST['val2'];
$op   = $_POST['operacao'];

if ($op == 'somar')           $resul = $val1 + $val2;
elseif ($op == 'subtrair')    $resul = $val1 - $val2;
elseif ($op == 'multiplicar') $resul = $val1 * $val2;
elseif ($op == 'dividir')     $resul = ($val2 != 0) ? $val1 / $val2 : null;

echo json_encode([
    'resultado' => $resul,
    'erro' => ($op == 'dividir' && $val2 == 0) ? 'Divisão por zero!' : null
]);
?>