# Guia de Shortcodes - Curitiba Comedy Club

Documento destinado ao administrador do site (Joca Madalosso) para entender como os blocos visuais do site funcionam e como editar textos sem precisar mexer em codigo.

## Por que shortcodes e nao elementos do Elementor?

O site usa o Elementor como base de layout (macro estrutura das paginas), mas os blocos visuais com identidade do Curitiba Comedy Club sao todos construidos como **shortcodes** dentro de dois plugins proprios:

- **ccc-ui-kit** — componentes visuais (hero, carrosseis, cards, contato, etc.)
- **ccc-eventos-standapp** — listagem de eventos via API do Standapp

### Vantagens dessa abordagem

1. **Consistencia visual** — todos os blocos seguem o mesmo padrao de cores, tipografia e espacamento, independente de quem edita a pagina.
2. **Facilidade de manutencao** — para trocar um texto, basta editar o atributo no shortcode. Nao precisa navegar por camadas do Elementor.
3. **Portabilidade** — se um dia o tema ou o page builder mudar, os shortcodes continuam funcionando.
4. **Versionamento** — o codigo dos plugins esta no GitHub, entao toda alteracao fica registrada e pode ser revertida.

## Como funciona na pratica

Dentro do Elementor, voce usa um widget de **HTML** ou **Shortcode** e cola o shortcode com os atributos desejados. Exemplo:

```
[ccc_page_hero title="Programacao" subtitle="Confira os proximos shows." button_text="Ver ingressos" button_url="/programacao/"]
```

Cada palavra antes do `=` e um **atributo**. O texto entre aspas e o **valor** que aparece no site. Para mudar o texto, basta trocar o valor entre aspas.

## Lista completa de shortcodes

### ccc_page_hero

Hero principal de pagina. Usar uma vez por pagina como primeiro bloco.

```
[ccc_page_hero
  kicker="Curitiba Comedy Club"
  title="Noite premium de stand-up em Curitiba"
  subtitle="Comedia, atmosfera cinematografica e publico apaixonado por humor."
  button_text="Ver programacao"
  button_url="/programacao/"
  align="left"
  heading_level="h1"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| kicker | Texto pequeno acima do titulo | Curitiba Comedy Club |
| title | Titulo principal | Noite premium de stand-up em Curitiba |
| subtitle | Texto abaixo do titulo | Comedia, atmosfera cinematografica... |
| button_text | Texto do botao | Ver programacao |
| button_url | Link do botao | /programacao/ |
| align | Alinhamento (left ou center) | left |
| heading_level | Tag HTML do titulo (h1, h2, h3) | h1 |

---

### ccc_about_block

Bloco institucional com texto, destaques e botao.

```
[ccc_about_block
  kicker="Sobre a casa"
  title="Comedia premium no coracao de Curitiba"
  text="O Curitiba Comedy Club entrega uma experiencia completa..."
  highlights="Palco profissional|Drinks e cozinha|Programacao semanal"
  button_text="Ver programacao"
  button_url="/programacao/"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| kicker | Texto pequeno acima do titulo | Sobre a casa |
| title | Titulo do bloco | Comedia premium no coracao de Curitiba |
| text | Paragrafo descritivo | (texto longo sobre a casa) |
| highlights | Itens de destaque separados por `\|` | Palco profissional\|Drinks e cozinha\|Programacao semanal |
| button_text | Texto do botao | Ver programacao |
| button_url | Link do botao | /programacao/ |

---

### ccc_cta_ingressos

Bloco de chamada para acao (compra de ingressos).

```
[ccc_cta_ingressos
  title="Garanta seu ingresso"
  text="A programacao muda toda semana. Escolha o show e reserve seu lugar."
  button_text="Comprar agora"
  button_url="https://standapp.com.br/parceiro/curitiba-comedy-club"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| title | Titulo | Garanta seu ingresso |
| text | Texto de apoio | A programacao muda toda semana... |
| button_text | Texto do botao | Comprar agora |
| button_url | Link do botao | (link Standapp) |

---

### ccc_split_cta

CTA duplo com dois botoes (primario e secundario).

```
[ccc_split_cta
  title="Escolha sua proxima experiencia"
  subtitle="Garanta ingresso para os shows ou fale com a equipe."
  primary_text="Ver programacao"
  primary_url="/programacao/"
  secondary_text="Eventos privados"
  secondary_url="/eventos-privados/"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| title | Titulo | Escolha sua proxima experiencia |
| subtitle | Subtitulo | Garanta ingresso para os shows... |
| primary_text | Texto do botao principal | Ver programacao |
| primary_url | Link do botao principal | /programacao/ |
| secondary_text | Texto do botao secundario | Eventos privados |
| secondary_url | Link do botao secundario | /eventos-privados/ |

---

### ccc_contact_section

Secao de contato com WhatsApp, Instagram, mapa e link de acao.

```
[ccc_contact_section
  title="Contato"
  address="Rua Exemplo, 123 - Curitiba/PR"
  whatsapp="(41) 99999-9999"
  whatsapp_note="Somente mensagens. Nao atendemos ligacoes."
  whatsapp_message="Oi! Tudo bem? Quero falar com o Curitiba Comedy Club."
  instagram="@curitibacomedy"
  map_url=""
  map_title="Mapa do local do Curitiba Comedy Club"
  map_button_text="Ver local no mapa"
  linktree_url=""
  linktree_text="Chamar no WhatsApp"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| title | Titulo da secao | Contato |
| address | Endereco exibido | Rua Exemplo, 123 - Curitiba/PR |
| whatsapp | Numero exibido | (41) 99999-9999 |
| whatsapp_note | Observacao abaixo do numero | Somente mensagens... |
| whatsapp_message | Mensagem pre-preenchida do WhatsApp | Oi! Tudo bem? Quero falar... |
| instagram | Perfil exibido | @curitibacomedy |
| map_url | URL do Google Maps (auto-gerado se vazio) | (vazio) |
| map_title | Titulo de acessibilidade do mapa | Mapa do local do Curitiba Comedy Club |
| map_button_text | Texto do botao do mapa | Ver local no mapa |
| linktree_url | Link do botao de acao no topo | (vazio) |
| linktree_text | Texto do botao de acao no topo | Chamar no WhatsApp |

---

### ccc_menu_section

Secao de cardapio com categorias e links.

```
[ccc_menu_section
  title="Cardapio"
  subtitle="Bebidas, petiscos, pratos exclusivos e sobremesas"
  description="Use o botao principal para abrir o Prato Digital."
  food_slots="Bebidas|Petiscos|Pratos exclusivos|Sobremesas"
  food_slot_texts="Selecao de drinks...|Entradas para compartilhar...|Pratos principais...|Finalizacoes doces..."
  button_text="Abrir Prato Digital"
  button_url="https://..."
  pdf_url="https://..."
  pdf_button_text="Ver cardapio em PDF"
  embed_placeholder="Em breve: visualizacao embutida do cardapio."]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| title | Titulo | Cardapio |
| subtitle | Subtitulo | Bebidas, petiscos... |
| description | Texto explicativo | Use o botao principal... |
| food_slots | Categorias separadas por `\|` | Bebidas\|Petiscos\|... |
| food_slot_texts | Descricoes das categorias separadas por `\|` | (textos descritivos) |
| food_slots_title | Titulo da secao de categorias | Espacos de comidas |
| button_text | Texto do botao principal | Abrir Prato Digital |
| button_url | Link do botao principal | (vazio) |
| pdf_url | Link do PDF | (vazio) |
| pdf_button_text | Texto do botao de PDF | Ver cardapio em PDF |
| embed_placeholder | Texto do placeholder de embed | Em breve: visualizacao embutida... |
| show_food_slots | Exibir categorias (true/false) | true |
| open_in_new_tab | Abrir links em nova aba (true/false) | true |

---

### ccc_feature_cards

Grid de cards com diferenciais da casa.

```
[ccc_feature_cards
  title="Por que viver essa noite com a gente"
  subtitle="Diferenciais que transformam um show em experiencia completa."
  items="Curadoria::Line-up selecionado...|Casa premium::Conforto, som e visibilidade...|Gastronomia::Drinks e cozinha...|Localizacao::Acesso pratico..."]
```

**Formato dos items:** `Titulo::Descricao|Titulo::Descricao` (separados por `|`, titulo e texto separados por `::`)

Para adicionar link a um card: `Titulo::Descricao::https://link.com`

---

### ccc_fullwidth_carousel

Carrossel de imagens em largura total (usado no hero da Home).

```
[ccc_fullwidth_carousel
  images="url1|url2|url3"
  captions="Legenda 1|Legenda 2|Legenda 3"
  alt_prefix="Foto de destaque"
  autoplay="true"
  interval="5000"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| images | URLs das imagens separadas por `\|` | (placeholders) |
| captions | Legendas separadas por `\|` | (vazio) |
| alt_prefix | Prefixo do texto alternativo | Foto de destaque |
| autoplay | Rotacao automatica (true/false) | true |
| interval | Tempo entre slides em milissegundos | 5000 |
| height_desktop | Altura no desktop | clamp(300px, 42vw, 500px) |
| height_mobile | Altura no mobile | clamp(220px, 56vw, 340px) |

---

### ccc_home_photo_wall

Galeria de fotos da casa em formato de mural com carrossel.

```
[ccc_home_photo_wall
  title="Atmosfera da casa"
  subtitle="Fotos da plateia, palco, artistas e bastidores."
  images="url1|url2|url3|url4"
  captions="Legenda 1|Legenda 2|Legenda 3|Legenda 4"
  alt_prefix="Foto da casa"
  autoplay="true"
  interval="4200"]
```

| Atributo | O que faz | Valor padrao |
|---|---|---|
| title | Titulo do bloco | Atmosfera da casa |
| subtitle | Subtitulo | Espacos para fotos... |
| images | URLs das imagens separadas por `\|` | (placeholders) |
| captions | Legendas separadas por `\|` | (vazio) |
| alt_prefix | Prefixo do texto alternativo | Foto da casa |
| autoplay | Rotacao automatica (true/false) | true |
| interval | Tempo entre slides em milissegundos | 4200 |

---

### ccc_timeline

Linha do tempo com marcos da historia da casa.

```
[ccc_timeline
  kicker="Nossa historia"
  title="Linha do tempo"
  subtitle="Marcos que moldaram o Curitiba Comedy Club."
  alt_prefix="Marco historico"
  items="2010::Inauguracao::Texto descritivo.|2020::Pausa::Texto.|2021::Renascimento::Texto.|Hoje::Nova fase::Texto."]
```

**Formato dos items:** `Periodo::Titulo::Descricao|Periodo::Titulo::Descricao`

Para adicionar imagem a um item: `Periodo::Titulo::Descricao::https://url-da-imagem.jpg`

---

### ccc_accordion

Bloco de perguntas e respostas (FAQ).

```
[ccc_accordion
  title="Perguntas sobre a casa"
  subtitle="Detalhes importantes sobre o Curitiba Comedy Club."
  items="Pergunta 1?::Resposta 1.|Pergunta 2?::Resposta 2.|Pergunta 3?::Resposta 3."
  open_first="true"
  allow_multiple="false"]
```

**Formato dos items:** `Pergunta::Resposta|Pergunta::Resposta`

| Atributo | O que faz | Valor padrao |
|---|---|---|
| open_first | Primeira pergunta ja aberta (true/false) | true |
| allow_multiple | Permitir varias abertas ao mesmo tempo (true/false) | false |

---

### ccc_section_heading

Cabecalho de secao simples (kicker + titulo + subtitulo).

```
[ccc_section_heading
  kicker="Texto pequeno"
  title="Titulo da secao"
  subtitle="Subtitulo opcional"
  align="left"]
```

---

### ccc_section_nav

Barra de navegacao interna da pagina (ancora para secoes).

```
[ccc_section_nav
  title="Navegue pela historia"
  sections="Visao geral::id-secao-1|Linha do tempo::id-secao-2|Legado::id-secao-3"
  sticky="true"]
```

**Formato das sections:** `Label::id-da-ancora|Label::id-da-ancora`

---

### ccc_image_frame

Imagem com legenda e link opcional.

```
[ccc_image_frame
  image_url="https://..."
  alt="Descricao da imagem"
  caption="Legenda visivel"
  link_url=""
  open_in_new_tab="false"]
```

---

### eventos_standapp

Listagem de eventos da API Standapp (plugin separado).

```
[eventos_standapp]
```

Versao compacta para Home:

```
[eventos_standapp_home]
```

---

## Dicas para edicao

1. **Sempre use aspas duplas** ao redor dos valores: `title="Meu titulo"`
2. **Use `|` para separar itens** em listas (highlights, images, items, etc.)
3. **Use `::` para separar campos** dentro de um item (titulo::descricao)
4. **Nao quebre o shortcode em varias linhas** dentro do Elementor — mantenha tudo em uma linha ou use o widget HTML
5. **Para testar**, use o modo Preview do WordPress antes de publicar
6. **Se algo quebrar**, provavelmente ha uma aspa faltando ou um `|` a mais — revise o shortcode
7. **Imagens** devem ser URLs completas (comecando com https://) da biblioteca de midia do WordPress
