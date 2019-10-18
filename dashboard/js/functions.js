//Function delete 
function trash(code, title) {

    //Swal
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

            //Ajax 
            $.ajax({
                type: "POST",
                url: "../functions/delete.php",
                data: {
                    code: code
                },
                success: function (data) {

                    //Recupérer le fichier json en objet
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.exist == true) {

                        if (obj.delete == true) {

                            //Swal
                            Swal.fire(
                                'Deleted!',
                                'Your link has been deleted.',
                                'success'
                            )

                            //Ne plus afficher le linky
                            let td = document.getElementById(code);
                            td.style.display = 'none';

                        }

                    } else {

                        //Swal
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

//Function edit
function edit(code, title) {

    (async () => {

        const {
            value: newTitle
        } = await Swal.fire({
            title: 'Change link title of ' + title,
            input: 'text',
            inputPlaceholder: 'Enter new title'
        })

        if (newTitle) {

            //Ajax 
            $.ajax({
                type: "POST",
                url: "../functions/edit.php",
                data: {
                    code: code,
                    title: newTitle
                },
                success: function (data) {

                    //Recupérer le fichier json en objet
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if (obj.exist == true) {

                        if (obj.edit == true) {

                            //Swal
                            Swal.fire('Success!','New title: ' + newTitle, 'success');

                            //Ne plus afficher le linky
                            let tr = document.getElementById('edit' + code);
                            tr.innerText = newTitle;

                        }

                    } else {

                        //Swal
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

//hide / show 
function hidePieChart() {

    if($('#card-pie-chart').is(':visible')) {
        $('#card-pie-chart').hide(300);
        $('#hide-show-pie').attr('title', 'Open');
        $('#hide-show-pie').attr('data-uk-icon', 'icon: plus');
    }else {
        $('#card-pie-chart').show(300);
        $('#hide-show-pie').attr('title', 'Close');
        $('#hide-show-pie').attr('data-uk-icon', 'icon: close');
    }
    

}

function hideAreaChart() {

    if($('#card-area-chart').is(':visible')) {
        $('#card-area-chart').hide(300);
        $('#hide-show-area').attr('title', 'Open');
        $('#hide-show-area').attr('data-uk-icon', 'icon: plus');
    }else {
        $('#card-area-chart').show(300);
        $('#hide-show-area').attr('title', 'Close');
        $('#hide-show-area').attr('data-uk-icon', 'icon: close');
    }
    

}

function hidePie() {

    if($('#card-pie').is(':visible')) {
        $('#card-pie').hide(300);
        $('#hide-pie').attr('title', 'Open');
        $('#hide-pie').attr('data-uk-icon', 'icon: plus');
    }else {
        $('#card-pie').show(300);
        $('#hide-pie').attr('title', 'Close');
        $('#hide-pie').attr('data-uk-icon', 'icon: close');
    }
    

}

function hideBar() {

    if($('#card-bar').is(':visible')) {
        $('#card-bar').hide(300);
        $('#hide-bar').attr('title', 'Open');
        $('#hide-bar').attr('data-uk-icon', 'icon: plus');
    }else {
        $('#card-bar').show(300);
        $('#hide-bar').attr('title', 'Close');
        $('#hide-bar').attr('data-uk-icon', 'icon: close');
    }
    

}

function copy(code) {

    var dummyContent = "https://clypy.me/" + code;
    

    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = dummyContent;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);

    UIkit.notification('<span uk-icon="icon: check"></span> Link has been copied', {status:'success', pos: 'bottom-right'})

}
