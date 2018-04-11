const uploadImage = (file) => {
    $('#image').html('<i class="fa fa-spinner fa-spin"></i>');    
    fetch('../server/upload.php', {
        method: 'PUT', credentials: 'include', body: file
    }).then(response => response.json()).then((res) => {
        if(res.image) $('#image').html('<img src="'+res.image+'" />');
        else if(res.error) $('#image').html('<i class="fa fa-exclamation-triangle"></i><br /><span>'+res.error+'</span>');
        else $('#image').html('<i class="fa fa-exclamation-triangle"></i><br /><span>Ett fel uppstod!</span>'); 
    }).catch(error => $('#image').html('<i class="fa fa-exclamation-triangle"></i><br /><span>Ett fel uppstod!</span>'));
};