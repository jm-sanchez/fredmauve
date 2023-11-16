
// Selectionner l'élément input 
var inputElement = document.querySelector('.quantity_number');

// Désactivez les flèches d'incrémentation/décrémentation
inputElement.addEventListener('keydown', function (event) {
  if (event.keyCode === 38 || event.keyCode === 40) {
    event.preventDefault();
  }
});


// Sélectionnez le message flash 
const flashMessage = document.querySelector('.flash-message');

// Vérifiez si le message flash existe
if (flashMessage) {
    // Définissez la durée d'affichage en millisecondes (par exemple, 3000 pour 3 secondes)
    const displayDuration = 3000;

    // Supprimez le message flash après la durée spécifiée
    setTimeout(() => {
        flashMessage.remove();
    }, displayDuration);
}
