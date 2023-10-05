//slide eventos passados

var listCards = document.getElementById("listCardSlides");
var nextCard = document.getElementById("nextSlideCards");
var prevCard = document.getElementById("prevSlideCards");

var quantEventos = document.getElementById("quantEventos").value;

var contClicks = 0;

//logica baseada na quantidade de slides e margens 
//slide ocupa 250px de tamanho + 35px de margem (normal)
//funcao para pegar quantidade de slides 
//variavel de controle abraça 3 casos (slide pode ter 3 cards 2 e 1 logo a limite de cliques deve ser diferente para cada condição)

//codigo para pagina estatica
var WidthScreen = window.innerWidth;
var controle;

if(WidthScreen >= 1000){
    controle = 3;
}else if(WidthScreen < 1000 && WidthScreen > 768 ){
    controle = 2;
}else if(WidthScreen < 768){
    controle = 1;
}

//codigo para resize

window.addEventListener('resize',()=>{
   
    if(window.innerWidth >= 1000){
        controle = 3;
    }else if(window.innerWidth < 1000 && window.innerWidth > 768 ){
        controle = 2;
    }else if(window.innerWidth < 768){
        controle = 1;
    }
})


nextCard.addEventListener("click", () => {

    //caso base
    if (contClicks < quantEventos - controle) {
        //logica de parada: se a quantidade de clicks for menos do que eventos - 3 (3 referente ao slide de 3 cards)
        //ou a quantidade de eventos for menor que 3 (ocasiona 2 cards ou 1 card) 
        contClicks++;
        listCards.style.marginLeft = `-${contClicks * 280}px`;
    }
});

prevCard.addEventListener("click", () => {

    if (contClicks > 0) {  //logica de parada: nao pode ter slide negativo 
        contClicks--;
        listCards.style.marginLeft = `-${contClicks * 280}px`;
    };
})

