$(document).ready( () => {
  
  const spinner = `<img class="icon spinner" src="/images/graphics/spinner.png">`;
  const check = `<img class="icon check" src="/images/graphics/spinner.png">`;
  //get the shopping list and render it
  const content = getCartContents();

  //shopping list listener
  //listen for click on shopping cart list and update or delete item(s)
  $('#shopping-list').click( (event) => {
    let target = event.target;
    let action = $(target).data('action');
    if( action == 'update' ){
      $(target).append(spinner);
      let itemId = $(target).data('item-id');
      let pId = $(target).data('product-id');
      let qty = $(target).parents('.shopping-list-form').find('input[name="quantity"]').val();
      let updateData = {productId : pId, quantity: qty, action: 'update' };
      getCartData( updateData, (response) => {
        if( response.success == true ){
          $('.spinner').remove();
          //$(target).text('Updated')
          $(target).append(check);
          
        }
      });
    }
    
    //listen for plus and minus buttons
    const btnFunction = $(target).data('function');
    const targetInput = $(target).parents('form').find('input[name="quantity"]');
    let quantity = $(targetInput).val();
    if( btnFunction == 'add' ){
      quantity++;
      $(targetInput).val( quantity );
    } 
    if( btnFunction == 'subtract' ){
      quantity--;
      quantity = quantity < 1 ? 1 : quantity;
      $(targetInput).val( quantity );
    }
  });
  
});

function getCartTotal(){
  const prices = Array.from ( $('.product-price') );
  const qtys = Array.from( $('input[name="quantity"]') );
  let total = 0;
  prices.forEach( (elm, index) => {
    total += ( parseInt( $(elm).text() * $( qtys[index] ).val() ) );
  });
  return total;
}

function getCartData( cartData, callback ){
  $.ajax({
    url: '/ajax/shoppingcart.ajax.php',
    method: 'post',
    dataType: 'json',
    data: cartData
  })
  .done( (response) => {
    callback( response );
  });
}
function getCartContents(){
  const cartData = { action: 'list' };
  
  getCartData( cartData , (response) => {
    if( response.success == true ){
      response.items.forEach( (item) => {
        let itemId = item.item_id;
        let productId = item.product_id;
        let productName = item.name;
        let productPrice = item.price;
        let quantity = item.quantity;
        let image = item.image;
        let template = `<div class="row">
          <div class="col-6 col-sm-6 col-md-4">
            <img class="img-fluid" src="/images/products/${image}">
          </div>
          <div class="col-6 col-sm-6 col-md-3">
            <h5>${productName}</h5>
            <p class="product-price price">${productPrice}</p>
          </div>
          <div class="col-12 col-sm-12 col-md-5 mt-sm-2">
          <form id="shopping-list-form-${itemId}" class="shopping-list-form form-inline">
            <div class="form-row">
              <div class="col col-sm-6 col-md-6">
                <label>Qty</label>
              </div>
              <div class="col col-sm-6 col-md-6">
                <input type="hidden" name="productId" value="${productId}">
                <input type="hidden" name="itemId" value="${itemId}">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn btn-md btn-default" type="button" data-function="subtract">
                      &minus;
                    </button>
                  </div>
                  <input type="text" name="quantity" min="1" class="border-defalut form-control text-center" value="${quantity}">
                  <div class="input-group-append">
                    <button class="btn btn-md btn-default" type="button" data-function="add">
                      &plus;
                    </button>
                  </div>
                </div>
              </div>
              <div class="col col-sm-6 col-md-6 mt-sm-2 mt-md-2">
                <button type="btn btn-md btn-default" class="btn btn-outline-info btn-block" data-product-id="${productId}" data-item-id="${itemId}" data-action="update">
                  Update
                </button>
              </div>
              <div class="col col-sm-6 col-md-6 mt-sm-2 mt-md-2">
                <button type="btn btn-md btn-default" class="btn btn-outline-info btn-block" data-product-id="${productId}" data-item-id="${itemId}" data-action="delete">
                  Delete
                </button>
              </div>
            </div>
          <form>
          </div>
        </div><hr>`;
        $('#shopping-list').append(template);
      });
      
      let totalPrice = response.total;
      let totalTemplate = `<div class="row">
        <div class="col">Total</div>
        <div class="col-4">
            <div class="">$${totalPrice}
              <button class="btn btn-md btn-default checkoutBtn">Checkout</button>
            </div>
        </div>
      </div><hr>`;
      $('#shopping-list').append(totalTemplate);
      return true;
    }
    else{
      return false;
    }
  });
}