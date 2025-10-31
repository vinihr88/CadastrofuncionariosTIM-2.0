<?php
require_once 'conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'list':
        listarFuncionarios();
        break;
    
    case 'get':
        if (isset($_GET['id'])) {
            getFuncionario($_GET['id']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
        }
        break;
    
    case 'insert':
        inserirFuncionario();
        break;
    
    case 'update':
        if (isset($_POST['id'])) {
            atualizarFuncionario($_POST['id']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
        }
        break;
    
    case 'delete':
        if (isset($_POST['id'])) {
            excluirFuncionario($_POST['id']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
        }
        break;
    
    default:
        echo json_encode(['success' => false, 'message' => 'Ação não reconhecida: ' . $action]);
        break;
}

function listarFuncionarios() {
    global $conn;
    
    try {
        $sql = "SELECT f.*, d.nome AS departamento, f.data_admissao AS dataAdmissao
                FROM funcionarios f 
                LEFT JOIN departamentos d ON f.departamento_id = d.id 
                ORDER BY f.nome";
        $result = $conn->query($sql);
        
        if (!$result) {
            throw new Exception("Erro na consulta: " . $conn->error);
        }
        
        $funcionarios = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $funcionarios[] = $row;
            }
        }
        
        echo json_encode(['success' => true, 'data' => $funcionarios]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao carregar dados: ' . $e->getMessage()]);
    }
}

function getFuncionario($id) {
    global $conn;
    
    $id = $conn->real_escape_string($id);
    $sql = "SELECT f.*, d.nome AS departamento, f.data_admissao AS dataAdmissao
            FROM funcionarios f
            LEFT JOIN departamentos d ON f.departamento_id = d.id
            WHERE f.id = '$id'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $funcionario = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $funcionario]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado']);
    }
}

function inserirFuncionario() {
    global $conn;
    
    try {
        $campos_obrigatorios = ['nome', 'cpf', 'email', 'telefone', 'cargo', 'departamento', 'salario', 'dataAdmissao'];
        foreach ($campos_obrigatorios as $campo) {
            if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                echo json_encode(['success' => false, 'message' => "Campo obrigatório não preenchido: $campo"]);
                return;
            }
        }
        
        $nome = $conn->real_escape_string($_POST['nome']);
        $cpf = $conn->real_escape_string($_POST['cpf']);
        $email = $conn->real_escape_string($_POST['email']);
        $telefone = $conn->real_escape_string($_POST['telefone']);
        
        $verificaCpf = "SELECT id FROM funcionarios WHERE cpf = '$cpf'";
        $resultCpf = $conn->query($verificaCpf);
        if ($resultCpf && $resultCpf->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => "CPF $cpf já está cadastrado no sistema. Cada funcionário deve ter um CPF único."]);
            return;
        }
        $cargo = $conn->real_escape_string($_POST['cargo']);
        $departamento = $conn->real_escape_string($_POST['departamento']);
        $salario = $conn->real_escape_string($_POST['salario']);
        $dataAdmissao = $conn->real_escape_string($_POST['dataAdmissao']);
        
        $sql_check_dept = "SELECT id FROM departamentos WHERE nome = '$departamento'";
        $result_dept = $conn->query($sql_check_dept);
        
        $departamento_id = null;
        
        if ($result_dept && $result_dept->num_rows > 0) {
            $row = $result_dept->fetch_assoc();
            $departamento_id = $row['id'];
        } else {
            $sql_insert_dept = "INSERT INTO departamentos (nome, descricao) VALUES ('$departamento', 'Departamento criado automaticamente')";
            if ($conn->query($sql_insert_dept) === TRUE) {
                $departamento_id = $conn->insert_id;
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar departamento: ' . $conn->error]);
                return;
            }
        }
        
        $sql = "INSERT INTO funcionarios (nome, cpf, email, telefone, cargo, departamento_id, salario, data_admissao) 
                VALUES ('$nome', '$cpf', '$email', '$telefone', '$cargo', $departamento_id, '$salario', '$dataAdmissao')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Funcionário cadastrado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar: ' . $e->getMessage()]);
    }
}

function atualizarFuncionario($id) {
    global $conn;
    
    try {
        $id = $conn->real_escape_string($id);
        $nome = $conn->real_escape_string($_POST['nome']);
        $cpf = $conn->real_escape_string($_POST['cpf']);
        $email = $conn->real_escape_string($_POST['email']);
        $telefone = $conn->real_escape_string($_POST['telefone']);
        $cargo = $conn->real_escape_string($_POST['cargo']);
        $departamento_nome = $conn->real_escape_string($_POST['departamento']);
        $salario = $conn->real_escape_string($_POST['salario']);
        $dataAdmissao = $conn->real_escape_string($_POST['dataAdmissao']);
        
        $departamento_id = null;
        $sql_check_dept = "SELECT id FROM departamentos WHERE nome = '$departamento_nome'";
        $result_dept = $conn->query($sql_check_dept);
        if ($result_dept && $result_dept->num_rows > 0) {
            $row = $result_dept->fetch_assoc();
            $departamento_id = (int)$row['id'];
        } else {
            $sql_insert_dept = "INSERT INTO departamentos (nome, descricao) VALUES ('$departamento_nome', 'Departamento criado automaticamente')";
            if ($conn->query($sql_insert_dept) === TRUE) {
                $departamento_id = (int)$conn->insert_id;
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar departamento: ' . $conn->error]);
                return;
            }
        }
        
        $sql_check_cpf = "SELECT id FROM funcionarios WHERE cpf = '$cpf' AND id <> '$id'";
        $result_cpf = $conn->query($sql_check_cpf);
        if ($result_cpf && $result_cpf->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => "CPF $cpf já está cadastrado para outro funcionário."]);
            return;
        }
        
        $sql = "UPDATE funcionarios SET 
                nome = '$nome', 
                cpf = '$cpf', 
                email = '$email', 
                telefone = '$telefone', 
                cargo = '$cargo', 
                departamento_id = $departamento_id, 
                salario = '$salario', 
                data_admissao = '$dataAdmissao' 
                WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Funcionário atualizado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar atualização: ' . $e->getMessage()]);
    }
}

function excluirFuncionario($id) {
    global $conn;
    
    try {
        $id = $conn->real_escape_string($id);
        $sql = "DELETE FROM funcionarios WHERE id = '$id'";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Funcionário excluído com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir: ' . $conn->error]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar exclusão: ' . $e->getMessage()]);
    }
}
?>
