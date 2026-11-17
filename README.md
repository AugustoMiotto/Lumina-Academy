# Lumina Academy (ODS 4)

Plataforma de intercâmbio educacional aberta, leve e segura, alinhada ao **Objetivo de Desenvolvimento Sustentável 4 (ODS 4 — Educação de Qualidade)**. O projeto foi arquitetado com foco **Low-Bandwidth** (otimizado para conexões lentas e hardwares limitados), utilizando renderização no servidor (SSR) sem dependência de JavaScript bloqueante.

---

## Stack Tecnológico

- **Back-end:** Laravel 11.x (`laravel/framework ^11.0`) e PHP 8.2+ com tipagem estrita (`declare(strict_types=1);`)
- **Front-end:** Blade Templates puros (SSR — Server Side Rendering)
- **Estilização:** HTML5 semântico e CSS inline minimalista com Flexbox e alto contraste (sem frameworks JS pesados)
- **Banco de Dados:** SQLite (configuração prática atual de desenvolvimento) / compatível com MySQL 8.x (especificação de produção do MVP)
- **Arquitetura:** MVC tradicional com `FormRequests` e Middleware de controle de acesso (`RoleMiddleware`)
- **Testes:** PHPUnit (`php artisan test`) com banco SQLite em memória

---

## O que já está implementado no MVP

### 1. Autenticação e Controle de Acesso (RBAC)
- Cadastro e login tradicionais via sessão (`AuthController`), com perfis `admin`, `mentor` e `aprendiz` (**RF01**).
- Proteção de rotas por perfil via [RoleMiddleware.php](file:///c:/Users/augus/Desktop/Lumina/app/Http/Middleware/RoleMiddleware.php).
- Fluxo de envio e validação institucional de credenciais para mentores (`verified_credentials`) (**RF02**).

### 2. Gestão de Cursos, Mentorias e Inscrições
- Publicação de ofertas de cursos/mentorias por mentores verificados (`CourseController` + `StoreCourseRequest`), incluindo campo para link HTTPS de sessão síncrona Meet/Zoom (**RF03**, **RF06**).
- Catálogo com busca e filtros no servidor (palavra-chave, categoria, idioma e carga horária máxima), paginação (`paginate(15)`) e Eager Loading para evitar queries N+1 (**RF04**).
- Inscrição em cursos (`EnrollmentController`) protegida por transação e `lockForUpdate()`, impedindo auto-inscrição do mentor, duplicidade de matrícula e ultrapassagem do limite de vagas (`max_students`) (**RF05**).

### 3. Repositório de Recursos Educacionais Abertos (OER)
- Mural público de consulta e busca de materiais abertos (`OerMaterialController`) na rota inicial `/` e `/oer` (**RF09**).
- Publicação de novos materiais OER por mentores/admins vinculados ou não a um curso (`StoreOerMaterialRequest`) (**RF08**).

### 4. Engajamento, Coautoria e Certificação
- Avaliações (nota 1 a 5, comentário e utilidade profissional) restritas a alunos com matrícula ativa ou concluída (`EvaluationController` + `StoreEvaluationRequest`) (**RF10**).
- Registro de reconhecimento mútuo de coautoria de projetos entre mentor e aprendiz (`CoauthorshipController` + `StoreCoauthorshipRequest`) (**RF13**).
- Emissão de certificados e badges com hash SHA-256 único para alunos com matrícula concluída, com rota pública de verificação (`/certificates/verify/{hashCode}`) (**RF11**).

---

## Estrutura Principal do Projeto

- **Rotas:** [routes/web.php](file:///c:/Users/augus/Desktop/Lumina/routes/web.php)
- **Models:** [app/Models/](file:///c:/Users/augus/Desktop/Lumina/app/Models) (`User`, `Course`, `Enrollment`, `OerMaterial`, `Evaluation`, `Certificate`, `Coauthorship`)
- **Controllers:** [app/Http/Controllers/](file:///c:/Users/augus/Desktop/Lumina/app/Http/Controllers)
- **FormRequests:** [app/Http/Requests/](file:///c:/Users/augus/Desktop/Lumina/app/Http/Requests)
- **Migrations & Seeder:** [database/migrations/](file:///c:/Users/augus/Desktop/Lumina/database/migrations) e [DatabaseSeeder.php](file:///c:/Users/augus/Desktop/Lumina/database/seeders/DatabaseSeeder.php)
- **Views Blade:** [resources/views/](file:///c:/Users/augus/Desktop/Lumina/resources/views) (layout base em [layouts/app.blade.php](file:///c:/Users/augus/Desktop/Lumina/resources/views/layouts/app.blade.php))
- **Testes de Feature:** [LuminaMvpTest.php](file:///c:/Users/augus/Desktop/Lumina/tests/Feature/LuminaMvpTest.php)

---

## Como rodar localmente
Consulte o guia rápido passo a passo em [README OR DIE.md](file:///c:/Users/augus/Desktop/Lumina/README%20OR%20DIE.md).
