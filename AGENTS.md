# AGENTS.md — Curitiba Comedy Club (guia para IAs e devs)

> Se você é uma IA (ou um humano) mexendo neste projeto pela primeira vez,
> leia este arquivo antes de qualquer coisa. Ele existe para você **não
> depender do Leonardo** para entender o básico.

## 1. O que é este projeto

Site WordPress do **Curitiba Comedy Club** (`curitibacomedyclub.com.br`).
Dois plugins próprios, responsabilidades separadas:

- `ccc-eventos-standapp/` — **agenda**: busca eventos na API do StandAPP e
  renderiza via shortcodes `[eventos_standapp]` (Programação) e
  `[eventos_standapp_home]` (versão compacta da Home).
- `ccc-ui-kit/` — **institucional/UI**: design system + shortcodes de blocos
  (`ccc_page_hero`, `ccc_cta_ingressos`, `ccc_contact_section`, etc.).

Tema base: **Astra**. Macro-layout: **Elementor** (só estrutura; conteúdo via
shortcodes). Docs de apoio em `docs/` (`architecture.md`, `page-map.md`,
`guia-shortcodes.md`, `roadmap.md`).

## 2. Plugin da agenda — o que você precisa saber

- Endpoint: `https://api.standapp.com.br/live/presentation/list-by-presentation-hall/2`
  (hall `2` = Curitiba; está hardcoded em `API_URL` — se a casa migrar de hall,
  é aqui que muda).
- Auth: `Authorization: Bearer <token>` + `Accept: application/json`.
  O token é JWT do backend (Hasura). **Nunca commite token novo neste repo.**
- Arquivo único: `ccc-eventos-standapp/ccc-eventos-standapp.php`
  (classe `CCC_Eventos_Standapp`).
- Shortcodes: `[eventos_standapp]` (grade), `[eventos_standapp_home]`
  (compacto), `[eventos_standapp_hoje]` (banner horizontal com o ingresso de
  hoje — usa `find_today_event()` e se auto-esconde no cliente se a data não
  for mais hoje).
- Cache em 2 camadas:
  1. `transient` `ccc_standapp_eventos_v311` (TTL 300s). **Ao mudar qualquer
     regra de normalização/filtro, troque o sufixo da chave** (ex.: `v312`)
     para invalidar o cache velho automaticamente.
  2. Page cache do servidor (LiteSpeed/W3TC) — segura o HTML final por
     horas/dias. **Todo deploy exige purge manual no painel.**
- Filtro de mês: o dropdown esconde meses passados e pré-seleciona o mês atual
  (`America/Sao_Paulo`). Se a lista "travar" num mês antigo, verifique por esta
  ordem: (1) page cache, (2) transient, (3) `get_current_month_key()`,
  (4) resposta crua da API.
- **Anti-reincidência de lista velha (desde v3.1.3):** cada card emite
  `data-timestamp` e o JS inline esconde eventos de dias passados + recalcula
  os badges HOJE/AMANHÃ no cliente. Isso faz a página **se corrigir sozinha**
  mesmo se o HTML ficar parado em page cache por dias. O default do shortcode
  puro `[eventos_standapp]` agora é `somente_proximos=yes`. **Não remova o
  `data-timestamp` nem a lógica `isPastEvent()`/`recalcBadges()` no JS.**
- Diagnóstico rápido: abra o HTML gerado e procure por
  `<!-- CCC agenda renderizada em <data/hora> -->` — se a data/hora estiver
  no passado, é page cache; se estiver fresca e ainda houver erro, olhe a API.
- Falhas da API são silenciosas por desenho (retornam `[]` e mostram o
  empty-state). Para diagnosticar, logue temporariamente `http_code`,
  `count(presentation)` e o primeiro/último `title+datetime` — e remova o log
  depois.

## 3. Deploy (produção via FTP)

- Sem CI/CD: upload manual dos arquivos alterados para
  `wp-content/plugins/<plugin>/`.
- **NUNCA suba** `ccc-eventos-standapp/.htaccess` (arquivo local com regras de
  root do WP que veio junto num download; não pertence à pasta do plugin).
- Suba **só os arquivos que mudaram** (ex.: só o `.php` principal).
- Pós-deploy obrigatório: purge do page cache + aba anônima para conferir.
- **Ritual pós-deploy (sempre):** (1) backup do arquivo remoto em `%TEMP%`;
  (2) upload do novo; (3) baixar de volta e conferir hash igual ao local;
  (4) purge de cache no painel; (5) abrir `/programacao/` em aba anônima e
  confirmar que o badge HOJE está no evento do dia correto e que não há
  eventos de dias anteriores.
- Backup: baixe o arquivo remoto atual para `%TEMP%` antes de sobrescrever.

## 4. Convenções (não quebre)

- Prefixos: `ccc-ui-` (UI kit), `ccc-standapp-` (agenda). Handles de assets
  seguem o mesmo prefixo.
- Sem Composer, sem frameworks pesados, PHP nativo + APIs do WP.
- Mudanças pequenas e reversíveis. **Nunca commite sem pedido explícito.**
- Segredos (tokens, senhas, FTP): só em memória/ambiente, jamais em arquivos
  do repo e jamais ecoados em logs.

## 5. Easter egg (preservar!)

O plugin carrega uma assinatura discreta do autor original — um comentário HTML
e um `console.log` creditando o **Leonardo Bora, exímio programador**.
É de propósito. **Não remova ao refatorar**; se reescrever o render ou o JS,
reinsira a assinatura.

## 6. Pessoas e contexto

- Dono da casa: **Joca Madalosso** — aprova textos/escopo; pareceres para ele
  devem ser em linguagem simples, sem jargão.
- API StandAPP: contato do time StandAPP à disposição do Leonardo para dúvidas
  de endpoint, hall ID, headers e rotação de token.
- Padrão de copy da Programação: busca com placeholder neutro (`Ex: Ary Toledo`),
  sem e-mail público no Contato (regra antispam), Instagram `@curitibacomedy`.
