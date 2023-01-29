/* -------------------------------------------------------------------------- */
/*                           Bootstrap Toast Message                          */
/* -------------------------------------------------------------------------- */


const toastLiveExample = document.getElementById('liveToast');

if (toastLiveExample) {
    document.addEventListener("DOMContentLoaded", function () {
        const toast = new bootstrap.Toast(toastLiveExample);
        toast.show();
    })

}

/* -------------------------------------------------------------------------- */
/*              Random Pastel Color Generator For Profile Picture             */
/* -------------------------------------------------------------------------- */

var hue = Math.floor(Math.random() * 360);

[...document.getElementsByClassName('profile')].forEach(change => {
    change.style.backgroundColor = "hsl(" + hue + ", 100%, 80%)"
});


/* -------------------------------------------------------------------------- */
/*                      Edit User Page | Update Card Info                     */
/* -------------------------------------------------------------------------- */

let cardName = document.getElementById("editCardName")
let cardEmail = document.getElementById("editCardEmail")
let cardSelect = document.getElementById("cardSelect")

let inputName = document.getElementById("editInputNames")
let inputEmail = document.getElementById("editInputEmail")
let editSelect = document.getElementById("editSelect")

if (inputName) {
    inputName.addEventListener("input", function () {
        cardName.innerHTML = inputName.value
    })
}

if (inputEmail) {
    inputEmail.addEventListener("input", function () {
        cardEmail.innerHTML = inputEmail.value
    })
}

if (editSelect) {
    editSelect.addEventListener("change", function () {
        let selectedValue = editSelect.value

        if (selectedValue == 'admin') {
            cardSelect.classList.add("text-bg-danger")
            cardSelect.classList.remove("text-bg-primary")
            cardSelect.classList.remove("text-bg-warning")
            cardSelect.classList.remove("text-bg-success")
            cardSelect.innerHTML = "Admin"
        } else if (selectedValue == 'moderator') {
            cardSelect.classList.add("text-bg-primary")
            cardSelect.classList.remove("text-bg-danger")
            cardSelect.classList.remove("text-bg-warning")
            cardSelect.classList.remove("text-bg-success")
            cardSelect.innerHTML = "Moderator"
        } else if (selectedValue == 'user') {
            cardSelect.classList.add("text-bg-success")
            cardSelect.classList.remove("text-bg-primary")
            cardSelect.classList.remove("text-bg-warning")
            cardSelect.classList.remove("text-bg-danger")
            cardSelect.innerHTML = "User"
        }

    })
}

/* -------------------------------------------------------------------------- */
/*                            Replace URL Function                            */
/* -------------------------------------------------------------------------- */

switch (location.pathname) {
    case '/edit-user':
    case '/edit-post':
        break;
    case '/post':
        let currentUrl = new URL(location.href);
        currentUrl.searchParams.forEach((value, key) => {
            if(key !== "id") currentUrl.searchParams.delete(key);
        });
        history.replaceState(null, null, currentUrl.href);
        break;
    default:
        if (location.search) {
            history.replaceState(null, null, location.pathname);
        }
}