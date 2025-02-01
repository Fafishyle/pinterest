
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formInscription").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        const email = document.getElementById('email').value;
        const motDePasse = document.getElementById('pass').value;
        console.log("Inscription détectée");

        fetch(
            'https://git.heroku.com/pinterest-backend.git/backend/inscription.php'
            ,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email, motDePasse: motDePasse })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Inscription réussie !');
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});


// Pour la connexion
document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();
    console.log("Connexion détectée")
    const email = document.getElementById('email').value;
    const motDePasse = document.getElementById('pass').value;

    fetch('https://ton-backend.herokuapp.com/connexion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email, motDePasse: motDePasse })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Connexion réussie !');
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => console.error('Erreur:', error));
});
