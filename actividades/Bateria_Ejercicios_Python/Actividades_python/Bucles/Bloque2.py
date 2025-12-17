print ("4. Dado un diccionario de productos y precios, imprime cada producto junto con su precio.")

productos = {"manzana": 1.5, "banana": 0.8, "leche": 2.3}

print(productos.items())

for key,value in productos.items():
    print(key,value)



print ("5. Encuentra la suma total de los precios de los productos.")

suma=0

for key,value in productos.items():
    suma=suma+value
    print(value, "suma: ", suma)



print ("6. Crea una lista de productos cuyo precio sea mayor que un valor dado (por ejemplo, 1.0).")

lista=[]

for p,v in productos.items():
    if v > 1.0:
        lista.append(p)

print (lista)

