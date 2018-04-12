function Subscribe() {
    var data = {};
    
    data.name = $('#subscribeName').val().trim();
    if(data.name == '') {
        alert('Ange ditt namn!');
        return $('#subscribeName').focus();
    }
    
    data.email = $('#subscribeEmail').val();
    if(data.email == '') {
        alert('Ange din e-postadress!');
        return $('#subscribeEmail').focus();
    }
    
    $.post('./server/subscriber.php', data, function(res) {
        if(res == 'DUPLICATE') {
            alert('E-postadressen finns redan!');
            $('#subscribeEmail').focus();
            
        } else if(res == 'EMAIL') {
            alert('Fel format e-postadress!');
            $('#subscribeEmail').focus();       
                 
        } else if(res == 'NAME') {
            alert('Ange ditt namn!');
            $('#subscribeName').focus();
                
        } else if(res == 'SUCCESS') {
            alert('Tack för din prenumeration!');
            $('#subscribeName, #subscribeEmail').val('');
                      
        } else alert('Ett fel uppstod!');
    });
};