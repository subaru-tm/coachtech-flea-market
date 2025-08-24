const edit_message_btn = document.getElementById('message__edit');
const update_message_btn = document.getElementById('message__update');

edit_message_btn.addEventListener('click', (e) => {
    e.target.style.display = "none";
    update_message_btn.style.display = "unset";
    const input = document.getElementById('message__mine');
    input.readOnly = false;
    input.focus();
});


