<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Analizador de Vulnerabilidades de Servidores Web</title>
  <style>
    :root {
      --uoc-blue: #0099cc;
      --uoc-gray: #f2f2f2;
      --text-color: #333;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--uoc-gray);
      margin: 0;
      padding: 0;
    }

    header {
      background-color: white;
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      border-bottom: 3px solid var(--uoc-blue);
    }

    header img {
      height: 60px;
      margin-right: 1.5rem;
    }

    header h1 {
      font-size: 1.8rem;
      color: var(--uoc-blue);
      margin: 0;
    }

    main {
      padding: 2rem;
      text-align: center;
    }

    .subinfo {
      margin-top: 0.5rem;
      font-size: 1rem;
      color: var(--text-color);
    }

    .author {
      font-weight: bold;
      color: var(--text-color);
      margin-bottom: 2rem;
    }

    .menu {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }

    .menu a {
      text-decoration: none;
      padding: 1.2rem 2rem;
      background-color: var(--uoc-blue);
      color: white;
      border-radius: 8px;
      font-size: 1.1rem;
      transition: background-color 0.2s;
    }

    .menu a:hover {
      background-color: #0077a3;
    }

    footer {
      margin-top: 3rem;
      text-align: center;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
</head>
<body>

  <header>
    <img src="logo.png" alt="Logo UOC">
    <h1>Analizador de Vulnerabilidades de Servidores Web</h1>
  </header>

  <main>
    <div class="subinfo">Asignatura: M1.888 - TFM - Análisis de Datos · <em>Universitat Oberta de Catalunya</em></div>
    <div class="author">Autor: Pablo Martínez Bernal</div>

    <div class="menu">
      <a href="nmap_form.php">🔍 Buscar hosts activos</a>
      <a href="zap_form.php">🕷️ Analizar vulnerabilidades</a>
      <a href="generar_pdf.php">📄 Exportar informe PDF</a>
      <a href="http://192.168.232.129:5601" target="_blank">📊 Acceder a Kibana</a>
    </div>
  </main>

  <footer>
    &copy; <?php echo date("Y"); ?> Pablo Martínez Bernal · Trabajo Final de Máster UOC
  </footer>

</body>
</html>
