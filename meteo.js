function fetchMeteo() {
    let url = 'https://prevision-meteo.ch/services/json/chateaulin' // préciser l’url à interroger sous forme de chaîne
    fetch(url) //demande pour récupérer les informations se trouvant à l’url précisée
        .then(response => response.json()) // quand la réponse arrive, la convertir en json
        .then(function (data) { // quand la conversion est terminée, récupérer les données
            // traitement à effectuer sur les données (data)
            console.log(data)  // affichage du contenu JSON dans la console
            afficheMeteo(data) // appel de la méthode qui alimente les éléments HTML
        })
        .catch(error => console.log(error))
}

function afficheMeteo(data) {
    
    document.getElementById('imgMeteoJour').src = data.current_condition.icon;
    console.log(data.current_condition.icon)
    document.getElementById('titreVille').src = data.city_info.name;
    console.log(data.city_info.name)
    document.getElementById('temperature1').src = data.current_condition.tmp;
    console.log(data.current_condition.tmp)

}
