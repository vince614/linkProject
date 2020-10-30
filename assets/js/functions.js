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
        icon: 'warning',
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
                    type: 'deleteLink',
                    code: code
                },
                success: function () {
                    location.reload();
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
                url: hostUrl + "/dashboard",
                data: {
                    type: 'changeLinkTitle',
                    code: code,
                    title: newTitle
                },
                success: function () {
                    location.reload();
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