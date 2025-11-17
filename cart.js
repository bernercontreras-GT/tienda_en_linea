//Propósito: Funcionalidad JavaScript para interactuar con cart_actions.php

// Función para agregar un producto al carrito
function addtoCart(id) {
  fetch("cart_actions.php", {
    method: "POST",
    body: new URLSearchParams({
      action: "add",
      product_id: id,
      qty: 1,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      alert("Producto agregado al carrito");
      console.log("Carrito Actualizado", data);
    })
    .catch((error) => console.error("Error al agregar al carrito", error));
}

//funcion para limpiar/vaciar el carrito de compra (cancelar compra)
function clearCart() {
  if (
    confirm(
      "¿Estás seguro de que quieres cancelar la compra y vaciar el carrito?"
    )
  ) {
    fetch("cart_actions.php", {
      method: "POST",
      body: new URLSearchParams({
        action: "clear",
      }),
    })
      .then(() => {
        window.location.reload();
      })
      .catch((error) => console.error("Error al vaciar carrito", error));
  }
}

///FUncion eliminar del carrito
function deleteFromCart(id) {
  if (
    confirm("¿Estás seguro de que deseas eliminar este producto del carrito?")
  ) {
    fetch("cart_actions.php", {
      method: "POST",
      body: new URLSearchParams({
        action: "remove", // La acción "remove" la maneja cart_actions.php
        product_id: id,
      }),
    })
      .then(() => {
        alert("Producto eliminado.");
        // Recargamos la página para que se actualice la tabla y el total
        window.location.reload();
      })
      .catch((error) => console.error("Error al eliminar del carrito", error));
  }
}

function imprimirRecibo() {
  window.print();
}
