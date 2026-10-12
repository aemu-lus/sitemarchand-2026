
<div class="container">
    <h1>Page de contact</h1>
    <div class="row">
        <div class="mb-3">
            <label for="login" class="form-label">Nom :</label>
            <input type="text" class="form-control" id="nom">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Prenom :</label>
            <input type="text" class="form-control" id="prenom">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Email :</label>
            <input type="text" class="form-control" id="mail">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Téléphone :</label>
            <input type="text" class="form-control" id="Tel">
        </div>

        <div id="messageErreur" class="erreur"></div><br>

        <button type="button" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill" onclick="verifierSaisie()">Valider</button>
    </div>
  