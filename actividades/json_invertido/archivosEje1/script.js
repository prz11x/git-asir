// Simulación de carga de un JSON externo (esto es lo que los alumnos deben crear)
function leer() {
    fetch('productos.json')
        .then(response => response.json())
        .then(productos => {
            const lista = document.getElementById('lista-productos');

            productos.forEach((producto, index) => {
                const item = document.createElement('li');
                item.textContent = `${index + 1}. ${producto.nombre} - $${producto.precio} - Stock: ${producto.stock}`;
                lista.appendChild(item);
            });
        })
        .catch(error => console.error("Error al cargar el JSON:", error));
}