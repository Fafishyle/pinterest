
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
            const message = data.message || data.error; // Message d'erreur ou de succès
            if(data.error){
                showCustomAlert(message, true);
            }else{
                showCustomAlert(message, false);
            }
            
        })
        .catch(error => {
            const error_message = 'Erreur: ' + error.message;
            showCustomAlert(error_message, true);
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
            //alert('Connexion réussie !');
            showCustomAlert('Connexion réussie !', false);
            // Supprimer l'alerte
            setTimeout(() => {
                alertBox.remove();
                window.location.href = 'index.php'; // Redirection après fermeture
            }, 5000);
        } else {
            showCustomAlert('Erreur: ' + data.error, true);
        }
    })
    .catch(error => console.error('Erreur:', error));
    
    console.log("Données envoyées:", JSON.stringify({ email: courriel, password: password }));
    
});

//customiser l'alert
function showCustomAlert(message, error) {
    // Vérifie s'il y a déjà une alerte affichée
    let existingAlert = document.querySelector('.custom-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Créer un div pour l'alerte
    let alertBox = document.createElement('div');
    alertBox.classList.add('custom-alert');
    alertBox.innerHTML = `<strong>${message}</strong>`;

    // Ajouter le style CSS directement en JS
    alertBox.style.padding = '20px';
     // Vérifie le code de statut et applique la couleur correspondante
     if (error) {
        alertBox.style.backgroundColor = '#ff3f3f'; // Rouge pour erreur
    } else {
        alertBox.style.backgroundColor = '#D8BFD8'; // Violet clair
    }
    alertBox.style.color = 'rgb(89, 12, 162)'; // Bleu-violet
    alertBox.style.marginBottom = '15px';
    alertBox.style.position = 'fixed';
    alertBox.style.top = '20px';
    alertBox.style.left = '50%';
    alertBox.style.transform = 'translateX(-50%)';
    alertBox.style.border = '2px solid #8A2BE2';
    alertBox.style.borderRadius = '8px';
    alertBox.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
    alertBox.style.zIndex = '1000';

    // Ajouter un bouton pour fermer l'alerte
    let closeButton = document.createElement('span');
    closeButton.innerHTML = '&times;';
    closeButton.style.marginLeft = '10px';
    closeButton.style.cursor = 'pointer';
    closeButton.style.fontWeight = 'bold';
    closeButton.onclick = function () {
        alertBox.remove();
    };

    alertBox.appendChild(closeButton);
    document.body.appendChild(alertBox);
}

