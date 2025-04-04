let cart = JSON.parse(sessionStorage.getItem("cart")) || [];
let total = parseFloat(sessionStorage.getItem("total")) || 0;

function addToCart(item, price) {
    cart.push({ item, price });
    total += price;
    updateCart();
    sessionStorage.setItem("cart", JSON.stringify(cart));
    sessionStorage.setItem("total", total);
}

function updateCart() {
    let cartTable = document.getElementById("cart-items");
    let totalPrice = document.getElementById("total-price");

    if (cartTable && totalPrice) {
        cartTable.innerHTML = "";
        cart.forEach(({ item, price }) => {
            let row = `<tr><td>${item}</td><td>₹ ${price}</td></tr>`;
            cartTable.innerHTML += row;
        });

        totalPrice.innerHTML = `Total: ₹ ${total}`;
    }
}

function proceedToOrder() {
    if (cart.length === 0) {
        alert("Your cart is empty! Add items before proceeding.");
        return;
    }

    sessionStorage.setItem("cart", JSON.stringify(cart));
    sessionStorage.setItem("total", total);

    window.location.href = "order.html";
}

function showPaymentOptions() {
    let name = document.getElementById("name").value;
    let contact = document.getElementById("contact").value;

    if (!name || !contact) {
        alert("Please enter your name and contact details.");
        return;
    }

    document.getElementById("custName").innerText = name;
    document.getElementById("custContact").innerText = contact;
    document.getElementById("orderTotal").innerText = total;
    document.getElementById("paymentSection").style.display = "block";
}

function confirmOrder(paymentMethod) {
    let name = document.getElementById("custName").innerText;
    let contact = document.getElementById("custContact").innerText;

    if (!name || !contact) {
        alert("Please enter your details.");
        return;
    }

    let products = cart.map(item => `${item.item} - ₹${item.price}`).join(", ");

    let formData = new FormData();
    formData.append("name", name);
    formData.append("contact", contact);
    formData.append("products", products);
    formData.append("total", total);
    formData.append("payment_mode", paymentMethod);

    fetch("place_order.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        document.getElementById("orderMessage").innerText =
            paymentMethod === "cash" ? "Order placed" : "Payment successful and Order placed";

        // Clear cart after order is placed
        sessionStorage.removeItem("cart");
        sessionStorage.removeItem("total");
    })
    .catch(error => console.error("Error:", error));
}

// Ensure cart updates correctly when pages load
window.onload = updateCart;
