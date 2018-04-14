const checkEmail = (email) => {
    const regEx = new RegExp(/^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/);
    return regEx.test(email);
};
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
const customerSignIn = () => {
    const data = {action: 'CSIGNIN'};
    data.email = $('.dropdown-login').find('input').eq(0).val().trim();
    if(!checkEmail(data.email)) {
        alert('Ange användarnamn!');
        $('.dropdown-login').find('input').eq(0).val('')
    }
    
    data.password = $('.dropdown-login').find('input').eq(1).val().trim();
    if(data.password.length < 6) {
        alert('Ange lösenordet! 6 bokstäver!');
        $('.dropdown-login').find('input').eq(1).val('')
    }
    
    $.post('./server/customer.php', data, (res) => {
        if(res == 1){
        window.location.reload();
        }else{alert('E-postadress eller lösenord är fel!')}       
    });

};
const customerSignOut = () => {
    const data = {action: 'CSIGNOUT'};
    data.email = $('.dropdown-login').find('input').eq(0).val().trim(); 
    
    $.post('./server/customer.php',data , (res) => {
    window.location.reload();    
    });

};

