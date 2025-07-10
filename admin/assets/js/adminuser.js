document.getElementById('searchInput').addEventListener('keyup', function() {
    let input = this.value.toLowerCase();
    let tbody = document.querySelector('table tbody');
    let rows = tbody.getElementsByTagName('tr');

    for (let row of rows) {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    }
});

// Edit button handler
function editAdmin(id, employee_id, email, acc_type, username) {
    // Set values in the edit form
    document.querySelector('[name="edit_employee_id"]').value = employee_id;
    document.querySelector('[name="edit_email"]').value = email;
    document.querySelector('[name="edit_role"]').value = acc_type;
    document.querySelector('[name="edit_username"]').value = username;

    // Add hidden input for admin ID
    let hiddenInput = document.getElementById('edit_admin_id');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'admin_id';
        hiddenInput.id = 'edit_admin_id';
        document.getElementById('editAdminForm').appendChild(hiddenInput);
    }
    hiddenInput.value = id;

    // Show the modal
    new bootstrap.Modal(document.getElementById('editAdminModal')).show();
}

// Delete button handler
function deleteAdmin(id) {
    document.getElementById('delete_admin_id').value = id;
    new bootstrap.Modal(document.getElementById('deleteAdminModal')).show();
}