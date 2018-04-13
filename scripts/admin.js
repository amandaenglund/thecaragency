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
            current = res.current; total = res.total;
            $('.browser').find('label').eq(2).html(current+'/'+total);
            $('.browser').show();
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
    if(isNaN(data.acceleration) || (data.acceleration <= 0) || (data.acceleration >= 10)) { 
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
