# Manual Funcional – Sistema de Tarefas

## 1. Visão Geral do Sistema
O sistema de gerenciamento de tarefas permite que cada usuário cadastre, visualize, edite, conclua e exclua as próprias tarefas.  
O sistema utiliza autenticação (login), garantindo que cada pessoa veja apenas suas próprias atividades.

### Principais Telas:
- **Tela de Login**
- **Tela de Cadastro**
- **Dashboard (Tarefas do Usuário)**

---

## 2. Funcionalidades

### ✔ Login
O usuário informa e-mail e senha para acessar o dashboard.

### ✔ Cadastro de Usuário
Ao criar uma conta, pode acessar o sistema imediatamente.

### ✔ Dashboard
Onde o usuário encontra:
- Saudação com o nome
- Formulário para criar novas tarefas
- Lista de tarefas existentes
- Botões para:
  - ✔ Editar
  - ✔ Excluir
  - ✔ Marcar como concluída/pendente

### ✔ Logout
Finaliza a sessão e retorna ao login.

---

## 3. Como Usar o Sistema – Passo a Passo

### **1. Acessar a tela de login**
Abra:  
`/views/auth/login.php`

Insira e-mail e senha → clique em *Entrar*.

---

### **2. Criar nova tarefa**
No dashboard:
1. Preencha **Título**, **Descrição** (opcional) e **Data limite** (opcional)
2. Clique no botão **+**  
A lista atualiza automaticamente.

---

### **3. Alterar status da tarefa**
Clique no botão:
- *Concluir*
- *Marcar como pendente*

---

### **4. Editar tarefa**
Clique no ícone ✏  
Os campos são preenchidos automaticamente.

Edite → clique em **Salvar**.

---

### **5. Excluir tarefa**
Clique no ícone 🗑  
A tarefa é removida imediatamente.

---

### **6. Logout**
Clique em **Sair** para encerrar a sessão.

---

## 4. Instalação e Execução Local

### **Pré-requisitos**
- PHP 8+
- MySQL ou MariaDB
- XAMPP/WAMP/MAMP (recomendado)
- Navegador web

---

### **Passos de instalação**
1. Coloque o projeto dentro da pasta:
xampp/htdocs/todo-system/


2. Importe o arquivo SQL no phpMyAdmin:
- Crie o banco `todo_system`
- Importe o script contendo as tabelas:
  - users
  - tasks

3. Configure o arquivo:
config/db_connect.php


4. Inicie o Apache + MySQL pelo XAMPP.

5. Acesse o sistema:
http://localhost/todo-system/views/auth/login.php


---

## 5. Suporte
Em caso de erro:  
Verifique permissões, conexão com o banco e diretórios corretos.
