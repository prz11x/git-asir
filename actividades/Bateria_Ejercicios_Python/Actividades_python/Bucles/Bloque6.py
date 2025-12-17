print("16. Simula una contraseña: solicita al usuario que introduzca una contraseña correcta hasta que lo haga bien. ")

contrasena_correcta = "python123"



while True:
    
    contraseña=input("Dame la contraseña: ")
    if contraseña == contrasena_correcta:
        print("Contraseña correcta")
        break
    else:
        print("Contraseña incorrecta. Intentalo de nuevo.")




print("17.Genera números aleatorios entre 1 y 10 hasta que se genere un número mayor que 8.")

import random

while True:
    numero = random.randint(1, 10)
    print("Número generado:", numero)
    if numero > 8:
        print("Número mayor que 8 encontrado:", numero)
        break


print("18.Crea un programa que permita al usuario adivinar un número secreto entre 1 y 20. Termina cuando el usuario lo adivine o escriba salir")


import random

numero_secreto = random.randint(1, 20)

while True:
    intento = input("Adivina el número (1-20) o escribe 'salir': ")
    
    if intento.lower() == "salir":
        print("Has salido del juego.")
        break
    
    if intento.isdigit():
        intento = int(intento)
        if intento == numero_secreto:
            print("¡Correcto! Has adivinado el número.")
            break
        elif intento < numero_secreto:
            print("Demasiado bajo.")
        else:
            print("Demasiado alto.")
    else:
        print("Introduce un número válido o 'salir'.")