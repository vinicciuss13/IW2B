<?php
 header("Content-Type: application/json; charset=utf-8");
 header("Access-Control-Allow-Origin: *");
 header("access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

 $host="localhost";
 $usuario="root";
 $senha="";
 $db="cadastro";
 $con=new mysqli($host,$usuario,$senha,$db);

 if($con ->connect_error){
    http_response_code(500);
    echo json_encode(["error" => "falha de conexao: " . $con->connect_error]);
    exit;
 }
 $method= $_SERVER['REQUEST_METHOD'];

 switch($method){
    case "GET":
        if(isset($_GET['pesquisa'])){
            $pesquisa = "%" . $_GET['pesquisa'] . "%";
            $stmt= $con ->prepare("SELECT * FROM usuarios WHERE NOME LIKE ?");
            $stmt ->bind_param("s",$pesquisa);
            $stmt ->execute();
            $result = $stmt->get_result();
        }
        else{
            $result=$con->query("SELECT * FROM usuarios order by ID desc");
        }
        $retorno = [];
        while ($linha = $result->fetch_assoc()){
            $retorno[]= $linha;
        }
        echo json_encode($retorno);
        break;
    
    case "POST":
        $data= json_decode(file_get_contents("php://input"),true);
        $stmt=$con->prepare("INSERT INTO usuarios (NOME,PAGINAS,PRECO,ATIVO) VALUES(?,?,?,?)");
        $stmt->bind_param("sisi",$data['NOME'],$data['PAGINAS'],$data['PRECO'],$data['ATIVO']);
        $stmt->execute();
        echo json_encode(["status" => "ok", "insert_id" =>$stmt->insert_id]);
        break;
    
    case "PUT":
        $data= json_decode(file_get_contents("php://input"),true);
        $stmt=$con->prepare("UPDATE usuarios SET NOME=?,PAGINAS=?,PRECO=?,ATIVO=? WHERE ID=?");
        $stmt->bind_param("sisii",$data["NOME"],$data["PAGINAS"],$data["PRECO"],$data["ATIVO"],$data["ID"]);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;
    
    case "DELETE":
        $id=$_GET["id"];
        $stmt=$con->prepare("DELETE FROM usuarios WHERE ID=?");
        $stmt ->bind_param("i",$id);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;
    }
    
    $con->close();
?>