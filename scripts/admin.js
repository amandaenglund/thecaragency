const uploadImage  = (file) => {
    fetch('../server/upload.php', {
        method: 'PUT', credentials: 'include',
        body: file
    }).then(
        response => response.json()
    ).then(
        success => console.log(success)
    ).catch(
        error => console.log(error)
    );
};