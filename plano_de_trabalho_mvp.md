# Plano de Trabalho - MVP (EduBridge / ODS 4)

## 1. Visão Geral
Este documento estabelece o plano de desenvolvimento do Produto Mínimo Viável (MVP) da plataforma de intercâmbio educacional com o nome de **Lumina Academy** (ODS 4). O foco é entregar uma aplicação funcional, extremamente leve (otimizada para conexões lentas e hardwares limitados) e altamente segura.

## 2. Stack Tecnológico
*   **Back-end:** Laravel 11.x (PHP 8.2+)
*   **Front-end:** Blade Templates (SSR - Server Side Rendering)
*   **Estilização:** CSS Customizado ou Tailwind CSS otimizado/purificado (sem dependências pesadas de JS). Visual "Clean".
*   **Banco de Dados:** MySQL 8.x
*   **Arquitetura:** MVC (Model-View-Controller) tradicional.

## 3. Diretrizes de Engenharia e Performance (Foco "Low-Bandwidth")
*   **Zero JS Bloqueante:** O JavaScript no front-end deve ser mínimo. Formulários usarão envios tradicionais POST do HTML, processados e redirecionados pelo Laravel.
*   **Otimização de Consultas (N+1):** Uso estrito de Eager Loading no Eloquent para evitar sobrecarga no banco de dados.
*   **Paginação Servidor:** Todas as listagens (Cursos, OER) devem usar a paginação nativa do Laravel.
*   **Segurança (OWASP Top 10):** Validação de dados usando `FormRequests`, proteção ativa contra CSRF, XSS (escape nativo do Blade) e SQL Injection (Eloquent ORM). Controle de acesso via `Gates` e `Policies`.

## 4. Escopo da Sprint 1 - MVP (Matriz MoSCoW: MUST HAVE)

Com base no board fornecido, apenas os requisitos amarelos serão desenvolvidos:

*   **Autenticação e Perfis**
    *   `RF01 - Cadastrar e autenticar usuário (MVP)`
    *   `RF02 - Validar credenciais de mentor (Instituição)`
*   **Gestão de Cursos e Mentorias**
    *   `RF03 - Publicar oferta de mentoria/curso`
    *   `RF04 - Buscar ofertas com filtros`
    *   `RF05 - Inscrever-se em curso`
    *   `RF06 - Integrar sessão virtual` (No MVP: campo para link do Meet/Zoom)
    *   `RF13 - Registrar coautoria` (Reconhecimento mútuo de projeto)
*   **Recursos Educacionais Abertos (OER)**
    *   `RF08 - Publicar recurso educacional (OER)`
    *   `RF09 - Consultar repositório (OER)`
*   **Engajamento e Conclusão**
    *   `RF10 - Avaliar e comentar`
    *   `RF11 - Emitir certificado e badge`

## 5. Cronograma Macro (Fases de Desenvolvimento)
*   **Fase 1: Infraestrutura e Database.** Criação de Migrations, Models, Relacionamentos e Seeders iniciais.
*   **Fase 2: Auth e RBAC.** Sistema de login, registro e diferenciação entre `Aprendiz`, `Mentor` e `Admin`.
*   **Fase 3: Core Educacional.** CRUD de Cursos, Filtros de Busca, Sistema de Inscrição.
*   **Fase 4: Repositório OER e Aulas.** Upload/Links de materiais e tela da sessão com link da aula/coautoria.
*   **Fase 5: Avaliação e Certificação.** Sistema de estrelas/comentários e geração de view de certificado.
*   **Fase 6: UI/UX Leve.** Refinamento das views em Blade garantindo acessibilidade e contraste.