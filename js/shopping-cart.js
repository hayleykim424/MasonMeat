//this function updates the count of the shopping cart or wishlist
function updateCount( tgt, count ){
  if( tgt == 'shoppingcart' ){
    $('#cart-count').text( count );
  }
  if( tgt == 'wishlist'){
    $('#wish-count').text( count );
  }
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



$(document).ready(
  () => {
    $('#shopping-form').submit(
      (event) => {
        //prevent form from refreshing page
        event.preventDefault();
      }  
    );
    $('#shopping-form').click(
      (event) => {
        //add spinner to the button
        const spinner = '<img class="icon spinner" src="/images/graphics/spinner.png">';
        //get the value of the target button either 'shoppingcart' or 'wishlist'
        let tgtVal = $(event.target).val();
        if( tgtVal == 'shoppingcart'){
          $(event.target).append(spinner);
          //disable button to prevent double clicking
          $(event.target).attr('disabled',true);
          //get qty and product_id
          const qty = $('input[name="quantity"]').val();
          const productId = $('input[name="product_id"]').val();
          
          const cartData = { quantity: qty, productId : productId, action: 'add' };
          //send cardData via ajax request\
          $.ajax({
            url: '/ajax/shoppingcart.ajax.php',
            method: 'post',
            dataType: 'json',
            data: cartData
          })
          .done((response) => {
            console.log(response);
            if(response.success){
              //successful
              // update the count in navbar
              //use tgtVal to tell wishlist and cart apart
              updateCount( tgtVal, response.cart_count );
              //remove the spinner
              $('.spinner').remove();
              //reenable the button
              $(event.target).attr('disabled',false);
              
              //if registration is successful
            showAlert('alert-template', 'success','alert-success-div','item added to your shopping cart!');
              
            }
            else{
              //unsuccessful
              //read response data
              //the script shoppingcart.ajax.php will send redirect in the response
              let dest = response.redirect;
              //get the product id that was sent
              let productId = response.product_id;
              //get the quantity that was sent
              let quantity = response.quantity;
              //this action will be used in the login.js to determine whether to add the product to the shopping cart or wishlist
              let action = 'shoppingcart';
              //redirect to destination page (login.php) and pass the productId and quantity as url parameters so they can be added after login
              let url = `/${dest}?productId=${productId}&quantity=${quantity}&action=${action}`;
              //redirect user to destination page (url) if they are not logged in while adding item to shopping cart or wishlist.
              (dest) ? window.location.href = url : window.location.href='index.php';
            }
          });
          
        }
        if( tgtVal == 'wishlist' ){
          $(event.target).append(spinner);
          //disable button to prevent double clicking
          $(event.target).attr('disabled',true);
          
          //get product id
          const productId = $('input[name="product_id"]').val();
          //create data object to send via ajax
          const wishData = { productId : productId, action: 'add' };
          
          //send data via ajax request\
          $.ajax({
            url: '/ajax/wishlist.ajax.php',
            method: 'post',
            dataType: 'json',
            data: wishData
          })
          .done((response) => {
            if(response.success){
              //successful
            }
            else{
              //if error because user is not logged in, redirect to login with url parameters
            }
          });
        }
      }  
    );
  }  
);
