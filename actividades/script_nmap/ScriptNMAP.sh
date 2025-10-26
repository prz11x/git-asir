#!/bin/bash

check_nmap() {
  if ! command -v nmap >/dev/null 2>&1; then
    echo "nmap no está instalado. Instala con: sudo apt update && sudo apt install -y nmap"
    exit 1
  fi
}

check_sudo() {
  if [ "$(id -u)" -ne 0 ]; then
    echo "AVISO: Algunas opciones funcionan mejor con sudo."
    echo "Puedes ejecutar este script con sudo o introducir la contraseña cuando se solicite."
    echo
  fi
}

read_target_and_outdir() {
  read -r -p "Introduce objetivo (IP o hostname): " TARGET
  if [ -z "${TARGET:-}" ]; then
    echo "Objetivo no especificado. Abortando."
    return 1
  fi
  read -r -p "Directorio para resultados (por defecto ./nmap_results): " OUTDIR
  OUTDIR=${OUTDIR:-./nmap_results}
  mkdir -p "$OUTDIR"
  TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
  return 0
}

ping_scan() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/ping_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap -sn "$TARGET" -oN "$OUTFILE"

  if grep -qE "0 hosts up|Host seems down" "$OUTFILE"; then
    echo "Ping estándar no detectó host. Intentando ARP (-PR)..."
    OUTFILE_ARP="$OUTDIR/ping_arp_${TARGET}_${TIMESTAMP}.txt"
    sudo nmap -sn -PR "$TARGET" -oN "$OUTFILE_ARP"

    if ! grep -qE "0 hosts up|Host seems down" "$OUTFILE_ARP"; then
      echo "ARP detectó el host. Resultado: $OUTFILE_ARP"
      return
    fi

    echo "ARP tampoco detectó host. Haciendo SYN rápido (top 20)..."
    OUTFILE_SYN="$OUTDIR/ping_synquick_${TARGET}_${TIMESTAMP}.txt"
    sudo nmap -sS --top-ports 20 -T4 -v "$TARGET" -oN "$OUTFILE_SYN"

    if grep -qE "Host is up|open" "$OUTFILE_SYN"; then
      echo "SYN encontró puertos abiertos -> host up. Resultado: $OUTFILE_SYN"
      return
    else
      echo "Host puede estar caído o filtrado."
      echo "Resultados: $OUTFILE, $OUTFILE_ARP, $OUTFILE_SYN"
      return
    fi
  else
    echo "Host detectado. Resultado: $OUTFILE"
  fi
}

tcp_syn_top() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/syn_top_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap -sS --top-ports 100 -T4 -v "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

tcp_connect_ports() {
  read_target_and_outdir || return
  read -r -p "Introduce puertos (p.ej. 22,80,443 o 1-1024) [por defecto 1-1024]: " PORTS
  PORTS=${PORTS:-1-1024}
  OUTFILE="$OUTDIR/connect_${TARGET}_${TIMESTAMP}.txt"
  nmap -sT -p "$PORTS" -v "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

service_version() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/service_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap -sV -sC -p- -T4 "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

os_detection() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/os_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap -O -v "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

udp_scan() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/udp_${TARGET}_${TIMESTAMP}.txt"
  echo "Escaneo UDP (lento). Solo en laboratorio..."
  sudo nmap -sU -p- -T3 "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

aggressive_scan() {
  read_target_and_outdir || return
  OUTFILE="$OUTDIR/aggressive_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap -A -p- -T4 "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

nse_vuln_scan() {
  read_target_and_outdir || return
  read -r -p "Introduce categoría/script (ej: vuln,http-vuln*). Por defecto 'vuln': " NSE
  NSE=${NSE:-vuln}
  OUTFILE="$OUTDIR/nse_${NSE}_${TARGET}_${TIMESTAMP}.txt"
  sudo nmap --script="$NSE" -p- -T4 "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

example_output_formats() {
  read_target_and_outdir || return
  OUTPREFIX="$OUTDIR/output_${TARGET}_${TIMESTAMP}"
  nmap -sV --top-ports 50 "$TARGET" -oN "${OUTPREFIX}.nmap" -oX "${OUTPREFIX}.xml" -oG "${OUTPREFIX}.gnmap"
  echo "Resultados: ${OUTPREFIX}.nmap, ${OUTPREFIX}.xml, ${OUTPREFIX}.gnmap"
}

no_ping_scan() {
  read_target_and_outdir || return
  read -r -p "Introduce puertos (por defecto --top-ports 100): " PORTS
  PORTS=${PORTS:---top-ports 100}
  OUTFILE="$OUTDIR/noping_${TARGET}_${TIMESTAMP}.txt"
  nmap -Pn $PORTS -T4 "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

custom_nmap() {
  read -r -p "Introduce opciones para nmap (ej: -sV --top-ports 50): " OPTS
  if [ -z "${OPTS:-}" ]; then
    echo "No se proporcionaron opciones. Abortando."
    return 1
  fi
  read -r -p "Introduce objetivo (IP o hostname): " TARGET
  if [ -z "${TARGET:-}" ]; then
    echo "Objetivo no especificado. Abortando."
    return 1
  fi
  read -r -p "Directorio para resultados (por defecto ./nmap_results): " OUTDIR
  OUTDIR=${OUTDIR:-./nmap_results}
  mkdir -p "$OUTDIR"
  TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
  OUTFILE="$OUTDIR/custom_${TARGET}_${TIMESTAMP}.txt"

  read -r -p "¿Confirmas ejecución de: nmap $OPTS $TARGET ? [s/N]: " CONF
  CONF=${CONF,,}
  if [ "$CONF" != "s" ] && [ "$CONF" != "si" ] && [ "$CONF" != "y" ]; then
    echo "Ejecución cancelada."
    return
  fi

  nmap $OPTS "$TARGET" -oN "$OUTFILE"
  echo "Resultado: $OUTFILE"
}

exit_script() {
  echo "Saliendo..."
  exit 0
}

usage_note() {
  cat <<EOF

IMPORTANTE (ÉTICA Y LEGAL):
- Solo escanea equipos y redes que sean de tu propiedad o con permiso explícito.
- El escaneo puede ser detectado por IDS/IPS y puede tener consecuencias.
- No uses estos comandos en redes públicas sin autorización.

EOF
}

main_menu() {
  while true; do
    clear
    echo "========================================"
    echo "       NMAP HELPER - MENÚ EDUCATIVO     "
    echo "========================================"
    echo "1) Ping scan (descubrir hosts vivos)"
    echo "2) TCP SYN top 100 (--top-ports 100 -sS)"
    echo "3) TCP connect scan y puertos personalizados (-sT -p)"
    echo "4) Detección de servicios y versiones (-sV -sC)"
    echo "5) Detección de SO (-O)"
    echo "6) Escaneo UDP (-sU)"
    echo "7) Escaneo agresivo (-A)"
    echo "8) NSE scripts (--script)"
    echo "9) Salida en formatos (-oN, -oX, -oG)"
    echo "10) Escaneo sin ping (-Pn)"
    echo "11) Crea tu propia opción para nmap"
    echo "12) Salir"
    echo "========================================"
    read -r -p "Elige opción [1-12]: " opt

    case "$opt" in
      1) ping_scan; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      2) tcp_syn_top; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      3) tcp_connect_ports; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      4) service_version; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      5) os_detection; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      6) udp_scan; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      7) aggressive_scan; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      8) nse_vuln_scan; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      9) example_output_formats; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      10) no_ping_scan; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      11) custom_nmap; usage_note; echo; read -r -p "Pulsa cualquier tecla para continuar...";;
      12) exit_script;;
      *) echo "Opción no válida."; read -r -p "Pulsa cualquier tecla para continuar...";;
    esac
  done
}

check_nmap
check_sudo
main_menu

