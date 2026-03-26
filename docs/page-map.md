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

[eventos_standapp titulo="Próximos eventos da semana" mostrar_filtros="no" mostrar_busca="no" somente_proximos="yes" dias_proximos="7" limit="6"]

[ccc_home_photo_wall
title="A casa em uma noite de show"
subtitle="Espaços reservados para fotos reais da experiência Curitiba Comedy Club."
images="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-1.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-2.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-3.jpg|https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/home-foto-4.jpg"
captions="Plateia lotada|Palco principal|Ambiente do salão|Experiência da noite"
autoplay="true"
interval="4200"]

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
[ccc_page_hero title="Sobre o Curitiba Comedy Club" subtitle="Da casa pioneira de 2010 ao palco premium de Santa Felicidade: uma historia viva da comedia brasileira." heading_level="h1" button_text="Ver programacao" button_url="/programacao/"]

[ccc_section_nav title="Navegue pela história" sections="Visão geral::sobre-visao|Linha do tempo::sobre-timeline|Experiência::sobre-experiencia|Formação::sobre-formacao|Legado::sobre-legado|Perguntas::sobre-perguntas"]

<div id="sobre-visao" class="ccc-ui-anchor-target"></div>
[ccc_about_block kicker="Desde 2010" title="O primeiro comedy club dedicado do Brasil" text="O Curitiba Comedy Club nasceu como um projeto pioneiro para dar ao stand-up um palco desenhado para a palavra, para o tempo da piada e para a experiencia completa do publico. A casa atravessou mudancas profundas, preservou sua identidade e se consolidou como referencia nacional para artistas e plateia." highlights="Pioneirismo real em Curitiba|Curadoria de comediantes e novos talentos|Experiencia premium com gastronomia e atendimento"]

[ccc_image_frame image_url="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/sobre-visao-geral.jpg" alt="Fachada e ambiente do Curitiba Comedy Club" caption="Um palco que ajudou a moldar a cena nacional de stand-up."]

<div id="sobre-timeline" class="ccc-ui-anchor-target"></div>
[ccc_timeline kicker="Marcos da casa" title="Linha do tempo do Curitiba Comedy Club" subtitle="Os capitulos que explicam por que a casa virou referencia cultural e de entretenimento." items="2005-2006::A inspiracao inicial::Joca Madalosso conhece de perto o circuito de comedy clubs nos EUA e traz a visao de um palco dedicado para Curitiba.|2009::Formato do projeto::A ideia evolui de um modelo tradicional para um clube focado em comedia, com estrutura pensada para texto, ritmo e experiencia de plateia.|2010::Inauguracao pioneira::Abertura da casa em Curitiba como primeiro comedy club dedicado do Brasil, iniciando uma nova fase para o stand-up nacional.|2010-2020::Decada de formacao::Palco de testes, lapidacao e consolidacao de comediantes que se projetaram para o Brasil inteiro.|2020::Pausa forcada::Com a pandemia, a operacao da sede original e interrompida, marcando um periodo desafiador para a cena ao vivo.|2021::Renascimento em Santa Felicidade::A casa reabre em novo endereco, preserva simbolos historicos e inicia uma fase mais ampla em hospitalidade e experiencia.|Hoje::Nova fase premium::Programacao ativa de terca a sabado, Open Mic, eventos privados e um ecossistema vivo de comedia."]

<div id="sobre-experiencia" class="ccc-ui-anchor-target"></div>
[ccc_section_heading kicker="A experiencia" title="Mais do que um show: uma noite completa" subtitle="Do primeiro atendimento ao ultimo aplauso, tudo e pensado para aproximar publico e artista."]

[ccc_about_block kicker="A casa" title="Comedia, atmosfera e hospitalidade" text="A experiencia no Curitiba Comedy Club combina ambiente intimista, visibilidade de palco, operacao de sala cuidadosa e gastronomia que acompanha o ritmo da noite. O publico nao vem apenas para assistir: vem para viver uma jornada completa, com conforto, clima de espetaculo e memorias que continuam depois do show." highlights="Ambiente intimista e boa visibilidade|Servico alinhado ao ritmo do palco|Gastronomia e drinks para a noite toda"]

[ccc_image_frame image_url="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/sobre-palco-historico.jpg" alt="Palco do Curitiba Comedy Club em noite de show" caption="Onde o publico encontra grandes nomes e novas vozes da comedia."]

<div id="sobre-formacao" class="ccc-ui-anchor-target"></div>
[ccc_section_heading kicker="Formacao" title="A casa que impulsiona comediantes" subtitle="Do teste de texto ao solo lotado: um caminho real de evolucao artistica."]

[ccc_about_block kicker="Open Mic e desenvolvimento" title="Espaco para quem esta construindo voz" text="A cultura de formacao continua viva na casa. As noites de Open Mic funcionam como laboratorio de material, tempo de palco e leitura de publico. Muitos artistas que hoje lotam teatros passaram por esse processo de tentativa, ajuste e evolucao em palcos como o do Curitiba Comedy Club." highlights="Teste de texto em ambiente profissional|Contato real com plateia e timing|Escada de evolucao para projetos maiores" button_text="Conhecer o Open Mic" button_url="/open-mic/"]

[ccc_image_frame image_url="https://curitibacomedyclub.com.br/wp-content/uploads/2026/03/sobre-formacao-openmic.jpg" alt="Comediante em noite de Open Mic" caption="Do primeiro microfone ao repertorio maduro: cada etapa conta."]

<div id="sobre-legado" class="ccc-ui-anchor-target"></div>
[ccc_feature_cards title="Legado e impacto" subtitle="Mais que uma casa de shows: um ecossistema de comedia em movimento." items="Polo de talentos::Curitiba se consolidou como berco de nomes fortes do stand-up nacional, com historico real de desenvolvimento artistico.|Curadoria constante::A agenda mistura headliners, nomes locais e noites de experimentacao, mantendo a casa viva e relevante.|Impacto cultural::A casa ajudou a mudar a percepcao sobre a cena de humor curitibana e fortaleceu o circuito local.|Conexao com a cidade::Comedia, hospitalidade e identidade de Santa Felicidade reunidas em uma experiencia reconhecida pelo publico."]

<div id="sobre-perguntas" class="ccc-ui-anchor-target"></div>
[ccc_accordion title="Perguntas frequentes sobre a casa" subtitle="Contexto rapido para quem quer conhecer melhor a historia e o formato do Curitiba Comedy Club." items="O Curitiba Comedy Club foi mesmo pioneiro?::Sim. A casa nasceu em 2010 como o primeiro comedy club dedicado do Brasil e se tornou referencia para o formato no pais.|A casa segue apoiando novos comediantes?::Sim. O Open Mic faz parte da cultura da casa como espaco de teste, lapidacao de texto e evolucao de palco.|O que diferencia a fase atual?::A sede em Santa Felicidade ampliou a experiencia, unindo curadoria de humor, estrutura de palco, atendimento e gastronomia em um formato premium.|A casa recebe somente grandes nomes?::Nao. A proposta combina artistas consagrados com espaco para novas vozes, mantendo a cena em movimento.|Pergunta curitibana obrigatoria: aqui tem salsicha ou vina?::Se voce e de Curitiba, ja sabe: aqui a resposta oficial e vina. A boa noticia e que, com esse humor local, voce ja comecou a noite no clima certo." open_first="true" allow_multiple="false"]

[ccc_cta_ingressos title="Conheca a agenda da semana" text="Veja os proximos shows e escolha a sua proxima noite no Curitiba Comedy Club." button_text="Ver programacao" button_url="/programacao/"]
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
subtitle="Bebidas, petiscos, pratos exclusivos e sobremesas"
description="Use o botao principal para abrir o Prato Digital."
food_slots_title="Espacos de comidas"
food_slots_note=""
food_slots="Bebidas|Petiscos|Pratos exclusivos|Especialidades Dom Antonio|Sobremesas"
food_slot_texts="Selecao de drinks classicos, autorais e sem alcool.|Entradas para compartilhar e porcoes da casa para acompanhar o show.|Pratos principais com assinatura da cozinha e foco em experiencia completa.|Receitas da casa com identidade Dom Antonio e combinacoes exclusivas.|Finalizacoes doces para fechar a noite com equilibrio e sabor."
button_text="Abrir Prato Digital"
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
[ccc_page_hero title="Contato" subtitle="Fale com a equipe e planeje sua proxima noite no Curitiba Comedy Club." heading_level="h1" button_text="Chamar no WhatsApp" button_url="https://wa.me/554133366258?text=Oi!%20Tudo%20bem?%20Quero%20falar%20com%20o%20Curitiba%20Comedy%20Club."]

[ccc_contact_section
title="Canais oficiais"
whatsapp="(41) 99999-9999"
whatsapp_note="Somente mensagens. Não atendemos ligações."
instagram="@curitibacomedy"
linktree_url="https://wa.me/554133366258?text=Oi!%20Tudo%20bem?%20Quero%20falar%20com%20o%20Curitiba%20Comedy%20Club."
linktree_text="Chamar no WhatsApp"
address="Rua Exemplo, 123 - Curitiba/PR"]
```

## Open Mic (pagina dedicada)

Objetivo da pagina:

- anunciar datas e proposta do formato
- captar inscricoes de novos comediantes
- direcionar publico para acompanhar a agenda

Copy inicial sugerida:

- Hero titulo: Open Mic
- Hero subtitulo: Novos talentos, testes de texto e noites experimentais no palco do Curitiba Comedy Club.

Snippet inicial:

```text
[ccc_page_hero title="Open Mic" subtitle="Novos talentos, testes de texto e noites experimentais no palco do Curitiba Comedy Club." heading_level="h1"]

[ccc_section_heading kicker="Palco aberto" title="Como funciona" subtitle="No Open Mic, comediantes em desenvolvimento apresentam blocos curtos e o publico acompanha a construcao de novos sets."]

[ccc_about_block title="Quer subir no palco?" text="Fale com a equipe para receber as regras de participacao, datas disponiveis e orientacoes de inscricao." highlights="Vagas limitadas por noite|Selecao por inscricao previa|Ambiente profissional de teste"]

[ccc_cta_ingressos title="Inscricoes e agenda Open Mic" text="Fale com a equipe e acompanhe as proximas datas." button_text="Chamar no WhatsApp" button_url="https://wa.me/554133366258?text=Oi!%20Tenho%20interesse%20em%20entender%20melhor%20como%20funciona%20o%20Open%20Mic%20do%20Curitiba%20Comedy%20Club."]
```

## Erro 404 (pagina dedicada)

Objetivo da pagina:

- transformar erro em experiencia de marca
- manter tom leve com humor da casa
- direcionar o usuario de volta para programacao e home

Snippet inicial:

```text
[ccc_page_hero title="Ops! Deu 404 por aqui" subtitle="A piada foi tao boa que ate o servidor caiu. Mas calma: o show continua em outras paginas." heading_level="h1" button_text="Ver programacao" button_url="/programacao/"]

[ccc_section_heading kicker="Erro 404" title="Esta pagina saiu de cartaz" subtitle="Pode ter mudado de endereco, sido removida ou digitada com erro. Acontece ate com comediante no improviso."]

[ccc_about_block kicker="Atalhos rapidos" title="Reencontre o palco em poucos cliques" text="Escolha um caminho rapido para voltar ao site sem perder o timing da noite." highlights="Agenda de shows|Pagina Open Mic|Eventos privados"]

[ccc_split_cta title="Reencontre o palco" subtitle="Use os acessos rapidos para continuar navegando sem perder o timing." primary_text="Ir para Programacao" primary_url="/programacao/" secondary_text="Voltar para Home" secondary_url="/"]

[ccc_cta_ingressos title="Quer falar com a equipe?" text="Se voce caiu aqui por um link antigo, chama no WhatsApp e te ajudamos com o caminho certo." button_text="Chamar no WhatsApp" button_url="https://wa.me/554133366258?text=Oi!%20Cai%20na%20pagina%20404%20e%20quero%20ajuda%20para%20encontrar%20a%20pagina%20certa."]
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
