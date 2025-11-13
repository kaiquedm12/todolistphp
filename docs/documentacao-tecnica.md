# Documentação Técnica – Sistema de Tarefas

## 1. Arquitetura Geral
O projeto segue uma estrutura MVC simplificada:
```
/config → Conexão com o banco
/controllers → Lógica de usuário e tarefas
/models → Classes que manipulam o banco
/views → Telas HTML/PHP
```
Requisições assíncronas (AJAX) fazem a comunicação entre `views` → `controllers`.

---

## 2. Modelagem do Banco de Dados

### **Tabela: users**
| Campo       | Tipo        | Descrição                       |
|-------------|-------------|---------------------------------|
| id          | INT PK AI   | Identificador do usuário        |
| name        | VARCHAR     | Nome do usuário                |
| email       | VARCHAR     | E-mail (único)                 |
| password    | VARCHAR     | Senha criptografada (HASH)     |
| created_at  | TIMESTAMP   | Data de criação                |

---

### **Tabela: tasks**
| Campo       | Tipo        | Descrição                       |
|-------------|-------------|---------------------------------|
| id          | INT PK AI   | ID da tarefa                    |
| user_id     | INT FK      | Liga tarefa ao usuário          |
| title       | VARCHAR     | Título                          |
| description | TEXT        | Descrição                       |
| status      | ENUM        | pendente / concluida            |
| due_date    | DATE        | Prazo                           |
| created_at  | TIMESTAMP   | Criada em                       |

**Relacionamento:**  
`users (1) ---- (N) tasks`

---

## 3. Arquivos Principais

### ✔ `config/db_connect.php`
Responsável pela conexão com o MySQL.

### ✔ `models/Task.php`
Contém funções:
- `create()`
- `update()`
- `delete()`
- `toggleStatus()`
- `getAllByUser()`

---

### ✔ `models/User.php`
Funções:
- `register()`
- `login()`
- `logout()`

---

### ✔ `controllers/taskController.php`
Recebe requisições AJAX do dashboard:
- Cria / Atualiza / Exclui / Lista tarefas
- Verifica sessão antes de executar qualquer ação

---

### ✔ `controllers/userController.php`
Gerencia:
- login
- cadastro
- logout
- validações

---

### ✔ `views/tasks/dashboard.php`
Traz:
- Formulário de criação
- Lista de tarefas via AJAX
- EventListeners com jQuery

---

## 4. Funcionamento das Requisições AJAX

### Exemplo — Criar tarefa
```js
$.post("../../controllers/taskController.php", {
    action: "create",
    title: "...",
    description: "...",
    deadline: "..."
});
``` 
### Exemplo — excluir
```js
$.post("../../controllers/taskController.php", { action: "delete", id });
```
### Exemplo — listar
```js
$("#taskList").load("list_tasks.php");
```
## 5. Decisões Técnicas Utilizadas
✔ MVC simplificado
Evita mistura de lógica e visual.

✔ Sessão via $_SESSION
Garante que cada usuário veja apenas suas tarefas.

✔ AJAX com jQuery
Atualiza a tela sem recarregar página inteira.

✔ Bootstrap
Padroniza layout e aparência.

✔ Prepared Statements (PDO)
Evita SQL Injection.

## 6. Configuração Local

### 1. Clonar ou copiar o projeto
Coloque em:

```bash
xampp/htdocs/todo-system
```
### 2. Banco
Criar banco:

```nginx
todo_system
Executar script SQL das tabelas.
```

### 3. Configurar /config/db_connect.php
Exemplo:

```php
$host = 'localhost';
$db   = 'todo_system';
$user = 'root';
$pass = '';
```
### 4. Executar no navegador:
```perl
http://localhost/todo-system/views/auth/login.php
```

## 7. Considerações Finais
A estrutura é planejada para fácil manutenção e evolução.
Próximas melhorias podem incluir:

- Modal de edição

- Filtros avançados

- Ordenação por data

- API completa em JSON







