numero=input("Ingrese un numero del 1-7: ")
if numero in ("1", "2", "3", "4", "5", "6", "7"):
    if numero == "1":
        print("Lunes")
    elif numero == "2":
        print("Martes")
    elif numero == "3":
        print("Miércoles")
    elif numero == "4":
        print("Jueves")
    elif numero == "5":
        print("Viernes")
    elif numero == "6":
        print("Sábado")
    else:
        print("Domingo")
else:
    print("Número inválido. Ingrese un número del 1 al 7.")
