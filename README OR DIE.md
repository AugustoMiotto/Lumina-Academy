# README OR DIE — Guia Rápido de Setup (Colaboradores)

Passo a passo direto para clonar o repositório e rodar a **Lumina Academy** localmente usando **SQLite**.

## Pré-requisitos
- **PHP 8.2+** (com extensões `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl` habilitadas)
- **Composer**
- **Git**

---

## Passo a passo

### 1. Clonar o repositório
```bash
git clone https://github.com/AugustoMiotto/Lumina-Academy.git
cd Lumina
```

### 2. Instalar as dependências PHP
```bash
composer install
```

### 3. Configurar o ambiente (`.env`) e chave da aplicação
```bash
cp .env.example .env
php artisan key:generate
```
*(No Windows PowerShell, use `copy .env.example .env` se `cp` não estiver disponível.)*

Verifique no `.env` se a conexão está configurada para SQLite (padrão atual do MVP):
```env
DB_CONNECTION=sqlite
```

### 4. Criar o arquivo do banco SQLite, rodar migrations e popular dados de teste
No Linux/macOS:
```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```
No Windows (PowerShell):
```powershell
New-Item -ItemType File -Force -Path database\database.sqlite
php artisan migrate:fresh --seed
```

### 5. Subir o servidor local
```bash
php artisan serve
```
Acesse no navegador: `http://127.0.0.1:8000`

---

## Contas de Teste (geradas pelo Seeder)
| Perfil | E-mail | Senha |
| :--- | :--- | :--- |
| **Admin** | `admin@lumina.org` | `password123` |
| **Mentor Verificado** | `mentor@lumina.org` | `password123` |
| **Aprendiz** | `aprendiz@lumina.org` | `password123` |

---

## Rodar os testes automatizados
```bash
php artisan test
```
