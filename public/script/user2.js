var UserManager = {
    // Returns the url of the application server of a demo web app.
    basePath: function () { return baseUrl+'/backend'; },
    // Loads the list of categories requested from the app server via ajax request.
    loadRoles: function () {
        $.ajax({
            url: this.basePath() + '/roles/list',
            dataType: 'json',
            cache: false,
            success: function (data) {
                //$('<option>').text('Select Role').attr('value', '').appendTo($('#role-id'))
                $.each(data, function (index, role) {
                    $('<option>').text(role.name)
                        .attr('value', role.id).appendTo($('#role-id'));
                })
            }
        });
    },
    // Shows a list of users in <select> element
    showDatatables: function () {
        $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: this.basePath()+'/users/data',
            columns: [
                {data: 'name', name: 'users.name'},
                {data: 'email', name: 'users.email'},
                {data: 'role', name: 'roles.name'},
                {data: 'created_at', name: 'users.created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ]
        });
    },
    // Shows a form with user details,
    showDetails: function (id) {
        if (id == null) return;
        this.clearForm();
        $.ajax({
            url: this.basePath() + '/users/' + id +"/edit",
            cache: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType: 'json',
            success: function (result) {
                $('#modal-user-label').html('Edit User');
                $('#form-modal').modal('show');
                $('[name="user_id"]').attr('value', result.id);
                $('[name="name"]').attr('value', result.name);
                $('[name="email"]').attr('value', result.email);
                var value = []
                result.roles.forEach(function(item){
                  value.push(item.id);
                });
                $('[name="role_id"]').val(value);
            }
        });
    },
    // Show a delete form
    showDeleteConfirmation: function (id) {
        if (id == null) return;
        $('#delete-form').attr('action', this.basePath() + '/users/' + id);
        $('#delete-modal').modal('show');
    },
    // Returns back to table.
    back: function () {
        $('#user-modal').modal('hide');
        $('#delete-modal').modal('hide');
    },
    // Refreshes the search result.
    refreshSearch: function () {
        this.showDatatables().draw();
    },
    // Formats a URI of a user
    createUrl: function (requestType) {
        if (requestType == 'POST')
            return this.basePath() + '/users';
        return this.basePath() + '/users/' + encodeURIComponent($('[name="id"]').val());
    },
    // Creates an object with the properties retrieved from the input fields
    // of the "User" form.
    collectFieldValues: function () {
        //return $('user-form').serialize();
        //or manually
        return {
            name: $('#name').val(),
            email: $('#email').val(),
            roles: $('#role-id').val(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val()
        };
    },
    // Deletes a users.
    deleteData: function () {
        $("#delete-form").ajaxSubmit({
            beforeSubmit: function (data, form, options) {
                console.log('aktif');
            },
            success: function (result) {
                if (result.errors)
                    alert(result.errors[0].message);
                else{
                    UserManager.refreshSearch();
                    UserManager.back();
                }
            },
            error: function(request, status, error){
                console.log(error);
            }
        })
    },
    // Shows the user form with blank values.
    clearForm: function () {
        $('#user-form').trigger("reset");
    },
    showCreate: function () {
        this.clearForm();
        $('#modal-user-label').html('New User');
        $('#form-modal').modal('show');
    },
    // Saves a user. If there is no value in the #user-id hidden field then
    // a new user is created by "POST" request. Otherwise an existing user
    // is updated with "PUT" request.
    save: function () {
        //if (!confirm('Save?')) return
        var requestType = $('#user-id').val() != '' ? 'PUT' : 'POST';
        $.ajax({
            type: requestType,
            url: this.createUrl(requestType),
            data: UserManager.collectFieldValues(),
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            success: function (result) {
                if (result.errors)
                    alert(result.errors[0].message);
                else {
                    if (requestType == 'POST')
                        alert('ID of the new user is ' + result.id);
                    this.refreshSearch();
                }
            }
        });
    }
};

$(document).ready(function() {
    UserManager.loadRoles();
    UserManager.showDatatables();

    // attach event handlers to buttons
    $('#find-button').click(function (e) {
        e.preventDefault();
        UserManager.refreshSearch();
    });
    $(document).on("click", '.show-detail', function (e) {
        e.preventDefault();
        UserManager.showDetails($(this).val());
    });
    $('#back-button').click(function (e) {
        e.preventDefault();
        UserManager.back();
    });
    $('#create-button').click(function (e) {
        e.preventDefault();
        UserManager.showCreate();
    });
    $('#save-button').click(function (e) {
        e.preventDefault();
        UserManager.save();
    });
    $(document).on("click", '.show-delete', function (e) {
        e.preventDefault();
        UserManager.showDeleteConfirmation($(this).data('id'));
    });
    $('#confirm-delete-botton').click(function (e) {
        e.preventDefault();
        UserManager.deleteData();
    });
});