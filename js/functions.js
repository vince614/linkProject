//Function delete 
function trash(code,title){

    //Swal
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want delete " + title + " linky",
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
                url: "./functions/delete.php",
                data: {
                    code: code
                },
                success: function (data) {
        
                    //Recupérer le fichier json en objet
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if(obj.exist == true) {

                        if(obj.delete == true) {

                            //Swal
                            Swal.fire(
                                'Deleted!',
                                'Your linky has been deleted.',
                                'success'
                            )

                            //Ne plus afficher le linky
                            let td = document.getElementById(code);
                            td.style.display = 'none';

                        }

                    }else {

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
    })

}

//Function edit
function edit(code, title){

    (async () => {

        const { value: newTitle } = await Swal.fire({
          title: 'Change linky title of ' + title,
          input: 'text',
          inputPlaceholder: 'Enter new title'
        })
        
        if (newTitle) {

            //Ajax 
            $.ajax({
                type: "POST",
                url: "./functions/edit.php",
                data: {
                    code: code,
                    title: newTitle
                },
                success: function (data) {
        
                    //Recupérer le fichier json en objet
                    var obj = JSON.parse(data);
                    console.log(obj);

                    if(obj.exist == true) {

                        if(obj.edit == true) {

                            //Swal
                            Swal.fire('Entered title: ' + newTitle);

                            //Ne plus afficher le linky
                            let tr = document.getElementById('edit' + code);
                            tr.innerText = newTitle;

                        }

                    }else {

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

// This function helps to send the request to retrieve data from mysql database...
function searchData(val){
    $('#search-result-container').show();
    $('#search-result-container').html('<div><img src="preloader.gif" width="50px;" height="50px"> <span style="font-size: 20px;">Please Wait...</span></div>');
    $.post('controller.php',{'search-data': val}, function(data){
        
     if(data != "")
      $('#search-result-container').html(data);
           else    
     $('#search-result-container').html("<div class='search-result'>No Result Found...</div>");
    }).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
        
    alert(thrownError); //alert with HTTP error
            
    });
   }



