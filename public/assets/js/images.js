// sélection de tous les liens "supprimer"
let links = document.querySelectorAll("[data-delete]");

// Boucle sur les liens
for(let link of links){
    // Écouteur d'évènement
    link.addEventListener("click", function(e){
        // Blocage du comportement par défaut du lien "supprimer"
        e.preventDefault();
        // Demande de confirmation
        if(confirm("Voulez-vous vraiment supprimer cette image ?")){
            // Envoi de la requête Ajax
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                // Les données à envoyer avec la requête
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(response => response.json())
            .then(data => {
                if(data.success){
                    this.parentElement.remove();
                }else{
                    alert(data.error);
                }
            })
        }
    })
}

