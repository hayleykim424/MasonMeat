

$(document).ready(
  () => {
    $('#login-form').on('submit',
      (event) => {
        event.preventDefault();
        //$(event.target).addClass('was-validated');
        let cred = $('#credentials').val();
        let pass = $('#password').val();
        //since we are only logging in, we only need to check if the fields are empty
        if( cred.length == 0 || pass.length == 0 ){
          //if form is empty, add class 'was-validated' (bootstrap form validation)
          $(event.target).addClass('was-validated');
        }
        //create a data object
        let loginData = {credentials: cred, password: pass};
        console.log(loginData);
        //add spinner
        let spinner = '<img class="spinner" src="/images/graphics/spinner.gif">';
        $('button[name="login"]').append(spinner);
        //make ajax request with data
        $.ajax({
          url: '/ajax/login.ajax.php',
          method: 'post',
          dataType: 'json',
          data: loginData
        })
        .done( (response) => {
          $(event.target).find('.spinner').remove();
          if( response.success == false ){
            //login failed
            console.log(response.errors);
            //check errors -- there are two errors, either account does not exist or password is wrong
            if(response.errors){
              $('#credentials,#password').addClass('is-invalid');
            }
            //notify user
          }
          else{
            //redirect user to a page
            window.location.href='index.php';
          }
        });
        
      }
    );
    $('#login-form').on('input',(event) => {
      //remove error messages when user 
      $('#login-form').find('input').removeClass('is-invalid');
    });
  }
);

