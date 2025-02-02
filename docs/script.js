
//_______________ INSCRIPTION _______________
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formInscription").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        const email = document.getElementById('email').value;
        const motDePasse = document.getElementById('pass').value;
        const confirmationMotDePasse = document.getElementById('pass2').value;

        fetch(
            //'https://git.heroku.com/pinterest-backend.git/inscription.php'
            'https://pinterest-backend-a55546f8898e.herokuapp.com/backend/inscription.php'
            //'postgres://uc6roibm5k3en1:p3939911c6d834d6448c9fbb4897aea2ee286bd2572d7999e0f8f36fe5753849e@c8m0261h0c7idk.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com:5432/d6m1ssvjvankel'
            ,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                email: email,
                password: motDePasse,
                confirmation : confirmationMotDePasse
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === '201') {
                alert('Inscription réussie !');
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});


//_______________ CONNEXION _______________
document.getElementById('formConnexion').addEventListener('submit', function(event) {
    event.preventDefault();
    console.log("Connexion détectée")
    const email = document.getElementById('email').value;
    const motDePasse = document.getElementById('pass').value;

    fetch(
        //'https://ton-backend.herokuapp.com/connexion.php'
        'https://pinterest-backend-a55546f8898e.herokuapp.com/backend/connexion.php'
        , {
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
