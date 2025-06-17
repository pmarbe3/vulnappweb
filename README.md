# 🔍 Analizador de Vulnerabilidades de Servidores Web

Aplicación web desarrollada como Trabajo Final de Máster (TFM) para la asignatura **M1.888 - Análisis de Datos** en la **Universitat Oberta de Catalunya (UOC)**.  
Permite realizar auditorías básicas de seguridad sobre servidores web, combinando escaneos de red y análisis de vulnerabilidades, y visualización mediante la pila ELK (Elasticsearch, Logstash, Kibana).

---

## 📌 Funcionalidades principales

- 🔎 **Descubrimiento de hosts activos** en una red específica mediante Nmap (`-sn`)
- 🔓 **Escaneo de puertos abiertos y versiones** por IP (`-sS -sV`)
- 🕷️ **Análisis de vulnerabilidades web** usando OWASP ZAP en modo daemon/API
- 📁 **Generación automática de informes PDF**
- 📊 **Visualización centralizada de logs** en Kibana mediante Logstash

---

## 🛠️ Tecnologías utilizadas

- **Frontend:** PHP + HTML/CSS (formulario y visualización)
- **Backend:** Nmap, OWASP ZAP, bash
- **Visualización:** Elasticsearch + Logstash + Kibana
- **Logs estructurados:** JSON y TXT
- **Informes:** DomPDF (PDF)

---

## ⚙️ Estructura del proyecto

```
/var/www/TFM/
├── index.php               # Página principal
├── nmap_form.php           # Formulario para escanear red
├── nmap_handler.php        # Ejecuta Nmap y analiza resultados
├── zap_form.php            # Formulario para escaneo con ZAP
├── zap_handler.php         # Lanza ZAP, genera alertas
├── generar_pdf.php         # Exporta informe PDF detallado
├── logs/
│   ├── nmap/               # Salidas Nmap (.txt, .json)
│   └── zap/                # Alertas ZAP (.json)
├── informes/               # Informes PDF generados
├── img/                    # Logo UOC y otros recursos
└── utils/                  # Parsers auxiliares
```

---

## 📊 Dashboards recomendados en Kibana

- **Vulnerabilidades por IP** (pie o barras)
- **Tipos de riesgo detectado** (`High`, `Medium`, etc.)
- **Puertos abiertos por host**
- **Histórico de alertas** con `@timestamp`

---

## 🧪 Requisitos

- Parrot OS o cualquier Linux con Apache, PHP y permisos `sudo`
- Nmap
- OWASP ZAP (modo daemon)
- Stack ELK instalado y configurado
- Composer (para instalar DomPDF)

---

## 🚀 Ejecución

1. Clonar el proyecto en `/var/www/TFM`
2. Dar permisos a la carpeta `logs/`:
   ```bash
   sudo chown -R www-data:www-data logs
   sudo chmod -R 775 logs
   ```
3. Ejecutar OWASP ZAP en modo daemon:
   ```bash
   zap.sh -daemon -port 8090 -config api.disablekey=true
   ```
4. Abrir la aplicación:
   ```
   http://localhost/TFM
   ```
5. Configurar Logstash para enviar los `.json` a Elasticsearch
6. Ver datos en Kibana (por defecto: `http://localhost:5601`)

---

## 📦 Instalación de dependencias

```bash
cd /var/www/TFM
composer require dompdf/dompdf
```

---

## 📚 Autor

**Pablo Martínez Bernal**  
TFM - Universitat Oberta de Catalunya  
Junio 2025

---

## 🛡️ Aviso

Este proyecto tiene fines exclusivamente educativos. No debe usarse para análisis de redes sin autorización explícita.  
