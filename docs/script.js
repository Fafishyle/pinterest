document.getElementById('formInscription').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const motDePasse = document.getElementById('motDePasse').value;

    // Envoie des données à l'API de l'inscription (backend sur Heroku)
    fetch('https://ton-backend.herokuapp.com/inscription.php', {
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

// Pour la connexion
document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();

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
