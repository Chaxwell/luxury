const path = window.location.href;
const menuLinks = document.querySelectorAll('.sidebar-sticky ul li a');
const notes = document.querySelectorAll('svg.feather-bookmark');
const alertBoxes = document.querySelectorAll('.alert.alert-success');

menuLinks.forEach(link => {
    if (link.href == path) {
        link.classList.add('active');
    } else {
        link.classList.remove('active');
    }
});

notes.forEach(note => {
    note.addEventListener('click', (event) => {
        const noteGrandParent = note.parentElement.parentElement;
        const dataId = noteGrandParent.getAttribute('data-id');

        note.parentElement.remove();

        const form = document.createElement("form");
        form.setAttribute('action', "/admin/candidate/"+ dataId +"/note");
        form.setAttribute('method', 'POST');

        const textarea = document.createElement("textarea");
        textarea.setAttribute('name', 'note')
        textarea.setAttribute('class', 'form-control')


        const submitButton = document.createElement("button");
        submitButton.setAttribute('type', 'submit');
        submitButton.setAttribute('class', 'mt-1 float-right');
        submitButton.innerText = 'Send';

        form.appendChild(textarea);
        form.appendChild(submitButton);
        noteGrandParent.appendChild(form);
    });
});

alertBoxes.forEach(alertBox => {
    if (alertBox.innerText != '') {
        setTimeout(() => {
            alertBox.remove();
        }, 2000);
    }
});
