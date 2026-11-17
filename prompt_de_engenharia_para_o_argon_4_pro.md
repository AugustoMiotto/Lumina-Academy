# Prompt de Inicialização para o Agente Argon 4 Pro

**Contexto:** Você é um Engenheiro de Software Sênior especialista em Laravel, PHP e performance web. Sua missão é construir o código-fonte do MVP de uma plataforma educacional para países em desenvolvimento (ODS 4). O sistema rodará em infraestruturas precárias, portanto, a aplicação deve ser EXTREMAMENTE LEVE, utilizando Blade templates puros, o mínimo possível de JavaScript e CSS otimizado. Segurança (prevenção a N+1, SQLi, XSS, CSRF) é inegociável.

**Stack:** Laravel 11, PHP 8.2, MySQL, Blade.

**Tarefas Iniciais:**
Por favor, atue na estrutura inicial do sistema focado APENAS nos requisitos classificados como "Must Have". Gere os arquivos de estrutura, rotas, models e controllers seguindo as instruções abaixo:

### Passo 1: Modelagem de Dados e Migrations
Crie os arquivos de Migration e Models (com os devidos relacionamentos e propriedades `$fillable`) para as seguintes tabelas lógicas:
1. `users`: id, name, email, password, role (enum: admin, mentor, aprendiz), education_level, bio, verified_credentials (boolean, atendendo ao RF02).
2. `courses`: id, mentor_id, title, description, benefits, workload_hours, language, category, meeting_link (RF06), status.
3. `enrollments`: id, user_id, course_id, status (enrolled, completed). (Atendendo ao RF05).
4. `oer_materials`: id, uploader_id, course_id (nullable), title, description, file_path_or_url, public (boolean). (Atendendo ao RF08 e RF09).
5. `evaluations`: id, course_id, user_id, rating (1-5), comment, professional_utility (boolean). (Atendendo ao RF10).
6. `certificates`: id, user_id, course_id, hash_code, type (badge, certificate). (Atendendo ao RF11).
7. `coauthorships`: id, course_id, mentor_id, coauthor_id, contribution_description. (Atendendo ao RF13).

### Passo 2: Rotas (routes/web.php)
Desenhe o arquivo de rotas web. Agrupe as rotas por middlewares apropriados (`auth`, e middlewares de permissão baseados na coluna `role` do usuário). 
- Crie um grupo para `mentors` (publicar cursos, OER, validar credenciais).
- Crie um grupo para `aprendizes` (buscar cursos, inscrever-se, avaliar, consultar OER).
- Rotas públicas para registro, login e visualização do mural OER.

### Passo 3: Lógica de Controllers e Segurança (Form Requests)
Escreva o esqueleto dos principais controllers, focando nos métodos vitais:
- `CourseController`: Métodos `index` (com lógica de busca/filtros avançados - RF04), `store` (RF03) usando FormRequests para validação estrita.
- `EnrollmentController`: Lógica para garantir que um aluno só se inscreve se houver vaga e não estiver já inscrito (RF05).
- `EvaluationController`: Validação para garantir que apenas alunos com matrícula ativa/concluída possam avaliar (RF10).

### Passo 4: Diretrizes de View (Blade)
Forneça um exemplo de layout principal (`resources/views/layouts/app.blade.php`) usando HTML5 semântico e estruturado de forma minimalista. Crie uma estrutura de header, main e footer. O CSS deve focar em flexbox simples, contraste alto e nenhuma animação pesada. Inclua a diretiva `@csrf` para segurança em futuros forms.

**Restrições Importantes que você deve seguir no código gerado:**
- Use tipagem estrita no PHP (`declare(strict_types=1);`).
- Onde houver listagem, aplique paginação do servidor (`->paginate(15)`).
- Evite N+1 queries usando o método `with()` do Eloquent nos controllers.
- Não implemente chamadas assíncronas (AJAX/Axios) neste primeiro momento. Priorize formulários padrão com redirecionamento e Flash Messages do Laravel (`->with('success', '...')`).

Por favor, comece gerando o código das Migrations e dos Models principais.