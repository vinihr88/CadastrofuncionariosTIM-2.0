<?php
require_once 'funcionarios_crud.php';
require_once 'auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth = new Auth();
$auth->checkAccess();

header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "sistema_cadastroTim");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco: ' . $conn->connect_error]);
    exit;
}

$funcionarioCRUD = new FuncionarioCRUD($conn);
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if ($data === null) {
                echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos']);
                break;
            }
            $camposObrigatorios = ['nome', 'cpf', 'email', 'telefone', 'cargo', 'departamento', 'salario', 'dataAdmissao'];
            foreach ($camposObrigatorios as $campo) {
                if (!isset($data[$campo]) || empty($data[$campo])) {
                    echo json_encode(['success' => false, 'message' => "Campo obrigatório faltando: $campo"]);
                    exit;
                }
            }
            
            if ($funcionarioCRUD->criar($data)) {
                echo json_encode(['success' => true, 'message' => 'Funcionário criado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar funcionário no banco de dados']);
            }
            break;
            
        case 'read':
            if (isset($_GET['id'])) {
                $funcionario = $funcionarioCRUD->buscarPorId($_GET['id']);
                if ($funcionario) {
                    echo json_encode(['success' => true, 'funcionario' => $funcionario]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado']);
                }
            } else {
                $funcionarios = $funcionarioCRUD->listar();
                echo json_encode(['success' => true, 'funcionarios' => $funcionarios]);
            }
            break;
            
        case 'update':
            if (!isset($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
                break;
            }
            
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if ($data === null) {
                echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos']);
                break;
            }
            
            if ($funcionarioCRUD->atualizar($_GET['id'], $data)) {
                echo json_encode(['success' => true, 'message' => 'Funcionário atualizado com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar funcionário no banco de dados']);
            }
            break;
            
        case 'delete':
            if (!isset($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
                break;
            }
            
            if ($funcionarioCRUD->excluir($_GET['id'])) {
                echo json_encode(['success' => true, 'message' => 'Funcionário excluído com sucesso']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao excluir funcionário do banco de dados']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação não reconhecida: ' . $action]);
            break;
    }
} catch (Exception $e) {
    error_log("Erro na API: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor: ' . $e->getMessage()]);
}

$conn->close();

?>
