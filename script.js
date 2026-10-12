function verifierSaisie() {
    let login = document.getElementById("login").value;
    let password = document.getElementById("password").value;
    let check = document.getElementById("check").checked;
    let liste = document.getElementById("liste").value;
    let message = document.getElementById("messageErreur");

    if (login.length < 7) {
        message.textContent = "Le login doit contenir au moins 7 caractères.";
    } else if (password === "") {
        message.textContent = "Le mot de passe ne peut pas être vide.";
    } else if (!check){
        message.textContent = "la connexion n'est pas sécurisé.";
    } else if (liste != "2"){
        message.textContent = "le domaine n'est pas le bon.";
    } else {
        alert("Connexion reussie");
        location.href = "admin.html";
    }
    
}