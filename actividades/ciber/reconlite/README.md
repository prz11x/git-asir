README.md 
# ReconLite (proyecto resuelto) – Python + requests con mentalidad ciber

## Objetivo
Hacer reconocimiento HTTP/OSINT de forma ligera y responsable:
- Cabeceras de seguridad (HSTS, CSP, etc.)
- Fingerprinting básico por headers
- Enumeración “suave” de rutas típicas
- Reporte JSON para evidencias

> Úsalo solo contra objetivos autorizados.

## Instalación
```bash
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

## Uso básico
```bash
python reconlite.py https://example.com
```

## Cambiar User-Agent
```bash
python reconlite.py example.com --ua "Mozilla/5.0 (ReconLite class)"
```

## Añadir tu propio wordlist de rutas
Archivo `paths.txt` (una ruta por línea):
```
admin
admin/
robots.txt
api/v1
```

Ejecución:
```bash
python reconlite.py https://midominio.com --paths-file paths.txt --max-paths 200
```

## TLS
Por defecto verifica TLS.
Si estás en laboratorio con certificados raros (NO recomendado):
```bash
python reconlite.py https://lab.local --insecure
```

## Salida
Genera `report.json` con:
- Config usada
- HEAD base con headers
- Seguridad de headers
- Listado de rutas con status, tiempos, redirects y content-type
```

---

## Ejemplos de ejecución (para clase)
~~~bash
# 1) Recon rápido
python reconlite.py https://testphp.vulnweb.com

# 2) Enumeración más grande con tu lista
python reconlite.py https://testphp.vulnweb.com --paths-file paths.txt --max-paths 150

# 3) Cambiar timeout (objetivos lentos)
python reconlite.py https://testphp.vulnweb.com --timeout 15
