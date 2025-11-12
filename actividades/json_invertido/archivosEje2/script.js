// Cargar JSON externo con pedidos
fetch('pedidos.json')
    .then(response => response.json())
    .then(pedidos => {
        const container = document.getElementById('pedidos-container');

        pedidos.forEach((pedido, index) => {
            const pedidoDiv = document.createElement('div');
            pedidoDiv.innerHTML = `
                <h2>Pedido #${index + 1}</h2>
                <p><strong>Cliente:</strong> ${pedido.cliente.nombre} (${pedido.cliente.email})</p>
                <p><strong>Dirección:</strong> ${pedido.cliente.direccion}</p>
                <h3>Productos:</h3>
                <ul>
                    ${pedido.productos.map(prod => 
                        `<li>${prod.nombre} - Cantidad: ${prod.cantidad} - Precio unitario: $${prod.precio}</li>`
                    ).join('')}
                </ul>
                <p><strong>Total:</strong> $${pedido.productos.reduce((total, prod) => total + prod.precio * prod.cantidad, 0)}</p>
                <hr>
            `;
            container.appendChild(pedidoDiv);
        });
    })
    .catch(error => console.error("Error al cargar el JSON:", error));
