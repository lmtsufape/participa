if (localStorage.getItem('dark-mode') == "active") {
    document.getElementById('img-change-mode').src = "/img/icons/sun.png"
    document.documentElement.classList.toggle('dark-mode')
}

$(document).ready(function () {
    $('#change-mode').click(function () {
        document.documentElement.classList.toggle('dark-mode')
        console.log(document.getElementById('img-change-mode').src);
        if (document.getElementById('img-change-mode').src.endsWith("mom.png")) {
            document.getElementById('img-change-mode').src = "/img/icons/sun.png"
            localStorage.setItem('dark-mode', "active");
        } else {
            document.getElementById('img-change-mode').src = "/img/icons/mom.png"
            localStorage.setItem('dark-mode', "no-active");
        }
    })

    $('#font-size-plus').click(function() {
        var elemento = $(".acessibilidade");
        var fonte = parseInt(elemento.css('font-size'));

        var body = $("body");
        const fonteNormal = parseInt(body.css('font-size'));
        fonte++;

        elemento.css("fontSize", fonte);
    })

    $('#font-size-min').click(function() {
        var elemento = $(".acessibilidade");
        var fonte = parseInt(elemento.css('font-size'));

        var body = $("body");
        const fonteNormal = parseInt(body.css('font-size'));
        fonte--;

        elemento.css("fontSize", fonte);
    })

    var btn = document.getElementById("submeterFormBotao");
    if(btn){
        $(document).on('submit', 'form', function() {
            $('button').attr('disabled', 'disabled');
            btn.textContent = 'Aguarde...';
        });
    }
})
$(function () {
    $('[data-toggle="popover"]').popover()
});
