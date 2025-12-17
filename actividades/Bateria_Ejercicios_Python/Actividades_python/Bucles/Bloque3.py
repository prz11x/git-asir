print("7. Dada una cadena, cuenta cuántas vocales contiene.")

cadena = "Python es genial"


contador=0

for i in cadena:
    if i in "aeiouAEIOU":
        contador=contador+1

print ("numero vocales: ", contador)



print("8. Invierte una cadena usando un bucle.")

cadena = "Hola Mundo"

cadena_invertida = ""

for caracter in cadena:
    cadena_invertida = caracter + cadena_invertida

print(cadena_invertida)

print("9. Elimina los espacios de una cadena y genera una nueva sin ellos.")

cadena = "Hola a todos"
cadena_sin_espacios = ""

for caracter in cadena:
    if caracter != " ":
        cadena_sin_espacios += caracter

print(cadena_sin_espacios)