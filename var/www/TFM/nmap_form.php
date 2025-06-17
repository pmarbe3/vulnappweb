<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Escaneo de Red - Nmap</title>
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
      font-size: 1.6rem;
      color: var(--uoc-blue);
      margin: 0;
    }

    main {
      padding: 2rem;
      max-width: 600px;
      margin: 0 auto;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-top: 2rem;
    }

    .subinfo {
      margin-bottom: 1rem;
      font-size: 1rem;
      color: var(--text-color);
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    label {
      text-align: left;
      color: var(--text-color);
      font-weight: bold;
    }

    input[type="text"] {
      padding: 0.8rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      padding: 1rem;
      background-color: var(--uoc-blue);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }

    button:hover {
      background-color: #0077a3;
    }

    .back-link {
      margin-top: 1rem;
      display: block;
      text-align: center;
      color: var(--uoc-blue);
      text-decoration: none;
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
    <div class="subinfo"><strong>Escaneo de red con Nmap</strong></div>

    <form action="nmap_handler.php" method="post">
      <label for="subnet">Introduce una red en formato CIDR (ej. 192.168.1.0/24):</label>
      <input type="text" id="subnet" name="subnet" required pattern="^(\d{1,3}\.){3}\d{1,3}/\d{1,2}$"
             title="Formato válido: 192.168.1.0/24">

      <button type="submit">Iniciar escaneo</button>
    </form>

    <a href="index.php" class="back-link">⬅ Volver al menú principal</a>
  </main>

  <footer>
    &copy; <?php echo date("Y"); ?> Pablo Martínez Bernal · TFM · UOC
  </footer>

</body>
</html>
