# IdeaVault — Cofre de Ideias Criativas

Projeto acadêmico de uma aplicação web tradicional em **PHP puro (MVC)**, para cadastro e organização de ideias criativas (projetos, aplicativos, invenções, negócios, músicas etc.).

## Tecnologias

- PHP 8.3+
- MySQL (PDO)
- HTML5 / CSS3 / Bootstrap 5
- Sessões nativas do PHP
- Composer (apenas para metadados do projeto — sem dependências externas)

## Estrutura do projeto

```
ideavault/
├── Config/
│   └── configuration.php   # Constantes de configuração (banco e nome do app)
├── Controller/
│   ├── AutenticacaoController.php  # Cadastro, login, logout, proteção de rotas
│   └── IdeiaController.php  # CRUD de ideias e painel
├── Model/
│   ├── Conexao.php      # Conexão PDO (única responsabilidade)
│   ├── Usuario.php         # Dados do usuário (com hash de senha)
│   └── Ideia.php           # Dados da ideia
├── View/                   # Telas (entrar, cadastro, painel, ideias...)
├── templates/               # header, footer, navbar e assets (css/js)
├── database/
│   └── schema.sql           # Script de criação do banco
├── index.php                 # Front controller (único ponto de entrada)
├── composer.json
└── README.md
```

## Arquitetura (MVC)

- **Model** (`Conexao`, `Usuario`, `Ideia`): representa os dados e conversa com o banco via PDO com *prepared statements*. Não conhece HTML nem sessão.
- **Controller** (`AutenticacaoController`, `IdeiaController`): recebe a requisição (`$_GET`/`$_POST`), chama o Model e decide o fluxo (redirecionar ou renderizar uma View). Não contém HTML nem SQL.
- **View** (`View/*.php`): apenas apresentação. Recebe variáveis já prontas dos Controllers.
- **index.php**: front controller único, faz o roteamento por `?action=`, sem lógica de negócio.

### Fluxo de requisição HTTP tradicional

- `GET` → exibir páginas e listar dados (ex: `index.php?action=ideas`)
- `POST` → enviar formulários (login, cadastro, criar/editar/excluir ideia)
- Após cada operação de escrita, o Controller faz um `header('Location: ...')` (redirect) e define uma mensagem de sucesso/erro na sessão (padrão *Post/Redirect/Get*).

## Segurança

- `password_hash()` / `password_verify()` para senhas (a senha é salva como hash)

## Como configurar

1. Suba um MySQL local e crie o banco executando o script:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   A conexão usa os valores de `Config/configuration.php` (MySQL local, usuário `root` sem senha).
2. Suba um servidor PHP embutido a partir da raiz do projeto:
   ```bash
   php -S localhost:8000
   ```
3. Acesse `http://localhost:8000`.

## Usuário de teste

- **E-mail:** `teste@ideavault.com`
- **Senha:** `123456`

## Checklist dos requisitos atendidos

- [x] Cadastro de usuário simples
- [x] Login/logout com sessão e senha protegida (hash)
- [x] CRUD completo de ideias
- [x] Filtro por categoria, prioridade e status
- [x] Visualização de ideia aleatória
- [x] Dashboard com contagem por status
- [x] Apenas tabelas `users` e `ideas`, com relação 1:N e chave estrangeira
- [x] Classe `Conexao.php` com responsabilidade única (PDO)
- [x] Controle de acesso: cada usuário só vê/edita/exclui suas próprias ideias
- [x] Separação MVC sem HTML em Controllers e sem SQL em Views
- [x] Sem API REST, sem JSON de API, sem JWT
- [x] Interface responsiva com Bootstrap

## Banco De Dados
-- IdeaVault — Script de criação do banco de dados

CREATE DATABASE IF NOT EXISTS ideavault
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ideavault;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ideas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta') NOT NULL DEFAULT 'media',
    status ENUM('rascunho', 'em_desenvolvimento', 'concluida') NOT NULL DEFAULT 'rascunho',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ideas_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    INDEX idx_ideas_user (user_id),
    INDEX idx_ideas_status (status),
    INDEX idx_ideas_categoria (categoria)
) ENGINE=InnoDB;

-- Usuário de teste (senha: 123456)
-- Hash gerado com password_hash('123456', PASSWORD_DEFAULT)
INSERT INTO users (nome, email, senha) VALUES
('Usuário Teste', 'teste@ideavault.com', '$2y$10$9iaGjRvDz8dEk6uXLacmeegGSJn221TMeFEKoQHHuWB3IrrVOL8JO');
