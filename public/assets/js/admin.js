const path = window.location.href;
const menuLinks = document.querySelectorAll('.sidebar-sticky ul li a');
const notes = document.querySelectorAll('svg.feather-bookmark');
const alertBoxes = document.querySelectorAll('.alert.alert-success');
const fileInputs = document.querySelectorAll('input[type=file]');


menuLinks.forEach(link => {
    if (link.href == path) {
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});

notes.forEach(note => {
    note.parentElement.addEventListener('click', (event) => {
        const noteGrandParent = note.parentElement.parentElement;
        const form = noteGrandParent.childNodes[3];
        const textarea = form.childNodes[1];

        note.parentElement.remove();

        const submitButton = document.createElement("button");
        submitButton.setAttribute('type', 'submit');
        submitButton.setAttribute('class', 'mt-1 btn btn-sm btn-dark float-right');
        submitButton.innerText = 'Send';
        form.appendChild(submitButton);

        const cancelButton = document.createElement("a");
        cancelButton.setAttribute('class', 'mt-1 btn btn-sm btn-dark float-left');
        cancelButton.setAttribute('href', path);
        cancelButton.innerText = 'Cancel';
        form.appendChild(cancelButton);

        textarea.style.display = "block";
    });
});

alertBoxes.forEach(alertBox => {
    if (alertBox.innerText != '') {
        setTimeout(() => {
            alertBox.remove();
        }, 2500);
    }
});

fileInputs.forEach(fileInput => {
    fileInput.addEventListener('change', (event) => {
        // Get file name and then replace the name of the label with it
        const fileName = event.target.files[0].name;
        event.target.previousElementSibling.innerText = fileName;
    });
});
