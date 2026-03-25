# Curitiba Comedy Club - Page Copy and Composition (v2)

Documento de apoio para montar as paginas no Elementor com copy coerente, sem perder o padrao de componentes do ccc-ui-kit.

## Programacao (prioridade atual)

Objetivo da pagina:

- listar shows
- contextualizar como a agenda funciona
- direcionar para compra com clareza

Blocos sugeridos (ordem):

1. [ccc_page_hero]
2. texto institucional curto (widget texto do Elementor)
3. [eventos_standapp]
4. [ccc_cta_ingressos]

Copy sugerida:

Titulo hero:

- Programacao

Subtitulo hero:

- Confira os proximos shows do Curitiba Comedy Club e garanta seu lugar nas noites mais disputadas da cidade.

Texto institucional (logo abaixo do hero):

- Nossa agenda e publicada com antecedencia aproximada de 1 mes. A casa abre de terca a sabado e a programacao pode ter ajustes em datas reservadas para eventos fechados.

Observacao operacional:

- o campo de busca da grade deve usar placeholder neutro: ex: Ary Toledo.

CTA final sugerido:

- Titulo: Nao encontrou o que queria?
- Texto: Veja todas as opcoes de ingressos e escolha sua proxima noite de comedia.
- Botao: Ver ingressos

Snippet de montagem:

```text
[ccc_page_hero title="Programacao" subtitle="Confira os proximos shows do Curitiba Comedy Club e garanta seu lugar nas noites mais disputadas da cidade." heading_level="h1"]

<p>Nossa agenda e publicada com antecedencia aproximada de 1 mes. A casa abre de terca a sabado e a programacao pode ter ajustes em datas reservadas para eventos fechados.</p>

[eventos_standapp]

[ccc_cta_ingressos title="Nao encontrou o que queria?" text="Veja todas as opcoes de ingressos e escolha sua proxima noite de comedia." button_text="Ver ingressos" button_url="https://standapp.com.br/parceiro/curitiba-comedy-club"]
```

## Home

Objetivo da página:

- gerar impacto
- mostrar prova de agenda ativa
- levar para conversão

Copy sugerida:

- Hero titulo: Curitiba Comedy Club
- Hero subtitulo: O palco onde Curitiba encontra as melhores noites de stand-up.
- CTA primário: Ver programação

Snippet base:

```text
[ccc_fullwidth_carousel
images="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-hero-1.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-hero-2.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-hero-3.jpg"
captions="Noites premium de stand-up em Curitiba.|Casa cheia, palco quente e experiência completa.|Eventos especiais, comediantes convidados e clima de espetáculo."
autoplay="true"
interval="4800"]

[ccc_page_hero title="Curitiba Comedy Club" subtitle="Noites premium de stand-up com atmosfera de espetáculo no coração de Curitiba." heading_level="h1" button_text="Ver programação" button_url="/programacao/"]

[ccc_section_heading kicker="Agenda" title="Shows em destaque" subtitle="Fique de olho nos eventos que vão acontecer esta semana!"]

[eventos_standapp_home titulo="Próximos eventos da semana" limit="6" dias_proximos="7"]

[ccc_home_photo_wall
title="A casa em uma noite de show"
subtitle="Espaços reservados para fotos reais da experiência Curitiba Comedy Club."
images="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-1.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-2.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-3.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-4.jpg"
captions="Plateia lotada|Palco principal|Ambiente do salão|Experiência da noite"]

[ccc_feature_cards
title="O que torna a noite única"
subtitle="Diferenciais para quem quer rir, comer bem e viver uma experiência completa."
items="Line-up de peso::Curadoria de humoristas consagrados e novas revelações.|Casa premium::Conforto, visibilidade e atmosfera cinematográfica.|Gastronomia no ponto::Drinks, petiscos e pratos para acompanhar o show.|Atendimento próximo::Equipe preparada para eventos sociais e corporativos."]

[ccc_split_cta
title="Escolha sua próxima experiência"
subtitle="Garanta seus ingressos ou fale com a equipe sobre eventos privados."
primary_text="Comprar ingressos"
primary_url="https://standapp.com.br/parceiro/curitiba-comedy-club"
secondary_text="Eventos privados"
secondary_url="https://wa.me/554133366258"]
```

## Sobre

Objetivo da pagina:

- reforcar posicionamento da marca
- contar historia da casa
- destacar experiencia

Copy sugerida:

- Hero titulo: Sobre o Curitiba Comedy Club
- Hero subtitulo: Uma casa criada para quem leva o riso a serio.

Snippet base:

```text
[ccc_page_hero title="Sobre o Curitiba Comedy Club" subtitle="Uma casa criada para quem leva o riso a serio." heading_level="h1"]

[ccc_about_block title="Comedia ao vivo com atmosfera de teatro" text="Unimos curadoria de elenco, estrutura de casa de espetaculo e atendimento proximo para criar uma experiencia completa, do primeiro drink ao ultimo aplauso." highlights="Programacao curada|Ambiente premium|Localizacao central"]

[ccc_cta_ingressos title="Conheca a agenda" text="Veja os proximos shows e planeje sua proxima noite." button_url="/programacao/"]
```

## Cardapio

Objetivo da pagina:

- apoiar experiencia da noite
- facilitar consulta rapida
- incentivar consumo de combos

Copy sugerida:

- Hero titulo: Cardapio
- Hero subtitulo: Drinks, petiscos e combos para acompanhar cada show.

Snippet base:

```text
[ccc_page_hero title="Cardapio" subtitle="Drinks, petiscos e combos para acompanhar cada show." heading_level="h1"]

[ccc_menu_section
title="Conheca nosso cardapio"
subtitle="Drinks, petiscos e combos"
description="Confira as categorias abaixo e abra a versao em PDF para o cardapio completo atualizado."
food_slots_title="Espacos de comidas"
food_slots_note="Categorias sugeridas para organizacao da pagina."
food_slots="Entradas|Petiscos|Pratos principais|Sobremesas"
button_text="Abrir cardapio em PDF"
button_url="https://drive.google.com/file/d/SEU_ID_DO_ARQUIVO/view"
pdf_url="https://drive.google.com/file/d/SEU_ID_DO_ARQUIVO/view"]
```

Observacao:

- Para manutencao simples, atualize apenas o link do Google Drive quando subir uma nova versao do PDF.

## Contato

Objetivo da pagina:

- orientar acesso rapido
- concentrar canais oficiais
- reduzir ruido de atendimento

Regras desta versao:

- sem e-mail publico
- WhatsApp apenas mensagens
- Instagram oficial: @curitibacomedy

Snippet base:

```text
[ccc_page_hero title="Contato" subtitle="Fale com a equipe e planeje sua proxima noite no Curitiba Comedy Club." heading_level="h1"]

[ccc_contact_section
title="Canais oficiais"
whatsapp="(41) 99999-9999"
instagram="@curitibacomedy"
email=""
linktree_url="https://linktr.ee/curitibacomedy"
linktree_text="Abrir Linktree"
address="Rua Exemplo, 123 - Curitiba/PR"]
```

## Eventos Privados (pagina dedicada criada)

Objetivo da pagina:

- captar leads B2B
- apresentar formatos de evento fechado
- facilitar contato comercial

Copy inicial sugerida:

- Hero titulo: Eventos Privados
- Hero subtitulo: Leve a experiencia do Curitiba Comedy Club para seu evento corporativo ou comemoracao.

Snippet inicial:

```text
[ccc_page_hero title="Eventos Privados" subtitle="Leve a experiencia do Curitiba Comedy Club para seu evento corporativo ou comemoracao." heading_level="h1"]

[ccc_section_heading kicker="B2B" title="Projetos sob medida" subtitle="Estruturamos eventos fechados para empresas, aniversarios e grupos especiais."]

[ccc_image_frame image_url="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/eventos-privados.jpg" alt="Ambiente do Curitiba Comedy Club para eventos privados" caption="Estrutura premium para eventos corporativos e comemoracoes."]

[ccc_cta_ingressos title="Fale com o comercial" text="Conte o objetivo do seu evento e montamos uma proposta." button_text="Chamar no WhatsApp" button_url="https://wa.me/554133366258"]
```

## Checklist rapido de publicacao

1. Validar hierarquia de titulo (um h1 por pagina).
2. Confirmar links de CTA para rotas reais do site.
3. Testar em mobile (espacamento, quebra de linha e botoes).
4. Confirmar consistencia de tom entre hero e bloco seguinte.
5. Revisar dados de contato antes de publicar.

## Notas de governanca

- Elementor fica no macro layout.
- ccc-ui-kit fica com identidade visual e blocos institucionais.
- ccc-eventos-standapp fica com logica e listagem de agenda.
