const path = window.location.href;
const menuLinks = document.querySelectorAll('.sidebar-sticky ul li a');
const notes = document.querySelectorAll('svg.feather-bookmark');

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

        const submitButton = document.createElement("button");
        submitButton.setAttribute('type', 'submit');
        submitButton.innerText = 'Send';

        const idHidden = document.createElement("input");
        idHidden.setAttribute('type', 'hidden')
        idHidden.setAttribute('name', 'id')
        idHidden.setAttribute('value', noteGrandParent.getAttribute('data-id'))

        form.appendChild(idHidden);
        form.appendChild(textarea);
        form.appendChild(submitButton);
        noteGrandParent.appendChild(form);
    });
});
