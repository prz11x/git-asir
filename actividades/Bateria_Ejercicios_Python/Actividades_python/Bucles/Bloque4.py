print ("11. Usa range para imprimir los números del 1 al 10. ")

for i in range(1, 11): 
    print(i)

print ("12. Usa range para generar una tabla de multiplicar (por ejemplo, la tabla del 5).")


numero = 5
for i in range (0, 11):
    print(numero, "*", i, ":" , (numero*i) )




print ("13. Usa enumerate para imprimir el índice y el valor de cada elemento en una lista. ")
frutas = ["manzana", "banana", "pera"]

for i,fruta in enumerate(frutas):
    print (i, fruta)