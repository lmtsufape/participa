function marcar_desmarcar_todos_checkbox_por_classe(checkbox_marcar_desmarcar, nome_classe)  {
    // se o checkbox foi marcado itera por todos os elementos com a classe nome_classe
    // e marca todos eles como sim, se foi desmarcado, desmarca todo mundo
    let valor_da_marcacao = checkbox_marcar_desmarcar.checked;
    let checkboxes_areas = document.getElementsByClassName(nome_classe);

    for(let checkbox of checkboxes_areas) {
        checkbox.checked = valor_da_marcacao;
    }

}
