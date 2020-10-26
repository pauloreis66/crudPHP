<?php
// Verifique a existência do parâmetro id antes de processar mais
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Incluir ficheiro de configuração
    require_once "config.php";
    
    // Preparar uma declaração de Select
    $sql = "SELECT * FROM funcionarios WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Vincular as variáveis como parâmetros à instrução preparada
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Definir parâmetros
        $param_id = trim($_GET["id"]);
        
        // Tentar executar a declaração preparada
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Procura a linha do resultado como uma matriz associativa. 
				Uma vez que o conjunto de resultados contém apenas uma linha, 
				não precisamos usar o loop while */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Recuperar valor de campo individual
                $nome = $row["nome"];
                $morada = $row["morada"];
                $salario = $row["salario"];
            } else{
                // URL não contém parâmetro de id válido. Redirecionar para a página de erro
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
    }
     
    // Fechar declaração
    mysqli_stmt_close($stmt);
    
    // Fechar conexão
    mysqli_close($link);
} else{
    // URL não contém parâmetro de id válido. Redirecionar para a página de erro
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visualizar registo (View Record)</title>
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
                        <h1>Visualizar Registo</h1>
                    </div>
                    <div class="form-group">
                        <label>Nome</label>
                        <p class="form-control-static"><?php echo $row["nome"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Morada</label>
                        <p class="form-control-static"><?php echo $row["morada"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Salario</label>
                        <p class="form-control-static"><?php echo $row["salario"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Voltar</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>