/**
 * Trash
 *
 * @param code
 * @param title
 */
function trash(code, title) {
    Swal.fire({
        title: 'Are you sure?',
        html: "Do you want delete <b>" + title + "</b> link?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "../functions/delete.php",
                data: {
                    code: code
                },
                success: function (data) {
                    let obj = JSON.parse(data);
                    if (obj.exist == true) {
                        if (obj.delete == true) {
                            Swal.fire(
                                'Deleted!',
                                'Your link has been deleted.',
                                'success'
                            );
                            let td = document.getElementById(code);
                            td.style.display = 'none';
                        }
                    } else {
                        Swal.fire(
                            'error!',
                            'Your link not exist.',
                            'error'
                        )
                    }
                }
            });
        }
    })
}

/**
 * Edit link
 *
 * @param code
 * @param title
 */
function edit(code, title) {

    (async () => {
        const {
            value: newTitle
        } = await Swal.fire({
            title: 'Change link title of ' + title,
            input: 'text',
            inputPlaceholder: 'Enter new title'
        });

        if (newTitle) {
            $.ajax({
                type: "POST",
                url: "../functions/edit.php",
                data: {
                    code: code,
                    title: newTitle
                },
                success: function (data) {
                    let obj = JSON.parse(data);
                    if (obj.exist == true) {
                        if (obj.edit == true) {
                            Swal.fire('Success!', 'New title: ' + newTitle, 'success');
                            let tr = document.getElementById('edit' + code);
                            tr.innerText = newTitle;
                        }
                    } else {
                        Swal.fire(
                            'error!',
                            'Your linky not exist.',
                            'error'
                        )
                    }
                }
            });
        }
    })()
}

//Settings 
//=================================================================

$(document).ready(function () {
    /** Submit profile **/
    $("#submitProfile").click(function () {
        let username = $('#username').val();
        $.ajax({
            type: "POST",
            url: hostUrl + "/dashboard",
            data: {
                type: 'changeUsername',
                username: username
            },
            success: function (data) {
                if (data) {
                    Swal.fire(
                        'Error !',
                        data,
                        'error'
                    )
                } else {
                    location.reload();
                }
            }
        });
    });

    /** Submit password **/
    $("#submitPassword").click(function () {
        let oldPassword = $('#oldPassword').val();
        let newPassword = $('#newPassword').val();
        let newPasswordVerif = $('#newPasswordVerif').val();
        $.ajax({
            type: "POST",
            url: hostUrl + "/dashboard",
            data: {
                type: 'changePassword',
                oldPassword: oldPassword,
                newPassword: newPassword,
                newPasswordVerif: newPasswordVerif
            },
            success: function (data) {
                if (data) {
                    Swal.fire(
                        'Error !',
                        data,
                        'error'
                    )
                } else {
                    location.reload();
                }
            }
        });
    });

    /** Delete account **/
    $('#deleteAccount').click(function () {
        Swal.fire({
            title: 'Are you sure?',
            html: "Do you want delete your account?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: hostUrl + "/dashboard",
                    data: {
                        type: 'deleteCustomer'
                    },
                    success: function () {
                        location.href = hostUrl;
                    }
                });
            }
        });
    });

    $('#createNewLinkButton').click(function () {
        let title = $('#createTitle').val();
        let url = $('#createUrlOrigin').val();
        $.ajax({
            type: "POST",
            url: hostUrl + "/dashboard",
            data: {
                type: 'addLink',
                title: title,
                url: url
            },
            success: function () {
                location.href = hostUrl + '/dashboard';
            }
        });
    })
});