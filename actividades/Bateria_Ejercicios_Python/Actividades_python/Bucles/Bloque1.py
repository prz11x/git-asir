print("Bloque 1")

print("1. Dada una lista de números, calcula la suma de todos los elementos")

numeros = [1, 2, 3, 4, 5]
suma=0
for n in numeros:
    suma=suma+n
    print (suma)


print("2.Dada una lista de nombres, imprime cada nombre en mayúsculas.")

nombres = ["ana", "luis", "carlos"]

for nombre in nombres:
    print (nombre.upper())



print("3. Crea una nueva lista que contenga solo los números pares de una lista dada.")
numeros = [10, 15, 20, 25, 30]
pares=[]

for n in numeros:
    if n % 2 == 0:
        pares.append(n)

print (pares)