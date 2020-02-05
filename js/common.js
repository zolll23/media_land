$(function() {
    $('.ajax-form').submit(function () {
	if($('#inputAction').val()=='signin') {
	    return ajax_signin();
	}
	if($('#inputAction').val()=='signup') {
	    return ajax_signup();
	}
	return false;
    });
    $('#country').chosen();
});

function ajax_signin() {
    var email = $('#inputEmail').val();
    var pass = $('#inputPassword').val();
    jQuery.post('/signin', {
	    email:email,
	    password:pass
    }).done(function (data) {
	    if (data.status=='ok') {
		document.location.href='/home';
	    }
	    for (key in data.errors) {
		var sel = '#error_'+key;
		$(sel).html(data.errors[key].message).removeClass('d-none');
	    }
	    console.log(data);
    });
    return false;
}

function ajax_signup() {
    var email = $('#inputEmail').val();
    var pass = $('#inputPassword').val();
    jQuery.post('/signup', $( ".form-signup" ).serialize()).done(function (data) {
	    if (data.status=='ok') {
		document.location.href='/sucessful';
	    }
	    for (key in data.errors) {
		var sel = '#error_'+key;
		$(sel).html(data.errors[key].message).removeClass('d-none');
	    }
	    console.log(data);
    });
    return false;
}