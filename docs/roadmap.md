# Curitiba Comedy Club - Roadmap (v1)

## Questao de roadmap

Como montar um site completo, consistente e escalavel para o Curitiba Comedy Club, com identidade Netflix + casa de espetaculo, separando claramente a camada de eventos da camada institucional e usando um pipeline de imagens organizado?

## Escopo do site completo (paginas)

1. Home
2. Sobre
3. Programacao
4. Cardapio
5. Contato
6. Ingressos (landing dedicada para conversao)
7. FAQ
8. Midia/Galeria
9. Eventos Corporativos (B2B)
10. Politicas (privacidade, cookies, termos)

Observacao:

- As paginas 1 a 5 sao o core imediato.
- As paginas 6 a 10 entram como expansao de fase 2.

## Arquitetura de paginas (Netflix + casa de espetaculo)

## Home

Objetivo: impacto + conversao rapida.

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

Objetivo: listar e vender.

Blocos:

- Hero curto
- Grade de eventos ([eventos_standapp])
- Filtros
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

- Hero curto com CTA Linktree (https://linktr.ee/curitibacomedy)
- Secao de contato minimalista com icones
- Mapa embutido Google Maps
- Acoes rapidas (telefone, e-mail, Instagram, mapa)

## Estrutura de imagens recomendada

Criar uma biblioteca de midia organizada por contexto de uso e formato.

## Fontes aprovadas

- Drone: https://drive.google.com/drive/folders/1PLeT49uZEWQC23ZjTZF_X7HmEXaRkI52
- Cardapio: https://drive.google.com/drive/folders/1SFZhfguelFpS70Fi_5MaGDeetFzx6pxa
- Fotos CCC: https://drive.google.com/drive/folders/1kvrzGEiBtnhoW5v54IJlI8WgmzmM0fwg

## Taxonomia de assets (proposta)

1. hero/
2. shows/
3. venue/
4. menu/
5. people/
6. social/

## Variações por formato

1. Desktop hero: 1920x1080 (16:9)
2. Card horizontal: 1280x720 (16:9)
3. Card vertical: 1080x1350 (4:5)
4. Stories/reels: 1080x1920 (9:16)
5. Thumb quadrada: 1080x1080 (1:1)

## Padrão tecnico para web

- Formato principal: WebP
- Qualidade base: 70-82
- JPG fallback quando necessario
- Nome de arquivo sem espacos e com slug
- Alt text descritivo com contexto

Exemplo de naming:

- ccc-home-hero-noite-comedia-v1.webp
- ccc-cardapio-drink-negroni-v2.webp
- ccc-venue-plateia-lotada-v1.webp

## Diretriz visual (tema premium)

- Fundo predominante escuro
- Superficies com contraste suave
- Vermelho como acento de conversao
- Tipografia forte em titulos
- Fotos com mood noturno/cinematografico
- Evitar excesso de elementos por secao

## Fases de implementacao

## Fase 1 (atual)

- Base do ccc-ui-kit
- Shortcodes institucionais iniciais
- Padrao visual principal

## Fase 2

- Shortcodes: about, section heading, galeria
- Paginas Home/Sobre/Contato refinadas
- Integracao de imagens curadas

## Fase 3

- Cardapio estruturado com componentes
- Midia/Galeria
- FAQ e pagina de Ingressos dedicada

## Fase 4

- Otimizacao de performance de imagem
- A/B de hero e CTA
- Hardening de SEO tecnico

## Backlog curto (proxima sprint)

1. Adicionar CTA Linktree no cabeçalho da secao de contato.
2. Criar componente de galeria de fotos para Home e Sobre.
3. Definir primeira curadoria de imagens por pagina.
4. Especificar diretrizes de crop por breakpoint (desktop/tablet/mobile).
