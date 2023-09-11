//slide eventos passados

var listCards = document.getElementById("listCardSlides");
var nextCard = document.getElementById("nextSlideCards");
var prevCard = document.getElementById("prevSlideCards");

var quantEventos = document.getElementById("quantEventos").value;

console.log(quantEventos)

var contClicks = 0;

//logica baseada na quantidade de slides e margens 

//slide ocupa 250px de tamanho + 35px de margem (normal)

//funcao para pegar quantidade de slides 

nextCard.addEventListener("click",()=>{

    if (contClicks < quantEventos - 3){ 
        //logica de parada: se a quantidade de clicks for menos do que eventos - 3 (3 referente ao slide de 3 cards)
        //ou a quantidade de eventos for menor que 3 (ocasiona 2 cards ou 1 card) 
        contClicks++;
        listCards.style.marginLeft = `-${contClicks * 270}px`; 
    }
});

prevCard.addEventListener("click",()=>{

    if(contClicks > 0){  //logica de parada: nao pode ter slide negativo 
        contClicks--;
        listCards.style.marginLeft = `-${contClicks * 270}px`;
    };
})

