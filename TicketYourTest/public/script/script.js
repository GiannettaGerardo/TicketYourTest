window.onload = function () {

    const editButton = document.getElementById("editProfileButton");
    const confirmEditButton = document.getElementById("confirmEditButton");

    //rilevo il click sull'icona di modifica
    editButton.addEventListener("click", () => {

        var editableField = document.getElementsByClassName("editableField");

        for (field of editableField) {

            //rendo editabili i campi
            field.setAttribute("contenteditable", "true");
            field.classList.add("editMode")

            //aggiungo il pulsante di conferma e nascondo l'icona di modifica
            confirmEditButton.classList.remove("hiddenDisplay");
            editButton.classList.add("hiddenDisplay");
        }

    });


     //rilevo il click sull'icona di conferma
     confirmEditButton.addEventListener("click", () => {

        var editableField = document.getElementsByClassName("editableField");

        for (field of editableField) {

            //rendo editabili i campi
            field.setAttribute("contenteditable", "false");
            field.classList.remove("editMode")

            //aggiungo il pulsante di conferma e nascondo l'icona di modifica
            confirmEditButton.classList.add("hiddenDisplay");
            editButton.classList.remove("hiddenDisplay");
        }

        sendData();

    });
}


function sendData(){
    //code....
}