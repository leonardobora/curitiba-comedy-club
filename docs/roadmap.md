# Curitiba Comedy Club - Roadmap (v2)

## Objetivo do roadmap

Planejar um site completo, consistente e escalavel para o Curitiba Comedy Club, com identidade Netflix + casa de espetaculo, separando com clareza a camada de eventos da camada institucional e mantendo um pipeline de imagens organizado.

## Status do documento

- Este arquivo representa planejamento.
- Nao substitui checklist tecnico de deploy.
- Escopo atual: manter fase 1 em andamento e alinhar proxima fila com os pedidos mais recentes.

## Solicitacoes consolidadas (Joca - 25/03/2026)

1. Direcao de fundo mais escuro, com preferencia inicial por vermelho bem escuro (alternativa: preto).
2. Cabecalho com tipografia mais pesada e avaliacao de opcao serifada alinhada a marca.
3. Programacao com placeholder de busca neutro: ex: Ary Toledo.
4. Programacao com texto explicativo abaixo do titulo: agenda publicada com antecedencia aproximada de 1 mes, abertura de terca a sabado, com ressalva para eventos fechados.
5. Contato sem e-mail publico para reduzir spam.
6. Instagram oficial exibido como @curitibacomedy.
7. Telefone rotulado como WhatsApp com regra: somente mensagens, sem ligacoes.
8. Planejar areas/abas para Open Mic, Eventos Fechados e Quadros da casa.
9. Recuperar referencias da pagina antiga, incluindo FAQ.

## Escopo de paginas

## Core imediato (fase 1)

1. Home
2. Sobre
3. Programacao
4. Cardapio
5. Contato

## Expansao planejada (fase 2+)

1. Ingressos (landing dedicada para conversao)
2. FAQ
3. Midia/Galeria
4. Eventos Corporativos (B2B) - pagina dedicada ja criada
5. Open Mic
6. Eventos Fechados
7. Quadros da casa
8. Politicas (privacidade, cookies, termos)

## Arquitetura de pagina (macro)

## Home

Objetivo: impacto e conversao rapida.

Blocos:

- Hero cinematografico (headline forte + CTA)
- Programacao em destaque ([eventos_standapp])
- CTA ingressos
- Teaser de ambiente (fotos/drone)
- Prova social (depoimentos/imprensa)
- CTA final

## Sobre

Objetivo: posicionamento e narrativa da marca.

Blocos:

- Hero institucional
- Historia da casa
- Diferenciais (ambiente, curadoria, experiencia)
- Galeria curta
- CTA programacao

## Programacao

Objetivo: listar, explicar e converter.

Blocos:

- Hero curto
- Texto institucional curto com regra de agenda (1 mes, terca a sabado, ressalva de eventos fechados)
- Campo de busca com placeholder: ex: Ary Toledo
- Grade de eventos ([eventos_standapp])
- CTA compra

## Cardapio

Objetivo: apoiar ticket medio e experiencia.

Blocos:

- Hero curto
- Categorias (drinks, comidas, combos)
- Cards de itens com foto + preco
- Observacoes de servico

## Contato

Objetivo: facilitar acesso e descoberta.

Blocos:

- Hero curto com CTA Linktree
- Secao de contato minimalista com icones
- Mapa embutido Google Maps
- Acoes rapidas: WhatsApp (mensagens), Instagram (@curitibacomedy), mapa

Observacao:

- Nao expor e-mail publico enquanto regra antispam estiver ativa.

## Estrutura de imagens recomendada

Criar uma biblioteca de midia organizada por contexto de uso e formato.

## Fontes aprovadas

- [Drone](https://drive.google.com/drive/folders/1PLeT49uZEWQC23ZjTZF_X7HmEXaRkI52)
- [Cardapio](https://drive.google.com/drive/folders/1SFZhfguelFpS70Fi_5MaGDeetFzx6pxa)
- [Fotos CCC](https://drive.google.com/drive/folders/1kvrzGEiBtnhoW5v54IJlI8WgmzmM0fwg)

## Taxonomia de assets (proposta)

1. hero/
2. shows/
3. venue/
4. menu/
5. people/
6. social/

## Variacoes por formato

1. Desktop hero: 1920x1080 (16:9)
2. Card horizontal: 1280x720 (16:9)
3. Card vertical: 1080x1350 (4:5)
4. Stories/reels: 1080x1920 (9:16)
5. Thumb quadrada: 1080x1080 (1:1)

## Padrao tecnico para web

- Formato principal: WebP
- Qualidade base: 70-82
- JPG fallback quando necessario
- Nome de arquivo sem espacos e com slug
- Alt text descritivo com contexto

Exemplos de naming:

- ccc-home-hero-noite-comedia-v1.webp
- ccc-cardapio-drink-negroni-v2.webp
- ccc-venue-plateia-lotada-v1.webp

## Diretriz visual (tema premium)

- Fundo predominante escuro com preferencia por vermelho muito escuro no contexto atual
- Superficies com contraste suave
- Vermelho como acento de conversao
- Tipografia forte em titulos; testar alternativa serifada no cabecalho
- Fotos com mood noturno/cinematografico
- Evitar excesso de elementos por secao

## Fases de implementacao

## Fase 1 (atual)

- Base do ccc-ui-kit
- Shortcodes institucionais iniciais
- Padrao visual principal
- Ajustes de copy e contato conforme solicitacoes consolidadas

## Fase 2

- Paginas Home/Sobre/Contato refinadas com curadoria visual
- Programacao com texto explicativo e placeholder revisado
- Galeria inicial para Home e Sobre

## Fase 3

- Cardapio estruturado com componentes
- Midia/Galeria ampliada
- FAQ com base no historico da pagina antiga
- Planejamento de Open Mic, Eventos Fechados e Quadros
- Pagina Quadros da Casa (carrossel de fotos dos quadros de famosos com artefatos e recordacoes)

## Fase 4

- Otimizacao de performance de imagem
- Testes A/B de hero e CTA
- Hardening de SEO tecnico

## Backlog curto (proxima sprint)

1. Consolidar copy da Programacao com texto de agenda e placeholder ex: Ary Toledo.
2. Ajustar Contato para exibir WhatsApp (somente mensagens) e @curitibacomedy sem e-mail publico.
3. Definir direcao de cabecalho (peso tipografico e teste serifado).
4. Criar inventario de referencias da pagina antiga para FAQ e Quadros.
5. Definir curadoria inicial de imagens por pagina e por formato.
6. Criar pagina Quadros da Casa com carrossel de fotos dos quadros de famosos (artefatos, lembrancas e recordacoes historicas do clube).
7. Integrar Google Tag Manager (GTM-T67592D9) e validar rastreamento com equipe de marketing.

## Fechamento do dia (25/03/2026)

Status: entregue em codigo e documentacao; pendente apenas validacao final em ambiente publicado.

Entregas concluidas nesta rodada:

1. Programacao com melhor contraste de titulo/labels em fundo escuro.
2. Placeholder de busca validado para linguagem neutra (ex: Ary Toledo).
3. Contato sem e-mail publico e com grade reequilibrada apos remocao do card.
4. Cardapio sem textos de preenchimento e com copy de producao.
5. Open Mic documentado como pagina dedicada no mapa de paginas.
6. Ajustes finais de Home/carrossel e hardening responsivo mantidos no pacote de entrega.

Checklist de publicacao para encerrar a sprint:

1. Limpar cache do WordPress/plugin/CDN apos upload final.
2. Validar em producao: Home, Programacao, Cardapio, Contato e Open Mic.
3. Confirmar menu mobile/off-canvas em viewport real de celular.
4. Congelar baseline com commit final no repositorio.

## Fechamento do dia (02/04/2026)

Status: refatoracao de shortcodes e preparacao de rastreamento concluidas.

Entregas concluidas nesta rodada:

1. Auditoria completa de textos hardcoded em todos os 14 shortcodes do ccc-ui-kit.
2. Refatoracao de 5 componentes (contact-section, menu-section, fullwidth-carousel, home-photo-wall, timeline) para expor textos fixos como atributos editaveis via shortcode.
3. Correcao ortografica de 11 erros de acentuacao/cedilha em timeline e accordion.
4. Instalacao do Google Tag Manager (GTM-T67592D9) via WPCode — script no head e noscript no body.
5. Documentacao de guia de shortcodes para administrador do site (docs/guia-shortcodes.md).
6. Roadmap atualizado com pagina Quadros da Casa e integracao GTM no backlog.

Proximos passos:

1. Validar GTM com equipe de marketing (Joao Madalosso) e desativar snippets duplicados (Meta Pixel, Google Ads) quando migrados para o GTM.
2. Criar pagina Quadros da Casa com carrossel de fotos dos quadros de famosos.
3. Subir plugins atualizados via FTP para ambiente de producao.
