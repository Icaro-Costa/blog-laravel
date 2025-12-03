# Blog Laravel - Desafio Técnico

## Descrição

Aplicação web desenvolvida em Laravel que consome a API DummyJSON para persistir, listar e permitir interações (Likes, Comentários) em posts de usuários, com foco em arquitetura limpa e boas práticas.

## Tecnologias Utilizadas

- Laravel 10.x+
- MySQL (Docker)
- PHP 8.2+
- Tailwind CSS (para interface)

## Arquitetura e Organização do Código (Diferencial)

A arquitetura do projeto foi estruturada para máxima manutenibilidade e aderência aos padrões de mercado:

* **Service Layer (ReactionService):** Lógica de negócio complexa (manipulação de Sessão e contadores de Likes/Dislikes) isolada do Controller.
* **Single Responsibility Principle (SRP):** Separação de classes (`PostController`, `CommentController`, `UserController`).
* **Query Scopes:** Lógica de filtragem avançada (Busca por título, Tag, Ordenação por Likes/Views) movida para o Model (`Post.php`).
* **Autorização:** Implementação de um **Gate** no `AppServiceProvider` para gerenciar permissões de edição/exclusão de comentários, substituindo a lógica hardcoded.
* **Componentização:** Uso do **PostCard Component** para eliminar a duplicação de HTML nas listagens.

## Instalação

### Pré-requisitos

- **Docker** e **Docker Compose V2** (Para ambiente de banco de dados e servidor PHP)
- **WSL2** ou ambiente Linux (Recomendado para melhor desempenho)
- **PHP 8.2+** (Versão da imagem Docker)
- **Node.js** e **npm** (Para gerenciar assets via Vite)

### Passos

1.  **Clone o repositório:**
    ```bash
    git clone [SEU_LINK_DO_REPOSITORIO]
    cd [NOME_DO_PROJETO]
    ```

2.  **Configuração Inicial:**
    Crie o arquivo de variáveis de ambiente com base no exemplo:
    ```bash
    cp .env.example .env
    ```

3.  **Subir os Containers (Infraestrutura):**
    Este comando inicia o servidor PHP e o banco de dados MySQL no Docker:
    ```bash
    docker compose up -d
    ```

4.  **Instalar Dependências PHP e JS:**
    Execute a instalação de pacotes PHP (Composer) e Node.js (npm) dentro do container:
    ```bash
    # Instalar dependências Composer (executado dentro do container PHP)
    docker compose exec laravel.test composer install

    # Instalar dependências NPM (executado no host/WSL)
    npm install
    ```

5.  **Migrar e Popular o Banco de Dados:**
    Este comando executa as Migrations e o Seeder, que **consome a API DummyJSON** e persiste os dados:
    ```bash
    # Execute DENTRO do container PHP para usar a rede Docker interna
    docker compose exec laravel.test php artisan migrate:fresh --seed
    ```

6.  **Compilar e Ligar Assets (Frontend):**
    Para uso em produção, compile os assets; para desenvolvimento, use o modo 'dev':
    ```bash
    # Para Produção (Cria a pasta public/build)
    npm run build
    
    # Para Desenvolvimento (Manter rodando em segundo plano em outro terminal)
    # npm run dev
    ```

7.  **Acesso à Aplicação:**
    A aplicação estará acessível no seu navegador:
    ```
    http://localhost:8000
    ```

---

**Ação Final:** Substitua os passos no seu `README.md` por esta versão detalhada, preencha os colchetes com os seus dados (`[Nome do Projeto]`, etc.), e garanta que o **link da apresentação** seja incluído.

## Funcionalidades Implementadas

### ✅ Checklist Final

- [x] Código commitado no repositório Git
- [ ] **README.md completo e bem estruturado** (Pendente de Inclusão do Link)
- [x] Arquivo .gitignore adequado
- [ ] **Link da apresentação incluído no README**
- [x] Funcionalidades principais implementadas
- [x] Aplicação testada e funcional (Migrações e Seeding OK)

### 📊 Funcionalidades Diferenciais Entregues

- [x] **Filtros Avançados (Query Scopes)** (Implementados por Título, Tag, Likes e Views)
- [x] **CRUD de Comentários**
- [x] **Soft Delete em Comentários** (Implementado via Migração)
```mermaid
erDiagram
    %% Tabela de Utilizadores
    USERS {
        bigint id PK
        string firstName
        string lastName
        string email UK "Unique"
        string phone
        string username UK "Unique"
        string password
        string image "Nullable"
        date birthDate "Nullable"
        string address_address "Nullable"
        string address_city "Nullable"
        string address_state "Nullable"
        string address_postalCode "Nullable"
        timestamp created_at
        timestamp updated_at
    }

    %% Tabela de Posts
    POSTS {
        bigint id PK
        bigint userId FK
        string title
        text body
        json tags "Nullable"
        integer reactions_likes "Default 0"
        integer reactions_dislikes "Default 0"
        integer views "Default 0"
        timestamp created_at
        timestamp updated_at
    }

    %% Tabela de Comentários
    COMMENTS {
        bigint id PK
        bigint postId FK
        bigint userId FK
        text body
        integer likes "Default 0"
        integer dislikes "Default 0"
        timestamp deleted_at "SoftDelete Nullable"
        timestamp created_at
        timestamp updated_at
    }

    %% Relações
    %% Um utilizador escreve muitos posts (1:N)
    USERS ||--o{ POSTS : "escreve (author)"
    
    %% Um post tem muitos comentários (1:N)
    POSTS ||--o{ COMMENTS : "tem"
    
    %% Um utilizador escreve muitos comentários (1:N)
    USERS ||--o{ COMMENTS : "comenta"
```
