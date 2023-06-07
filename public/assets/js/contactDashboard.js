window.onload = () => {
    let supprimer = document.querySelectorAll(".deleteContactBtn")
    for (let button of supprimer){
        button.addEventListener("click", function() {
            document.querySelector(".modal-footer a").href = `/admin/messages/${this.dataset.id}/supprimer`
            document.querySelector(".modal-body").innerText = `Êtes-vous sûr de vouloir supprimer le message de "${this.dataset.name}" ?`
        })
    }
}