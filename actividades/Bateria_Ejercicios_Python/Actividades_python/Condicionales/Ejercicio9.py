temperatura=int(input("Temperatura en Grados Celsius: "))

if temperatura >= 30:
    print("Hace calor")
elif temperatura >= 10 and temperatura < 30:
    print("El clima es templado")
else:
    print("Hace frio")

