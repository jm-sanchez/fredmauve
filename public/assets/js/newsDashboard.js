window.onload = () => {
    let supprimer = document.querySelectorAll(".deleteNewsBtn")
    for (let button of supprimer){
        button.addEventListener("click", function() {
            document.querySelector(".modal-footer a").href = `/admin/actualites/${this.dataset.id}/supprimer`
            document.querySelector(".modal-body").innerText = `Êtes-vous sûr de vouloir supprimer l'annonce "${this.dataset.title}" ?`
        })
    }
}