const placeOrder = () => {
    var data = {action: 'ORDER', company: $('#company').val().trim()};
    
    data.name = $('#name').val().trim();
    if(data.name == '') {
        alert('Ange ditt namn!');
        return $('#name').focus();
    }
    
    data.phone = $('#phone').val().trim();
    if(data.phone == '') {
        alert('Ange ditt telefonnummer!');
        return $('#phone').focus();
    }
    
    data.email = $('#email').val();
    if(!checkEmail(data.email)) {
        alert('Ange din e-postadress!');
        return $('#email').focus();
    }
    
    data.address = $('#address').val().trim();
    if(data.address == '') {
        alert('Ange din adress!');
        return $('#address').focus();
    }
    
    data.zipcode = $('#zipcode').val().trim();
    if(data.zipcode == '') {
        alert('Ange ditt postnummer!');
        return $('#zipcode').focus();
    }
    
    data.city = $('#city').val().trim();
    if(data.city == '') {
        alert('Ange din stad!');
        return $('#city').focus();
    }
    
    data.newsletter = $('#newsletter').prop('checked') ? 1 : 0;
    
    data.shipperID = $('input[name=shipping]:checked').val();
    if(!data.shipperID) {
        return alert('välj en fraktmetod!');
    }
    
    $.post('./server/cart.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else {
            let html = '<h2>Tack för din beställning. Din ordernummer är '+res.orderID+'</h2>';
            if(res.password) {
                html += '<h2>Vi har skapat ett konto till dig. <br/>Du kan logga in i ditt konto med din e-postadress och ';
                html += '<span style="color: red;">'+res.password+'</span> som lösenord</h2>';
            }
            $('.row').html(html);
        }
    });
};

const updateCart = (prodID, element) => {
    const data = {action: 'UPDATE', prodID: prodID};
    data.quantity = ($(element).attr('id') == 'delete') ? 0 : $(element).val();

    $.post('./server/cart.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) {
            alert(res.error);
            window.location.reload();
            
        } else {
            if($(element).attr('id') == 'delete') $(element).closest('.product').remove();
            $('#antal').html(res.total);
            $('#price').html(res.price+' SEK');
        }
    });
};

const setShipper = (shipperID) => {
    $.post('./server/cart.php', {action: 'SHIPPER', shipperID: shipperID}, (res) => {
        res = JSON.parse(res);
        if(res.error) {
            if(res.error == 'EMPTY') window.location = './';
            else alert(res.error);            
        } else if(res.total) $('#price').html(res.total+' SEK');
    });
};

const addToCart = (prodID) => {
    $.post('./server/cart.php', {action: 'ADD', prodID: prodID}, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else if(res.quantity) {            
            $('.kassabtn').prop('href', './checkout.php');
            $('.kassabtn small').html(" ("+res.quantity+")");
        }
    });
};

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
    if(!checkEmail(data.email)) {
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
    const data = {action: 'SIGNIN'};
    data.email = $('.dropdown-login').find('input').eq(0).val().trim();
    if(!checkEmail(data.email)) {
        alert('Ange användarnamn!');
        return $('.dropdown-login').find('input').eq(0).val('')
    }
    
    data.password = $('.dropdown-login').find('input').eq(1).val().trim();
    if(data.password.length < 6) {
        alert('Ange lösenordet! 6 bokstäver!');
        return $('.dropdown-login').find('input').eq(1).val('')
    }
    
    $.post('./server/customer.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else window.location.reload();       
    });

};

const customerSignOut = () => {
    const data = {action: 'SIGNOUT'};
    
    $.post('./server/customer.php', data, (res) => {
        window.location.reload();    
    });
};

const changeState = () => {
    const data = {action: 'changeState'};
    data.orderID = $('.status').attr('id'); 
    $.post('./server/customer.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        window.location.reload(); 
    });
};