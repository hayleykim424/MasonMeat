$(document).ready( () => {
  //add listeners to plus and minus buttons
  let input = $('input[name="quantity"]');
  let quantity = $(input).val();
  //plus button
  let plus = $('button[data-function="add"]');
  plus.on("click",() => {
    quantity++;
    $(input).val(quantity);
  });
  //minus button
  let minus = $('button[data-function="subtract"]');
  minus.on("click", () => {
    quantity--;
    if(quantity < 1){
      quantity = 1;
    }
    $(input).val(quantity);
  });
});