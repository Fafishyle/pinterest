
//_______________ INSCRIPTION _______________
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formInscription").addEventListener("submit", function(event) {
        console.log("Inscription détectée")
        event.preventDefault(); // Empêche le rechargement de la page
        const pseudo = document.getElementById('pseudo').value;
        const email = document.getElementById('email').value;
        const motDePasse = document.getElementById('pass').value;
        const confirmationMotDePasse = document.getElementById('pass2').value;

        fetch('https://pinterest-backend-a55546f8898e.herokuapp.com/backend/inscription.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                pseudo: pseudo,
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
    const courriel = document.getElementById('email').value;
    const password = document.getElementById('pass').value;
    console.log("Données envoyées au serveur:", JSON.stringify({ 
        email: courriel,
        password: password
    }));   
    fetch('https://pinterest-backend-a55546f8898e.herokuapp.com/backend/connexion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            email: courriel,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            //throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        console.log("Réponse du serveur:", data);
        if (data.status === 'success') {
            alert('Connexion réussie !');
            window.location.href = 'index.php';
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => console.error('Erreur:', error));
    
    console.log("Données envoyées:", JSON.stringify({ email: courriel, password: password }));
    
});
