//CUSTOM JS
$('#userEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userEditId').val(user.id);
    modal.find('#userEditName').text(user.name);
    modal.find('#userEditRole').val(user.role);
});

$('#userEditModalAjax').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userEditIdAjax').val(user.id);
    modal.find('#userEditNameAjax').text(user.name);
    modal.find('#userEditRoleAjax').val(user.role);
});

$('#userDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data('user');

    let modal = $(this);

    modal.find('#userDeleteId').val(user.id);
    modal.find('#userDeleteName').text(user.name);
});

// $('#boardAddModal').on('shown.bs.modal', function(event) {
//     let button = $(event.relatedTarget); // Button that triggered the modal
//     //let board = button.data('board');

//     let modal = $(this);

//     //modal.find('#boardEditId').val(board.id);
//     modal.find('#boardAddName').val(board.name);

//     modal.find('#boardAddName').trigger('change');
// });

$('#boardEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let board = button.data('board');

    let modal = $(this);

    modal.find('#boardEditId').val(board.id);
    modal.find('#boardEditName').val(board.name);

    let usersSelected = [];

    board.board_users.forEach(function(boardUser) {
        usersSelected.push(boardUser.user_id);
    });

    modal.find('#boardEditUsers').val(usersSelected);
    modal.find('#boardEditUsers').trigger('change');
});

$('#boardDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let board = button.data('board');

    let modal = $(this);

    modal.find('#boardDeleteId').val(board.id);
    modal.find('#boardDeleteName').text(board.name);
});

// $('#taskAddModal').on('shown.bs.modal', function(event) {
//     let button = $(event.relatedTarget); // Button that triggered the modal
//     let task = button.data('task');

//     let modal = $(this);

//     modal.find('#taskAddId').val(task.id);
//     modal.find('#taskAddName').val(task.name);
//     modal.find('#taskAddDescription').text(task.description);
//     modal.find('#taskAddAssignment').val(task.assignment ? task.assignment : '');
//     //modal.find('#taskEditStatus').val(task.status);
// });

$('#taskEditModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');

    let modal = $(this);

    modal.find('#taskEditId').val(task.id);
    modal.find('#taskEditName').val(task.name);
    modal.find('#taskEditDescription').text(task.description);
    modal.find('#taskEditAssignment').val(task.assignment ? task.assignment : '');
    modal.find('#taskEditStatus').val(task.status);
});

$('#taskDeleteModal').on('shown.bs.modal', function(event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data('task');

    let modal = $(this);

    modal.find('#taskDeleteId').val(task.id);
    modal.find('#taskDeleteName').text(task.name);
});

$(document).ready(function() {
    $('#userEditButtonAjax').on('click', function() {
        $('#userEditAlert').addClass('hidden');

        let id = $('#userEditIdAjax').val();
        let role = $('#userEditRoleAjax').val();

        $.ajax({
            method: 'POST',
            url: '/user-update/' + id,
            data: {role: role}
        }).done(function(response) {
            if (response.error !== '') {
                $('#userEditAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#userDeleteButton').on('click', function() {
        $('#userDeleteAlert').addClass('hidden');
        let id = $('#userDeleteId').val();

        $.ajax({
            method: 'POST',
            url: '/user/delete/' + id
        }).done(function(response) {
            if (response.error !== '') {
                $('#userDeleteAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#changeBoard').on('change', function() {
        let id = $(this).val();

        window.location.href = '/board/' + id;
    });

    $('#boardEditUsers').select2();

    $('#boardAddButton').on('click', function() {
        $('#boardAddAlert').addClass('hidden');

        //let id = $('#boardEditId').val();
        let name = $('#boardAddName').val();
        //let user =
        //let members = 1;
        
        $.ajax({
            method: 'POST',
            url: '/board/add',
            data: {name}
        }).done(function(response) {
            if (response.error !== '') {
                $('#boardAddAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#boardEditButton').on('click', function() {
        $('#boardEditAlert').addClass('hidden');

        let id = $('#boardEditId').val();
        let name = $('#boardEditName').val();
        let boardUsersData = $('#boardEditUsers').select2('data');

        let boardUsers = [];

        boardUsersData.forEach(function(item) {
            boardUsers.push(item.id);
        });

        $.ajax({
            method: 'POST',
            url: '/board/update/' + id,
            data: {name, boardUsers}
        }).done(function(response) {
            if (response.error !== '') {
                $('#boardEditAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#boardDeleteButton').on('click', function() {
        $('#boardDeleteAlert').addClass('hidden');
        let id = $('#boardDeleteId').val();

        $.ajax({
            method: 'POST',
            url: '/board/delete/' + id
        }).done(function(response) {
            if (response.error !== '') {
                $('#boardDeleteAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#taskAddButton').on('click', function() {
        $('#taskAddAlert').addClass('hidden');

        //let id = $('#taskAddId').val();
        let name = $('#taskAddName').val();
        let description = $('#taskAddDescription').val();
        let assignment = $('#taskAddAssignment').val();
        let boardId = $('#changeBoard').val();
        //let status = $('#taskAddStatus').val();

        $.ajax({
            method: 'POST',
            url: '/task/add',
            data: {name, description, assignment, boardId}
        }).done(function(response) {
            if (response.error !== '') {
                $('#taskAddAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#taskEditButton').on('click', function() {
        $('#taskEditAlert').addClass('hidden');

        let id = $('#taskEditId').val();
        let name = $('#taskEditName').val();
        let description = $('#taskEditDescription').text();
        let assignment = $('#taskEditAssignment').val();
        let status = $('#taskEditStatus').val();

        $.ajax({
            method: 'POST',
            url: '/task/update/' + id,
            data: {name, description, assignment, status}
        }).done(function(response) {
            if (response.error !== '') {
                $('#taskEditAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

    $('#taskDeleteButton').on('click', function() {
        $('#taskDeleteAlert').addClass('hidden');
        let id = $('#taskDeleteId').val();

        $.ajax({
            method: 'POST',
            url: '/task/delete/' + id
        }).done(function(response) {
            if (response.error !== '') {
                $('#taskDeleteAlert').text(response.error).removeClass('hidden');
            } else {
                window.location.reload();
            }
        });
    });

});
