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

const addProduct = () => {
    const data = {action: 'ADD'};
    if(!$('#image').children('img').length) return alert('Ladda upp bilden!');
    else data.image = $('#image').children('img').attr('src');
    
    let temp = [];
    $('#categories').children('input').each(function(){
        if($(this).is(':checked')) temp[temp.length] = $(this).val();
    });
    if(temp.length) data.categories = temp;
    else return alert('Välj kategorier!');
    
    data.name = $('#name').val().trim();
    if(data.name == '') { alert('Ange namnet!'); return $('#name').focus(); }
    
    data.year = $('#year').val().replace(/\D/g,'');
    if(data.year.length != 4) { alert('Ange årsmodel!'); return $('#year').focus(); }
    
    data.price = $('#price').val().replace(/\D/g,'');
    if(data.price == '') { alert('Ange priset!'); return $('#price').focus(); }
    
    data.battery = $('#battery').val().trim();
    if(data.battery == '') { alert('Ange batteri!'); return $('#battery').focus(); }
    
    data.maxspeed = $('#maxspeed').val().replace(/\D/g,'');
    if(data.maxspeed == '') { alert('Ange topphastigheten!'); return $('#maxspeed').focus(); }
    
    data.acceleration = parseFloat($('#acceleration').val().replace(',', '.').replace(/[^0-9\.]/g, ''));
    if(isNaN(data.acceleration) || (data.acceleration <= 0)) { alert('Ange acceleration!'); return $('#acceleration').focus(); }

    data.quantity = $('#quantity').val().replace(/\D/g,'');
    if(data.quantity == '') { alert('Ange antalet!'); return $('#quantity').focus(); }
    
    data.description = $('#description textarea').val().trim().replace(/\n/g, '<br/>');
    if(data.description == '') { alert('Ange beskrivningen!'); return $('#description').focus(); }

    $.post('../server/products.php', data, (res) => {
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