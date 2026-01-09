function ajax() {
    try {
        req = new XMLHttpRequest();
    } catch(err1) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (err2) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (err3) {
                req = false;
            }
        }
    }
    return req;
}

var borrar = new ajax();
function borrarFigura(id) {

   if(confirm("¿Seguro que deseas eliminar la figura de la BD?")) {
       var myurl = 'llamadas/borrarFigura.php';
       myRand = parseInt(Math.random() * 999999999999999);
       modurl = myurl + '?rand=' + myRand + '&id=' + id;
       borrar.open("GET", modurl, true);
       borrar.onreadystatechange = borrarFiguraResponse;
       borrar.send(null);
   }

}

function borrarFiguraResponse() {

    if (borrar.readyState == 4) {
        if(borrar.status == 200) {

           var listaFiguras = borrar.responseText;
           //document.getElementsByClassName('lista')[0].innerHTML = listaFiguras;
           document.getElementById('lista').innerHTML =  listaFiguras;
        }
    }
}