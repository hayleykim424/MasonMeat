// This file has changed so errors are now shown using Bootstrap's built in classes
//read http://getbootstrap.com/docs/4.1/components/forms/#validation

function removeAlerts() {
  //remove all alerts
  $('.alert').remove();
}

function removeAlert(event) {
  //remove alert from particular inputs
  $(event.target).parents('.form-group').find('.alert').remove();
}

function showAlert(templateId,type,forElement,message){
  //get reference to the template
  let template = $('#'+templateId).html().trim();
  let clone = $(template);
  $(clone).addClass('alert-' + type);
  $(clone).find('.alert-message').text(message);
  //$(forElement).parents('.form-group').append(clone);
  $('.'+forElement).append(clone);
}

function showInvalid(inputelm,msg){
  let targetinput = 'input[name="'+inputelm+'"]';
  //add bootstrap validation class and change message
  $(targetinput).addClass('is-invalid');
  $(targetinput).parents('.form-group').find('.invalid-feedback').text(msg);
}

function validateForm(form_elm) {
  //simple form validation
  let inputs = $(form_elm).find('input');
  //check each input after converting into an array
  Array.from(inputs).forEach((input) => {
    
  });
}
$(document).ready(
  () => {
    $('#register-form').on('change', (event) => { 
      removeAlert(event); 
      $(event.target).removeClass('was-validated');
    });
    $('#register-form').on('submit', (event) => {
      event.preventDefault();
      //remove invalid classes
      $(event.target).find('input').removeClass('is-invalid');
      //validateForm(event.target);
      let username = $('input[name="username"]').val();
      let email = $('input[name="email"]').val();
      let password = $('input[name="password"]').val();
      if(username.length == 0 || email.length == 0 || password.length == 0){
        $(event.target).addClass('was-validated');
      }
      let registerdata = { username: username, email: email, password: password };
      console.log(registerdata);
      //add spinner to button
      let spinner = '<img class="spinner" src="/images/graphics/spinner1.gif">';
      $('button[name="register-btn"]').append(spinner);
      $('button[name="register-btn"]').attr('disabled', '');
      $.ajax({
          url: '/ajax/register.ajax.php',
          method: 'post',
          dataType: 'json',
          data: registerdata
        })
        .done((response) => {
          //remove spinner from button
          $('button[name="register-btn"] img').remove();
          // remove all alerts
          removeAlerts();
          if (response.success == false) {
            //check for errors in different fields
            if (response.errors.username) {
              showAlert('alert-template','warning','alert-username',response.errors.username);
              showInvalid('username',response.errors.username);
            }
            if (response.errors.email) {
              showAlert('alert-template','warning','alert-email',response.errors.email);
              showInvalid('email',response.errors.email);
            }
            if (response.errors.password) {
              showAlert('alert-template','warning','alert-password',response.errors.password);
              showInvalid('password',response.errors.password);
            }
          }
          else{
            //if registration is successful
            showAlert('alert-template', 'success','alert-success-div','account registration successful');
            //to clear all the fields
            $('#register-form').trigger('reset');
          }
          $('button[name="register-btn"]').removeAttr('disabled');
        });
    });
  }
);
