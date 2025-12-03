#!/bin/bash
# Limpiar reglas existentes
iptables -F

# Permitir tráfico desde el host
iptables -A INPUT -i eth0 -s 192.168.1.0/24 -j ACCEPT

# Bloquear todo el tráfico restante
iptables -A INPUT -j DROP
