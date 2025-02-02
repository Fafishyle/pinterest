
//_______________ INSCRIPTION _______________
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formInscription").addEventListener("submit", function(event) {
        event.preventDefault(); // Empêche le rechargement de la page
        const email = document.getElementById('email').value;
        const motDePasse = document.getElementById('pass').value;
        const confirmationMotDePasse = document.getElementById('pass2').value;

        fetch('https://pinterest-backend-a55546f8898e.herokuapp.com/backend/inscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                email: email,
                password: motDePasse,
                confirmation: confirmationMotDePasse
            })
        })
        .then(response => {
            if (!response.ok) {
                //throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            const alertBox = document.createElement('div');
            alertBox.classList.add('alert'); // Classe CSS pour l'alerte
            alertBox.textContent = data.message || data.error; // Message d'erreur ou de succès

            // Vérifie le code de statut et applique la couleur correspondante
            if (data.error) {
                alertBox.style.backgroundColor = 'red'; // Rouge pour erreur
                alertBox.style.color = 'white';
            } else {
                alertBox.style.backgroundColor = 'green'; // Vert pour succès
                alertBox.style.color = 'white';
            }

            // Ajoute l'alerte à l'élément body ou à un autre endroit de ton choix
            document.body.appendChild(alertBox);

            // Masquer l'alerte après 5 secondes
            setTimeout(() => {
                alertBox.remove();
            }, 5000);
        })
        .catch(error => {
            const alertBox = document.createElement('div');
            alertBox.classList.add('alert');
            alertBox.textContent = 'Erreur: ' + error.message;
            alertBox.style.backgroundColor = 'red'; // Rouge en cas de problème
            alertBox.style.color = 'white';
            document.body.appendChild(alertBox);
            
            setTimeout(() => {
                alertBox.remove();
            }, 5000);
        });
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
