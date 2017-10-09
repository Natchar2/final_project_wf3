url = document.location.href;

token = url.split('reset_password-')[1];

alert(token);

$('.inject-token').val(token);