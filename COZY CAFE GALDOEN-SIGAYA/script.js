// script.js - small helpers
document.addEventListener('click', function(e){
  if(e.target.matches('.order-now')){
    // If user not logged in, server will redirect, but we can show a message client-side
    alert('Please login first to place orders.');
  }
});

function addToCart(productId) {
    fetch("add_to_cart.php?id=" + productId)
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                alert("Added to cart!");
            }
        });
}
