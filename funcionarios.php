<?php
require_once 'auth.php';
require_once 'conexao.php'; // Incluindo o arquivo de conexão com o banco

$auth = new Auth();
$auth->checkAccess();

// Criar tabela de funcionários se não existir
$sql = "CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    departamento VARCHAR(50) NOT NULL,
    salario DECIMAL(10,2) NOT NULL,
    dataAdmissao DATE NOT NULL
)";

if ($conn->query($sql) !== TRUE) {
    echo "Erro ao criar tabela: " . $conn->error;
}
?>
<!-- O resto do HTML mantém igual -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cadastro</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
        :root {
            --primary-color: #0066cc;
            --primary-light: #3a89e9;
            --primary-dark: #004c99;
            --secondary-color: #f8f9fa;
            --accent-color: #00b8d4;
            --text-color: #333;
            --text-light: #6c757d;
            --white: #ffffff;
            --danger: #e53935;
            --success: #43a047;
            --warning: #fb8c00;
            --border-radius: 8px;
            --box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-color);
            position: relative;
            overflow-x: hidden;
        }

        /* Partículas de fundo */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 102, 204, 0.1);
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-20px) translateX(10px);
            }
            50% {
                transform: translateY(-10px) translateX(-15px);
            }
            75% {
                transform: translateY(15px) translateX(5px);
            }
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
        }

        .header h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--accent-color);
            border-radius: 2px;
            transition: var(--transition);
        }

        .header h1:hover::after {
            width: 100%;
        }

        .logout-btn {
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logout-btn i {
            font-size: 14px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            margin-bottom: 30px;
            transition: var(--transition);
            transform: translateY(0);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        h2 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            padding-bottom: 12px;
            font-size: 1.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
            gap: 10px 0;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
            padding: 0 15px;
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: var(--transition);
        }

        input, select {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: var(--transition);
            background-color: var(--white);
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.15);
        }

        input:focus + .input-icon,
        select:focus + .input-icon {
            color: var(--primary-color);
        }
        
        /* Efeitos de foco */
        .input-focus {
            transform: translateY(-2px);
        }
        
        .label-focus {
            color: var(--primary-color);
            font-weight: 600;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }
        
        /* Animação de sucesso */
        .success-animation {
            background: linear-gradient(135deg, var(--success), #2e7d32) !important;
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #495057, #6c757d);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #c62828);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c62828, var(--danger));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table-container {
            overflow-x: auto;
            border-radius: var(--border-radius);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: rgba(0, 102, 204, 0.05);
            font-weight: 600;
            color: var(--primary-dark);
            position: relative;
        }

        th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), transparent);
        }

        tr {
            transition: var(--transition);
        }

        tr:hover {
            background-color: rgba(0, 102, 204, 0.03);
        }

        td.actions {
            display: flex;
            gap: 8px;
        }

        td.actions .btn {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* ---------- RESPONSIVIDADE ---------- */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 15px;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .header h1::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .btn {
                font-size: 0.9rem;
                padding: 10px 18px;
            }
            
            .form-group {
                min-width: 100%;
            }
            
            .card {
                padding: 20px 15px;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 12px 10px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
                margin: 20px auto;
            }
            
            .form-row {
                margin: 0;
            }
            
            .form-group {
                padding: 0 5px;
            }
            
            input, select {
                padding: 10px 10px 10px 35px;
                font-size: 0.9rem;
            }
            
            .input-icon {
                left: 10px;
            }
            
            td.actions {
                flex-direction: column;
            }
        }
    </style>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal {
            background: #ffffff;
            color: var(--text-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 90%;
            max-width: 420px;
            padding: 24px;
            text-align: center;
        }
        .modal h3 {
            margin: 0 0 10px 0;
            font-size: 1.1rem;
        }
        .modal p {
            margin: 0 0 16px 0;
            color: var(--text-light);
            font-size: 0.95rem;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
    </style>
</style>
</head>
<body>
    <!-- Partículas de fundo -->
    <div class="particles" id="particles"></div>

    <div class="header">
        <h1>Sistema de Funcionários</h1>
        <button class="logout-btn" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Sair</button>
    </div>

    <!-- Modal de confirmação para demitir funcionário -->
    <div id="confirmDeleteModal" class="modal-overlay">
        <div class="modal">
            <h3>Certeza que deseja demitir esse funcionário?</h3>
            <p>Esta ação não pode ser desfeita.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <button class="btn btn-danger" onclick="confirmDelete()">Demitir</button>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Formulário de Cadastro/Edição -->
        <div class="card">
            <h2 id="formTitle">Cadastrar Funcionário</h2>
            <form id="funcionarioForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Completo:</label>
                        <div class="input-container">
                            <input type="text" id="nome" name="nome" required>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <div class="input-container">
                            <input type="text" id="cpf" name="cpf" required>
                            <i class="fas fa-id-card input-icon"></i>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <div class="input-container">
                            <input type="email" id="email" name="email" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <div class="input-container">
                            <input type="tel" id="telefone" name="telefone" required>
                            <i class="fas fa-phone input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cargo">Cargo:</label>
                        <div class="input-container">
                            <input type="text" id="cargo" name="cargo" required>
                            <i class="fas fa-briefcase input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="departamento">Departamento:</label>
                        <div class="input-container">
                            <select id="departamento" name="departamento" required>
                                <option value="">Selecione...</option>
                                <option value="TI">TI</option>
                                <option value="RH">RH</option>
                                <option value="Vendas">Vendas</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Financeiro">Financeiro</option>
                                <option value="Atendimento">Atendimento</option>
                                <option value="Operações">Operações</option>
                                <option value="Suporte">Suporte</option>
                                <option value="Logística">Logística</option>
                                <option value="Jurídico">Jurídico</option>
                                <option value="Compras">Compras</option>
                                <option value="Produção">Produção</option>
                                <option value="Qualidade">Qualidade</option>
                                <option value="Planejamento">Planejamento</option>
                                <option value="Administração">Administração</option>
                                <option value="P&D">P&D</option>
                                <option value="Segurança">Segurança</option>
                                <option value="Auditoria">Auditoria</option>
                                <option value="Desenvolvimento">Desenvolvimento</option>
                                <option value="Infraestrutura">Infraestrutura</option>
                                <option value="Produto">Produto</option>
                                <option value="Backoffice">Backoffice</option>
                            </select>
                            <i class="fas fa-building input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="salario">Salário:</label>
                        <div class="input-container">
                            <input type="number" id="salario" name="salario" step="0.01" required>
                            <i class="fas fa-money-bill-wave input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dataAdmissao">Data de Admissão:</label>
                        <div class="input-container">
                            <input type="date" id="dataAdmissao" name="dataAdmissao" required>
                            <i class="fas fa-calendar-alt input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Cadastrar</span>
                        <i class="fas fa-save"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelBtn" onclick="cancelEdit()" style="display: none;">
                        <span class="btn-text">Cancelar</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Funcionários -->
        <div class="card">
            <h2>Funcionários Cadastrados</h2>
            <div class="table-container">
                <table id="tabelaFuncionarios">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Salário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaBody">
                        <!-- Os dados serão inseridos aqui via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Criar partículas de fundo
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 15;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Tamanho aleatório
                const size = Math.random() * 50 + 20;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Posição aleatória
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.left = `${Math.random() * 100}%`;
                
                // Atraso na animação
                particle.style.animationDelay = `${Math.random() * 5}s`;
                
                particlesContainer.appendChild(particle);
            }
        });

        // Verificar se o usuário está logado (via session PHP)
        // Se não estiver logado, redireciona para index.php
        fetch('check_auth.php')
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    window.location.href = 'index.php';
                }
            });

        let editandoId = null;

        // Elementos do DOM
        const form = document.getElementById('funcionarioForm');
        const tabelaBody = document.getElementById('tabelaBody');
        const formTitle = document.getElementById('formTitle');
        const submitBtn = document.getElementById('submitBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        // Inicializar a tabela
        atualizarTabela();

        // Adicionar efeitos de foco nos inputs
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focus');
                const label = this.closest('.form-group').querySelector('label');
                if (label) label.classList.add('label-focus');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focus');
                const label = this.closest('.form-group').querySelector('label');
                if (label) label.classList.remove('label-focus');
            });
        });

        // Evento de submit do formulário
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nome = document.getElementById('nome').value;
            const cpf = document.getElementById('cpf').value;
            const email = document.getElementById('email').value;
            const telefone = document.getElementById('telefone').value;
            const cargo = document.getElementById('cargo').value;
            const departamento = document.getElementById('departamento').value;
            const salario = document.getElementById('salario').value;
            const dataAdmissao = document.getElementById('dataAdmissao').value;
            
            // Enviar dados para o servidor via AJAX
            const formData = new FormData();
            formData.append('nome', nome);
            formData.append('cpf', cpf);
            formData.append('email', email);
            formData.append('telefone', telefone);
            formData.append('cargo', cargo);
            formData.append('departamento', departamento);
            formData.append('salario', salario);
            formData.append('dataAdmissao', dataAdmissao);
            
            if (editandoId !== null) {
                formData.append('id', editandoId);
                formData.append('action', 'update');
            } else {
                formData.append('action', 'insert');
            }
            
            fetch('funcionarios_action.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Resposta recebida:', response);
                return response.json();
            })
            .then(data => {
                console.log('Dados processados:', data);
                if (data.success) {
                    // Animação de sucesso
                    const submitButton = document.getElementById('submitBtn');
                    submitButton.classList.add('success-animation');
                    
                    setTimeout(() => {
                        submitButton.classList.remove('success-animation');
                        // Atualizar a tabela e resetar o formulário
                        atualizarTabela();
                        form.reset();
                        cancelEdit();
                        editandoId = null;
                    }, 800);
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao processar a solicitação. Verifique o console para mais detalhes.');
            });
        });

        function editarFuncionario(id) {
            // Buscar dados do funcionário pelo ID
            fetch(`funcionarios_action.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Erro: ' + (data.message || 'Não foi possível carregar o funcionário'));
                        return;
                    }
                    const funcionario = data.data;
                    // Preencher o formulário com os dados do funcionário
                    document.getElementById('nome').value = funcionario.nome;
                    document.getElementById('cpf').value = funcionario.cpf;
                    document.getElementById('email').value = funcionario.email;
                    document.getElementById('telefone').value = funcionario.telefone;
                    document.getElementById('cargo').value = funcionario.cargo;
                    document.getElementById('departamento').value = funcionario.departamento || '';
                    document.getElementById('salario').value = funcionario.salario;
                    document.getElementById('dataAdmissao').value = funcionario.dataAdmissao;
                    
                    // Atualizar o estado do formulário
                    formTitle.textContent = 'Editar Funcionário';
                    document.querySelector('#submitBtn .btn-text').textContent = 'Atualizar';
                    document.querySelector('#submitBtn i').className = 'fas fa-sync-alt';
                    cancelBtn.style.display = 'inline-flex';
                    editandoId = id;
                    
                    // Scroll suave até o formulário
                    document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Erro ao buscar dados do funcionário:', error);
                    alert('Erro ao carregar dados do funcionário.');
                });
        }

        let pendingDeleteId = null;

        function openDeleteModal(id) {
            pendingDeleteId = id;
            const modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            pendingDeleteId = null;
            const modal = document.getElementById('confirmDeleteModal');
            modal.style.display = 'none';
        }

        function confirmDelete() {
            if (!pendingDeleteId) return;
            fetch('funcionarios_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&id=${pendingDeleteId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    atualizarTabela();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao excluir o funcionário.');
            })
            .finally(() => {
                closeDeleteModal();
            });
        }

        function excluirFuncionario(id) {
            openDeleteModal(id);
        }

        function cancelEdit() {
            editandoId = null;
            form.reset();
            formTitle.textContent = 'Cadastrar Funcionário';
            document.querySelector('#submitBtn .btn-text').textContent = 'Cadastrar';
            document.querySelector('#submitBtn i').className = 'fas fa-save';
            cancelBtn.style.display = 'none';
        }

        function atualizarTabela() {
            // Buscar dados do servidor
            fetch('funcionarios_action.php?action=list')
                .then(response => response.json())
                .then(data => {
                    tabelaBody.innerHTML = '';
                    
                    // Verificar se a resposta foi bem-sucedida
                    if (!data.success) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td colspan="7" class="no-data">Erro: ${data.message || 'Erro desconhecido'}</td>`;
                        tabelaBody.appendChild(tr);
                        return;
                    }
                    
                    // Verificar se há dados
                    if (!data.data || data.data.length === 0) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = '<td colspan="7" class="no-data">Nenhum funcionário cadastrado</td>';
                        tabelaBody.appendChild(tr);
                        return;
                    }
                    
                    data.data.forEach((funcionario, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${funcionario.nome}</td>
                            <td>${funcionario.cpf}</td>
                            <td>${funcionario.email}</td>
                            <td>${funcionario.cargo}</td>
                            <td>${funcionario.departamento}</td>
                            <td>R$ ${parseFloat(funcionario.salario).toFixed(2)}</td>
                            <td class="actions">
                                <button class="btn btn-primary" onclick="editarFuncionario(${funcionario.id})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger" onclick="excluirFuncionario(${funcionario.id})">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </td>
                        `;
                        tabelaBody.appendChild(row);
                        
                        // Adicionar animação de entrada
                        setTimeout(() => {
                            row.style.opacity = '1';
                            row.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar funcionários:', error);
                    tabelaBody.innerHTML = '<tr><td colspan="7" class="no-data">Erro ao carregar dados</td></tr>';
                });
        }

        function logout() {
            // Fazer logout via PHP
            fetch('logout.php')
                .then(() => {
                    window.location.href = 'index.php';
                });
        }

        // Formatar CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });

        // Formatar telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2')
                               .replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2')
                               .replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value;
            }
        });
    </script>
</body>

</html>
