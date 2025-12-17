letra=input("Ingrese una letra: ")

if len(letra) == 1:
    if letra in "aeiou":
        print("La letra es una vocal.")
    else:
        print("La letra no es una vocal.")
else:
    print("No es una letra válida o es mas de una letra.")