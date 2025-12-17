print("13. Imprime los números del 1 al 10 usando un bucle while.")

n=1

while n in range(1,11):
    
    print(n)

    n=n+1


print("14. Solicita al usuario números hasta que introduzca un 0, luego calcula la suma de los números ingresados.")

suma = 0

while True:
    numero = int(input("Introduce un número (0 para terminar): "))
    
    if numero == 0:
        break  
    
    suma += numero  

print("La suma total es:", suma)

print("15. Dado un número, encuentra su factorial usando un bucle while.")
numero = 5
factorial = 1
i = 1

while i <= numero:
    factorial *= i  
    i += 1          
print("El factorial de", numero, "es:", factorial)