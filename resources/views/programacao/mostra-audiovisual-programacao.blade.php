@extends('layouts.app')

@section('content')
<style>
    .mostra-cartaz {
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow: hidden;
        border: 1px solid #dce9ec;
        border-radius: 10px;
        background: #edf4f5;
        text-decoration: none;
    }
    .mostra-cartaz img {
        display: block;
        width: 100%;
        height: 320px;
        object-fit: contain;
        padding: 12px;
    }
    .mostra-cartaz-legenda {
        width: 100%;
        padding: 8px 12px;
        background: #fff;
        color: #246477;
        font-size: .875rem;
        text-align: right;
        border-top: 1px solid #dce9ec;
    }
    .mostra-cartaz:hover { border-color: #3d93a9; }
    .mostra-cartaz:focus-visible { outline: 3px solid #f2a440; outline-offset: 3px; }
    @media (max-width: 575px) {
        .mostra-cartaz img { height: 280px; }
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 mb-4 fw-bold" style="color: #3d93a9; border-bottom: 3px solid #f2a440; padding-bottom: 10px;">Mostra AudioVisual</h1>
            <p class="fs-5 fw-normal mb-4">Mostra Audiovisual – XV CBEE 2026</p>
            <nav aria-label="Sessões da mostra" class="d-flex flex-wrap gap-2 mb-5">
                <a class="btn btn-outline-info" href="#sessao-1">17/11 · Tarde</a>
                <a class="btn btn-outline-info" href="#sessao-2">17/11 · Noite</a>
                <a class="btn btn-outline-info" href="#sessao-3">18/11 · Tarde</a>
                <a class="btn btn-outline-info" href="#sessao-4">18/11 · Noite</a>
            </nav>
<section id="sessao-1" aria-labelledby="titulo-sessao-1" class="mb-5" style="scroll-margin-top: 100px;"><h2 id="titulo-sessao-1" class="h3 mb-2" style="color: #3d93a9;">17/11  &#183;  Sess&#227;o da tarde  &#183;  17h30 &#224;s 18h30</h2><p class="text-muted mb-3">3 filmes &#183; 51min23s</p><div class="row g-3">
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-1.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Amy&#225; ay Barae (Alimentando o Futuro) (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-1.jpeg') }}" alt="Cartaz do filme Amy&#225; ay Barae (Alimentando o Futuro)" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Amy&#225; ay Barae (Alimentando o Futuro)</h3><p class="mb-2"><strong>Direção:</strong> Maria J&#250;lia Batalha e Larissa Nascimento S&#225;tiro</p><p class="mb-3"><strong>Duração:</strong> 0:19:25</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://youtu.be/zwFSpSDQuh8" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Amy&#225; ay Barae (Alimentando o Futuro) (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1XeeAwo_fAYFNX9DZYdaALtHoUAWs9PW6?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: Amy&#225; ay Barae (Alimentando o Futuro) (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1XeeAwo_fAYFNX9DZYdaALtHoUAWs9PW6?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Amy&#225; ay Barae (Alimentando o Futuro) (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-2.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Conhecimentos tradicionais na cidade: O feitio do Colorau (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-2.jpeg') }}" alt="Cartaz do filme Conhecimentos tradicionais na cidade: O feitio do Colorau" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Conhecimentos tradicionais na cidade: O feitio do Colorau</h3><p class="mb-2"><strong>Direção:</strong> Dion&#237;sio Dias Garcia e Isabella Fernandes Fantini</p><p class="mb-3"><strong>Duração:</strong> 0:05:24</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=fJHv4Hs7uNo" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Conhecimentos tradicionais na cidade: O feitio do Colorau (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1Qwoki4eX3BxWWwfZwpeM2AjRaoIfeVzU?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: Conhecimentos tradicionais na cidade: O feitio do Colorau (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1Qwoki4eX3BxWWwfZwpeM2AjRaoIfeVzU" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Conhecimentos tradicionais na cidade: O feitio do Colorau (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-3.png') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Porque Manjeric&#227;o e n&#227;o alface? (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-3.png') }}" alt="Cartaz do filme Porque Manjeric&#227;o e n&#227;o alface?" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Porque Manjeric&#227;o e n&#227;o alface?</h3><p class="mb-2"><strong>Direção:</strong> Cris&#226;ngela Souza</p><p class="mb-3"><strong>Duração:</strong> 0:26:34</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=GtbykwPxOEk" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Porque Manjeric&#227;o e n&#227;o alface? (abre em nova aba)</span></a>
</div>
</article></div>
</div></section>
<section id="sessao-2" aria-labelledby="titulo-sessao-2" class="mb-5" style="scroll-margin-top: 100px;"><h2 id="titulo-sessao-2" class="h3 mb-2" style="color: #3d93a9;">17/11  &#183;  Sess&#227;o da noite</h2><p class="text-muted mb-3">5 filmes &#183; 1h24min27s</p><div class="row g-3">
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-4.png') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: O Cavaleiro Levanta a Espada Flamejante (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-4.png') }}" alt="Cartaz do filme O Cavaleiro Levanta a Espada Flamejante" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">O Cavaleiro Levanta a Espada Flamejante</h3><p class="mb-2"><strong>Direção:</strong> Danilo Vila&#231;a</p><p class="mb-3"><strong>Duração:</strong> 0:14:28</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://drive.google.com/drive/folders/1yYaE1qFrXUMtWxDenFPGmU-y6OuiKr6Y?usp=drive_link" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: O Cavaleiro Levanta a Espada Flamejante (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/184Q3eonuyjyxSJoahL87NVXOChpB7DOl" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: O Cavaleiro Levanta a Espada Flamejante (abre em nova aba)</span></a>
<a href="https://www.youtube.com/@OCavaleiroLevanta-z7x/videos" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: O Cavaleiro Levanta a Espada Flamejante (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-5.jpg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Mapa da Oralidade Quilombola da Comunidade de Barreiros, Itagua&#231;u da Bahia (BA) (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-5.jpg') }}" alt="Cartaz do filme Mapa da Oralidade Quilombola da Comunidade de Barreiros, Itagua&#231;u da Bahia (BA)" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Mapa da Oralidade Quilombola da Comunidade de Barreiros, Itagua&#231;u da Bahia (BA)</h3><p class="mb-2"><strong>Direção:</strong> Ted Ferreira / Thiago Leandro da Silva Dias</p><p class="mb-3"><strong>Duração:</strong> 0:09:52</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=gEVfSOjRMZE" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Mapa da Oralidade Quilombola da Comunidade de Barreiros, Itagua&#231;u da Bahia (BA) (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-6.jpg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Feridas na Terra (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-6.jpg') }}" alt="Cartaz do filme Feridas na Terra" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Feridas na Terra</h3><p class="mb-2"><strong>Direção:</strong> Helena Duarte</p><p class="mb-3"><strong>Duração:</strong> 0:46:00</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://drive.google.com/file/d/12FjRzmGJWG9cd7VkKxW0rJ9tKhdD6yID/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Feridas na Terra (abre em nova aba)</span></a>
<a href="https://www.youtube.com/watch?v=7G0CEegb3bw" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Feridas na Terra (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-7.png') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Paisagem. Povo. Terra. (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-7.png') }}" alt="Cartaz do filme Paisagem. Povo. Terra." loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Paisagem. Povo. Terra.</h3><p class="mb-2"><strong>Direção:</strong> Jo&#227;o Masaia Alvarenga de Toledo</p><p class="mb-3"><strong>Duração:</strong> 0:02:40</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://youtu.be/0oFkLZXOLQg" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Paisagem. Povo. Terra. (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-8.png') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Pandemia nas Ro&#231;as: Os quatro povos do Oiapoque (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-8.png') }}" alt="Cartaz do filme Pandemia nas Ro&#231;as: Os quatro povos do Oiapoque" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Pandemia nas Ro&#231;as: Os quatro povos do Oiapoque</h3><p class="mb-2"><strong>Direção:</strong> Rede Arawa e Instituto Iep&#233;</p><p class="mb-3"><strong>Duração:</strong> 0:11:27</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://drive.google.com/file/d/1SUSv3K76kf5OV9Hk63rpyCZ5DbRfBK6j/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Pandemia nas Ro&#231;as: Os quatro povos do Oiapoque (abre em nova aba)</span></a>
<a href="https://drive.google.com/file/d/1gm8VGZk88gakj1lZSZH4PQ2KPZhtyqxS/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Pandemia nas Ro&#231;as: Os quatro povos do Oiapoque (abre em nova aba)</span></a>
</div>
</article></div>
</div></section>
<section id="sessao-3" aria-labelledby="titulo-sessao-3" class="mb-5" style="scroll-margin-top: 100px;"><h2 id="titulo-sessao-3" class="h3 mb-2" style="color: #3d93a9;">18/11  &#183;  Sess&#227;o da tarde  &#183;  17h30 &#224;s 18h30</h2><p class="text-muted mb-3">2 filmes &#183; 50min15s</p><div class="row g-3">
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-9.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Entre Hortas e Quintais - Alimentos Ancestrais (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-9.jpeg') }}" alt="Cartaz do filme Entre Hortas e Quintais - Alimentos Ancestrais" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Entre Hortas e Quintais - Alimentos Ancestrais</h3><p class="mb-2"><strong>Direção:</strong> Gustavo Monge e Rodrigo Castilho Freitas</p><p class="mb-3"><strong>Duração:</strong> 0:30:00</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=UOBeSjo1bvc" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Entre Hortas e Quintais - Alimentos Ancestrais (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1vz4DcxAAS6McT-bn7bJjerLyAbyC6zv4" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: Entre Hortas e Quintais - Alimentos Ancestrais (abre em nova aba)</span></a>
<a href="https://www.youtube.com/watch?v=UOBeSjo1bvc&amp;feature=youtu.be" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Entre Hortas e Quintais - Alimentos Ancestrais (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-10.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: A Mureta que Chora Mar (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-10.jpeg') }}" alt="Cartaz do filme A Mureta que Chora Mar" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">A Mureta que Chora Mar</h3><p class="mb-2"><strong>Direção:</strong> Fernanda Terra Stori e Iuri Castro</p><p class="mb-3"><strong>Duração:</strong> 0:20:15</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://drive.google.com/file/d/1DYtYH9y3v-05rDZx3p_5mWVhtrzNmrp-/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: A Mureta que Chora Mar (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1AoR9JLD5UWacK7oSo_m90cN8rl665uY7?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: A Mureta que Chora Mar (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/1AoR9JLD5UWacK7oSo_m90cN8rl665uY7?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: A Mureta que Chora Mar (abre em nova aba)</span></a>
</div>
<small class="text-muted mt-2">Teaser: na mesma pasta do cartaz.</small>
</article></div>
</div></section>
<section id="sessao-4" aria-labelledby="titulo-sessao-4" class="mb-5" style="scroll-margin-top: 100px;"><h2 id="titulo-sessao-4" class="h3 mb-2" style="color: #3d93a9;">18/11  &#183;  Sess&#227;o da noite</h2><p class="text-muted mb-3">3 filmes &#183; 1h27min49s</p><div class="row g-3">
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-11.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Verter (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-11.jpeg') }}" alt="Cartaz do filme Verter" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Verter</h3><p class="mb-2"><strong>Direção:</strong> CEVEX/UFMG</p><p class="mb-3"><strong>Duração:</strong> 0:30:00</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=T08etJrP8dU" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Verter (abre em nova aba)</span></a>
<a href="https://drive.google.com/drive/folders/12512EDZFyqZn6XfpCHkEiCubkYAMcuno?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: Verter (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-12.jpeg') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Misioneros del temporal (Mission&#225;rios da chuva) (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-12.jpeg') }}" alt="Cartaz do filme Misioneros del temporal (Mission&#225;rios da chuva)" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Misioneros del temporal (Mission&#225;rios da chuva)</h3><p class="mb-2"><strong>Direção:</strong> Jimena Leonor Paz Navarro</p><p class="mb-3"><strong>Duração:</strong> 0:27:11</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://drive.google.com/file/d/1y2y2KOo-Q7IAMHzpwVdBGVfyfpvVCa0v/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Misioneros del temporal (Mission&#225;rios da chuva) (abre em nova aba)</span></a>
<a href="https://drive.google.com/file/d/12KwysbsewLnTWZzIvf3tqTk9ydkgl_tc/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver cartaz<span class="visually-hidden">: Misioneros del temporal (Mission&#225;rios da chuva) (abre em nova aba)</span></a>
<a href="https://drive.google.com/file/d/1Im3-_hRMxuQlLngNMvqBiEc7HmoukyEk/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="btn btn-outline-info">Ver teaser<span class="visually-hidden">: Misioneros del temporal (Mission&#225;rios da chuva) (abre em nova aba)</span></a>
</div>
</article></div>
<div class="col-12 col-lg-6"><article class="h-100 shadow-sm p-4 bg-white rounded border-start border-4 d-flex flex-column" style="border-color: #f2a440 !important;"><a class="mostra-cartaz mb-4" href="{{ asset('img/mostra-audiovisual/cartaz-13.png') }}" target="_blank" rel="noopener noreferrer" aria-label="Ampliar cartaz: Ra&#237;zes do Bonete: Document&#225;rio Etnogr&#225;fico na Comunidade Cai&#231;ara do Bonete (Ilhabela, SP) (abre em nova aba)">
    <img src="{{ asset('img/mostra-audiovisual/cartaz-13.png') }}" alt="Cartaz do filme Ra&#237;zes do Bonete: Document&#225;rio Etnogr&#225;fico na Comunidade Cai&#231;ara do Bonete (Ilhabela, SP)" loading="lazy" decoding="async">
    <span class="mostra-cartaz-legenda">Ampliar cartaz <span aria-hidden="true">↗</span></span>
</a><h3 class="h5 fw-bold mb-3">Ra&#237;zes do Bonete: Document&#225;rio Etnogr&#225;fico na Comunidade Cai&#231;ara do Bonete (Ilhabela, SP)</h3><p class="mb-2"><strong>Direção:</strong> Julio Avanzo Neto e Yara Vergueiro</p><p class="mb-3"><strong>Duração:</strong> 0:30:38</p><div class="d-flex flex-wrap gap-2 mt-auto">
<a href="https://www.youtube.com/watch?v=1QmOhzavVys" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Ver filme<span class="visually-hidden">: Ra&#237;zes do Bonete: Document&#225;rio Etnogr&#225;fico na Comunidade Cai&#231;ara do Bonete (Ilhabela, SP) (abre em nova aba)</span></a>
</div>
</article></div>
</div></section>
</div></div></div>
@endsection
