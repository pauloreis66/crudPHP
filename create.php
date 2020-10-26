<?php
// Incluir ficheiro de configuração
require_once "config.php";
 
// Definir variáveis e inicializar com valores vazios
$nome = $morada = $salario = "";
$nome_err = $morada_err = $salario_err = "";
 
// Processar dados submetidos quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validar nome
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_err = "Digite um nome.";
    } elseif(!filter_var($input_nome, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nome_err = "Digite um nome válido.";
    } else{
        $nome = $input_nome;
    }
    
    // Validar morada
    $input_morada = trim($_POST["morada"]);
    if(empty($input_morada)){
        $morada_err = "Digite uma morada.";     
    } else{
        $morada = $input_morada;
    }
    
    // Validar salario
    $input_salario = trim($_POST["salario"]);
    if(empty($input_salario)){
        $salario_err = "Digite um valor para o salário.";     
    } elseif(!ctype_digit($input_salario)){
        $salario_err = "Digite um valor inteiro positivo para o salário.";
    } else{
        $salario = $input_salario;
    }
    
    // Verificar os erros de entrada antes de inserir na base de dados
    if(empty($nome_err) && empty($morada_err) && empty($salario_err)){
        // Preparar uma declaração de inserção
        $sql = "INSERT INTO funcionarios (nome, morada, salario) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
			// Vincular as variáveis como parâmetros à instrução preparada 
            mysqli_stmt_bind_param($stmt, "sss", $param_nome, $param_morada, $param_salario);
            
            // Definir parâmetros
            $param_nome = $nome;
            $param_morada = $morada;
            $param_salario = $salario;
            
            // Tentar executar a declaração preparada
            if(mysqli_stmt_execute($stmt)){
                // Registos criados com sucesso. Redirecionar para a página de destino
                header("location: index.php");
                exit();
            } else{
                echo "Algo errado não está certo. Por favor, tente novamente mais tarde.";
            }
        }
         
        // Fechar declaração
        mysqli_stmt_close($stmt);
    }
    
    // Fechar conexão
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inserir Novo Registo (Create Record)</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Criar registo</h2>
                    </div>
                    <p>Preencha este formulário e envie para adicionar o registo do funcionário à base de dados.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($morada_err)) ? 'has-error' : ''; ?>">
                            <label>Morada</label>
                            <textarea name="morada" class="form-control"><?php echo $morada; ?></textarea>
                            <span class="help-block"><?php echo $morada_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salario_err)) ? 'has-error' : ''; ?>">
                            <label>Salario</label>
                            <input type="text" name="salario" class="form-control" value="<?php echo $salario; ?>">
                            <span class="help-block"><?php echo $salario_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>






















