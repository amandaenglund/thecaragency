const deleteProduct = () => {
    const data = {action: 'DELETE', prodID: prodID};
    if(confirm("Är du säker på att radera produkten?"))
    $.post('../server/products.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else {
            alert('Produkten raderas!');
            getProduct();
        }
    });
};

const getOrders = () => {
    $('.row > .title > span').remove();    
    const data = {action: 'GET', current: current};
    
    $.post('../server/order.php', data, (res) => {
        res = JSON.parse(res);
        $('.browser').hide();
        if(res.order) {
            updateBrowser(res.current, res.total);
            res = res.order;
            let total = 0;
            let antal = 0;
            let html  = '';
            if(res['status'] === null) {
                html  = '<span title="Skicka ordern" onclick="sendOrder(';
                html += res['orderID']+')"><i class="fa fa-send"></i></span>';
                $('.row > .title').append(html);
                html = '';
            }
            
            for(let key in res['products']) {
                html += '<div class="input props"><img src="../images/'+key+'.jpg" />';
                html += '<div><a href="../car.php?carid='+key+'">'+res['products'][key]['name']+'</a><br/>';
                html += 'Pris: '+res['products'][key]['price']+' SEK<br/>Antal: '+res['products'][key]['quantity'];
                html += '</div></div><div class="seperator"></div>';
                total += res['products'][key]['quantity'] * res['products'][key]['price'];
                antal += res['products'][key]['quantity'];
            }
            total += res['shipping']['cost'];            
            html += '<div class="input props"><span class="bold"><i class="fa fa-ship" style="font-size: 6em; width: 140px;"></i></span>';
            html += '<div>Frakt: '+res['shipping']['name']+'<br>Levereras inom: '+res['shipping']['time']+'<br>Kostnad: ';
            html += res['shipping']['cost']+' SEK</div></div><div class="seperator"></div>';
            html += '<div class="input props" style="text-align: right;"><span class="bold">Antal:</span><div>';
            html += antal+'</div><br><span class="bold">Totalpris:</span><div>'+total+' SEK</div></div>';
            $('#prodDiv > .body').html(html);
            
            html  = '<div class="input props"><span class="bold">Order-id:</span><div>'+res['orderID']+'</div></div>';
            html += '<div class="input props"><span class="bold">Status:</span><div>'+res['statusTXT']+'</div></div>';
            html += '<div class="input props"><span class="bold">Orderdatum:</span><div>'+res['orderDate']+'</div></div>';
            if(res['status'] === 0) html += '<div class="input props"><span class="bold">Skickat datum:</span><div>'+res['shipDate']+'</div></div>';
            html += '<div class="input props"><span class="bold">Kund-id:</span><div>'+res['customerID']+'</div></div>';
            html += '<div class="input props"><span class="bold">Kund namn:</span><div>'+res['customerName']+'</div></div>';
            html += '<div class="input props"><span class="bold">Leverans adress:</span><div>'+res['deliveryAddress']+'<br/>';
            html += res['deliveryPostalCode']+' '+res['deliveryCity']+'</div></div>';
            $('#orderDiv > .body').html(html); 
                       
        } else if(res.error) alert(res.error);
    });
};

const sendOrder = (orderID) => {
    const data = {action: 'SEND', orderID: orderID};
    
    $.post('../server/order.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else {
            alert('Ordern skickades!');
            window.location.reload();
        }
    });
};

const getCustomers = () => {
    $('.row > .column').remove();
    const data = {action: 'ADMIN', current: current};
    
    $.post('../server/customer.php', data, (res) => {
        res = JSON.parse(res);
        $('.browser').hide();
        if(res.customers) {
            let html = '';
            updateBrowser(res.current, res.total);
            res = res.customers;
            for(let value of res) {
                html += '<div class="column">';
                html += '<div class="title"><label>Kund-id: '+value['customerID']+'</label></div><div class="body">';
                html += '<div class="input props"><span class="bold">Namn:</span><div>'+value['contactName']+'</div></div>';
                if(value['companyName'] != '') {
                    html += '<div class="input props"><span class="bold">Företag:</span><div>'+value['companyName']+'</div></div>';
                }
                html += '<div class="input props"><span class="bold">E-post:</span><div>'+value['email']+'</div></div>';
                html += '<div class="input props"><span class="bold">Telefon:</span><div>'+value['phoneNumber']+'</div></div>';                
                html += '<div class="input props"><span class="bold">Adress:</span><div>'+value['address']+'<br/>';
                html += value['postalCode']+' '+value['city']+'</div></div>';                
                html += '</div></div>';
            }
            $('.row').append(html);
            
        } else if(res.error) alert(res.error);
    });
};

const getNewsletter = () => {
    $('#dateDiv, #subjectDiv, #bodyDiv').html('');
    const data = {action: 'GET', current: current};
    
    $.post('../server/newsletter.php', data, (res) => {
        res = JSON.parse(res);
        $('.browser').hide();
        if(res.newsletter) {
            updateBrowser(res.current, res.total);
            res = res.newsletter;
            $('#dateDiv').html(res['date']);
            $('#subjectDiv').html(res['subject']);
            $('#bodyDiv').html(res['body']);
            
        } else if(res.error) alert(res.error);
    });
};

const sendNewsletter = () => {
    const data = {action: 'SEND'};
    
    data.subject = $('#subject').val().trim();
    if(data.subject == '') { alert('Ange ämnet!'); return $('#subject').focus(); }
    
    data.body = $('#texten').val().trim().replace(/\n/g, '<br/>');
    if(data.body == '') { alert('Ange texten!'); return $('#texten').focus(); }
    
    $.post('../server/newsletter.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else {
            alert('Nyhetsbrev skickades!');
            window.location.reload();
        }
    });
};

const approveUsers = () => {
    const data = {action: 'APPROVE'};
    
    let temp = [];
    $('#admins').find('input').each(function(){
        if($(this).is(':checked')) temp[temp.length] = $(this).val();
    });
    if(!temp.length) return;
    data.admins = temp;

    $.post('../server/admins.php', data, (res) => {
        res = JSON.parse(res);
        if(res.error) alert(res.error);
        else alert('Konton aktiverades!');
        window.location.reload();
    });
};

const updateProduct = () => {
    const product = checkProduct();
    if(product === false) return;
    product.action = 'EDIT';
    product.prodID = prodID;

    $.post('../server/products.php', product, (res) => {
        res = JSON.parse(res);
        if(res.success) {
            alert('Ändringar sparades!');
            getProduct();
        } else if(res.error) {
            alert(res.error);
        } else alert('Ett fel uppstod!');
    });
};

const getProduct = () => {
    $('#image').html('');
    $('#properties > .title span').text('');
    $('#categories').children('input').each(function(){ $(this).prop('checked', false); });
    $('#name, #year, #price, #battery, #maxspeed, #acceleration, #quantity, #description textarea').val('');
    
    const data = {action: 'GET', current: current};
    
    $.post('../server/products.php', data, (res) => {
        res = JSON.parse(res);
        $('.browser').hide();
        if(res.product) {
            updateBrowser(res.current, res.total);
            res = res.product;
            prodID = res['productID'];
            $('#image').html('<img src="../images/'+prodID+'.jpg?'+parseInt(10000000000000*Math.random())+'" />');
            $('#properties > .title span').text('(ID: '+prodID+')');

            for(let value of res['categories']) {
                $('#categories').children('input[value='+value+']').prop('checked', true);
            }
            
            $('#name').val(res['name']);
            $('#year').val(res['modelYear']);
            $('#price').val(res['price']);
            $('#battery').val(res['battery']);
            $('#maxspeed').val(res['maxSpeed']);
            $('#quantity').val(res['unitsInStock']);
            $('#acceleration').val(res['acceleration']);
            $('#description textarea').val(res['description'].replace(/<br\/>/g, '\n'));
            
        } else if(res.error) alert(res.error);
    });
};

const updateBrowser = (crrnt, ttl) => {
    current = crrnt; total = ttl;
    $('.browser').find('label').eq(2).html(current+'/'+total);
    if(total > 1) $('.browser').show();
    if(current == 1) {
        $('.browser').find('i').eq(0).removeClass('show').addClass('hide');
        $('.browser').find('i').eq(1).removeClass('show').addClass('hide');
    } else {
        $('.browser').find('i').eq(0).removeClass('hide').addClass('show');
        $('.browser').find('i').eq(1).removeClass('hide').addClass('show');
    }
    if(current == total) {
        $('.browser').find('i').eq(2).removeClass('show').addClass('hide');
        $('.browser').find('i').eq(3).removeClass('show').addClass('hide');
    } else {
        $('.browser').find('i').eq(2).removeClass('hide').addClass('show');
        $('.browser').find('i').eq(3).removeClass('hide').addClass('show');
    }
};

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

const checkProduct = () => {
    const data = {};
    
    if(!$('#image').children('img').length) { alert('Ladda upp bilden!'); return false; }
    else data.image = $('#image').children('img').attr('src').split('?')[0];
    
    let temp = [];
    $('#categories').children('input').each(function(){
        if($(this).is(':checked')) temp[temp.length] = $(this).val();
    });
    if(temp.length) data.categories = temp;
    else { alert('Välj kategorier!'); return false; }
    
    data.name = $('#name').val().trim();
    if(data.name == '') { alert('Ange namnet!'); $('#name').focus(); return false;  }
    
    data.year = $('#year').val().replace(/\D/g,'');
    if(data.year.length != 4) { alert('Ange årsmodel!'); $('#year').focus(); return false;  }
    
    data.price = $('#price').val().replace(/\D/g,'');
    if(data.price == '') { alert('Ange priset!'); $('#price').focus(); return false;  }
    
    data.battery = $('#battery').val().trim();
    if(data.battery == '') { alert('Ange batteri!'); $('#battery').focus(); return false;  }
    
    data.maxspeed = $('#maxspeed').val().replace(/\D/g,'');
    if(data.maxspeed == '') { alert('Ange topphastigheten!'); $('#maxspeed').focus(); return false;  }
    
    data.acceleration = parseFloat($('#acceleration').val().replace(',', '.').replace(/[^0-9\.]/g, ''));
    if(isNaN(data.acceleration) || (data.acceleration <= 0) || (data.acceleration >= 100)) { 
        alert('Accelerationen måste vara mellan 0.1 till 99.9!'); $('#acceleration').focus(); return false; 
    }

    data.quantity = $('#quantity').val().replace(/\D/g,'');
    if(data.quantity == '') { alert('Ange antalet!'); $('#quantity').focus(); return false;  }
    
    data.description = $('#description textarea').val().trim().replace(/\n/g, '<br/>');
    if(data.description == '') { alert('Ange beskrivningen!'); $('#description').focus(); return false;  }
    
    return data;
};

const addProduct = () => {
    const product = checkProduct();
    if(product === false) return;
    else product.action = 'ADD';
    
    $.post('../server/products.php', product, (res) => {
        res = JSON.parse(res);
        if(res.success) {
            alert('Produkten skapades!');
            window.location = './edit.php';
        } else if(res.error) {
            if(isNaN(res.error)) alert(res.error);
            else {
                alert('Ett fel uppstod!');
                window.location = './edit.php';
            }
        } 
    });
};