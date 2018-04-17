<?php
    require('../classes/database.php');
    require('../classes/admin.php');
    
    $admin = new Admin();
    if($admin->isSignedIn()) die(header('Location: ./home.php'));
    else $admin->signOut();
?>
<!doctype html>
<html lang="sv"><head>
<meta charset="utf-8" />
<link rel="stylesheet" href="../styles/admin.css"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://use.fontawesome.com/917257e2ef.js"></script>
<script>
    $(document).on('keypress', 'input', function(event){ 
        if(event.which == 13) $(this).parent('div').parent('div').children('.button').click();
    });

    const checkEmail = (email) => {
        const regEx = new RegExp(/^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/);
        return regEx.test(email);
    };
    
    const createAccount = () => {
        const data = {action: 'CREATE'};
        data.name = $('#signup').find('input').eq(0).val().trim();
        if(data.name == '') {
            alert('Ange ditt namn!');
            return $('#signup').find('input').eq(0).focus();
        }
        
        data.email = $('#signup').find('input').eq(1).val().trim();
        if(!checkEmail(data.email)) {
            alert('Ange din e-postadress!');
            return $('#signup').find('input').eq(1).focus();
        }
        
        data.password = $('#signup').find('input').eq(2).val().trim();
        if(data.password.length < 4) {
            alert('Ange ditt lösenord! Minst 4 bokstäver!');
            return $('#signup').find('input').eq(2).focus();
        }
        
        if(data.password != $('#signup').find('input').eq(3).val().trim()) {
            alert('Upprepade lösenordet är fel!');
            return $('#signup').find('input').eq(2).focus();
        }
        
        $.post('../server/admins.php', data, (res) => {
            res = JSON.parse(res);
            if(res.error) alert(res.error);
            else {
                alert('Ditt konto skapades!');
                window.location.reload();
            }
        });
    };
    
    const signIn = () => {
        const data = {action: 'SIGNIN'};
        data.email = $('#signin').find('input').eq(0).val().trim();
        if(!checkEmail(data.email)) {
            alert('Ange användarnamn!');
            return $('#signin').find('input').eq(0).focus();
        }
        
        data.password = $('#signin').find('input').eq(1).val().trim();
        if(data.password.length < 4) {
            alert('Ange lösenordet! Minst 4 bokstäver!');
            return $('#signin').find('input').eq(1).focus();
        }
        
        $.post('../server/admins.php', data, (res) => {
            res = JSON.parse(res);
            if(res.error) alert(res.error);
            else window.location = './home.php';
        });
    };
</script>
<title>The Car Agency - Administration</title>
</head><body>
    <div class="container">
        <div class="header">
            <a href="./"><img src="../images/knesla_logo.png" /><span>KNESLA</span></a>
            <label onclick="{$('.content>div').hide(); $('#signup').show();}"><i class="fa fa-user-plus"></i><span>Skapa konto</span></label>
            <label onclick="{$('.content>div').hide(); $('#signin').show();}"><i class="fa fa-sign-in"></i><span>Logga in</span></label>
        </div>
        <div class="content">
            <div id="signin">
                <h3 class="center">Logga in</h3>
                <div class="input">
                    <label>Användarnam</label>
                    <input name="email" type="email" placeholder="E-postadress" />
                </div>
                <div class="input">
                    <label>Lösenord</label>
                    <input name="password" type="password" placeholder="Lösenord" />
                </div>
                <div class="button" onclick="signIn()">Logga in</div>
            </div>
            <div id="signup">
                <h3 class="center">Skapa konto</h3>
                <div class="input">
                    <label>Namn</label>
                    <input type="text" placeholder="Namn" />
                </div>
                <div class="input">
                    <label>E-postadress</label>
                    <input type="email" placeholder="E-postadress" />
                </div>
                <div class="input">
                    <label>Lösenord</label>
                    <input type="password" placeholder="Lösenord" />
                </div>
                <div class="input">
                    <label>Upprepa lösenordet</label>
                    <input type="password" placeholder="Lösenord" />
                </div>
                <div class="button" onclick="createAccount()">Skapa konto</div>
            </div>
        </div>
        <div class="footer">&copy; The Car Agency - Sverige <?=date('Y');?></div>
    </div>
</body></html>