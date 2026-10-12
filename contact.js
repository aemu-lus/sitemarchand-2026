function verifierSaisie() {
    let nom = document.getElementById("nom").value;
    let regexNom = /^[a-zA-Z]{1,}$/;
    let prenom = document.getElementById("prenom").value;
    let regexPrenom = /^[a-zA-Z]{1,}$/;
    let mail = document.getElementById("mail").value;
    let regexMail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let Tel = document.getElementById("Tel").value;
    let regexTel = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/g;
    let message = document.getElementById("messageErreur");

    if (!regexNom.test(nom)) {
        message.textContent = "Entrez un nom.";
    } else if (!regexPrenom.test(prenom)) {
        message.textContent = "Entrez un prenom.";
    } else if (!regexMail.test(mail)) {
        message.textContent = "Entrez une adresse mail valide.";
    } else if (!regexTel.test(Tel)) {
        message.textContent = "Entrez un numéro de téléphone valide.";
    } else {
        alert("Connexion reussie");
        location.href = "admin.html";
    }
    
}