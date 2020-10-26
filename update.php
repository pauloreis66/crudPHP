<?php
// Incluir ficheiro de configuração
require_once "config.php";
 
// Definir variáveis e inicializar com valores vazios
$nome = $morada = $salario = "";
$nome_err = $morada_err = $salario_err = "";
 
// Processar dados submetidos quando o formulário é enviado
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Obter valor de entrada oculto
    $id = $_POST["id"];
    
    // Validar nome
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $name_err = "Digite um nome.";
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
    
    // Validar salário
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
        // Preparar uma declaração de atualização
        $sql = "UPDATE funcionarios SET nome=?, morada=?, salario=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular as variáveis como parâmetros à instrução preparada
            mysqli_stmt_bind_param($stmt, "sssi", $param_nome, $param_morada, $param_salario, $param_id);
            
            // Definir parâmetros
            $param_nome = $nome;
            $param_morada = $morada;
            $param_salario = $salario;
            $param_id = $id;
            
            // Tentar executar a declaração preparada
            if(mysqli_stmt_execute($stmt)){
                // Registros atualizados com sucesso. Redirecionar para a página de destino
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
} else{
    // Verifique a existência do parâmetro id antes de processar mais
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Obter parâmetro de URL
        $id =  trim($_GET["id"]);
        
        // Prepare uma declaração Select
        $sql = "SELECT * FROM funcionarios WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular as variáveis como parâmetros à instrução preparada
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Definir parâmetros
            $param_id = $id;
            
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
    }  else{
        // URL não contém parâmetro de id válido. Redirecionar para a página de erro
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Registo (Update Record)</title>
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
                        <h2>Atualizar Registo</h2>
                    </div>
                    <p>Edite os valores de entrada e envie para atualizar o registo.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>