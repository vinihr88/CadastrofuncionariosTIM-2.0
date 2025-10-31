<?php
require_once 'funcionarios_crud.php';
require_once 'auth.php';

// Iniciar sessão e verificar autenticação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$auth = new Auth();
$auth->checkAccess();

header('Content-Type: application/json');

// Criar conexão
$conn = new mysqli("localhost", "root", "", "sistema_cadastroTim");

// Verificar se há erro de conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco: ' . $conn->connect_error]);
    exit;
}

$funcionarioCRUD = new FuncionarioCRUD($conn);
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            // Criar funcionário
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if ($data === null) {
                echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos']);
                break;
            }
            
            // Validar campos obrigatórios
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
            // Ler funcionários
            if (isset($_GET['id'])) {
                // Ler um funcionário específico
                $funcionario = $funcionarioCRUD->buscarPorId($_GET['id']);
                if ($funcionario) {
                    echo json_encode(['success' => true, 'funcionario' => $funcionario]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Funcionário não encontrado']);
                }
            } else {
                // Ler todos os funcionários
                $funcionarios = $funcionarioCRUD->listar();
                echo json_encode(['success' => true, 'funcionarios' => $funcionarios]);
            }
            break;
            
        case 'update':
            // Atualizar funcionário
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
            // Excluir funcionário
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