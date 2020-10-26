<?php
/* Credenciais da base de dados. 
Supondo que estejas a executar o servidor MySQL
com configuração padrão (utilizador 'root' sem senha) * */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'psim19db');
 
/* Tentativa de conexão com a base de dados MySQL */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Verificar a conexão
if($link === false){
    die("ERRO: Não consegue a ligação à Base de Dados. " . mysqli_connect_error());
}
?>

