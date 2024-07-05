<<<<<<< HEAD
// toast target element
=======
// toast targer element
>>>>>>> f7bb41a4ac79e8a78d2a24c1de5b630fd6e09fa1
const toastLiveTarget = document.getElementById('liveToast'); 

const toastMessage = document.getElementById('toastMessage');

const toastHeader = document.getElementById('toastHeader');

const toastTitle = document.getElementById('toastTitle');


function showToast(success, message) {

    let toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveTarget);

    toastHeader.classList.add("border-0");
    
    if (success == true) {
        toastHeader.classList.add("text-bg-success");
        toastTitle.innerHTML = 'Success!';
        toastMessage.innerHTML = `${message}`;
    } else {
        toastHeader.classList.add("text-bg-danger");
        toastTitle.innerHTML = 'Error!';
        toastMessage.innerHTML = `${message}`;
    }

    toastBootstrap.show();

}
