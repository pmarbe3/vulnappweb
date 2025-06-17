<?php
set_time_limit(300000000000);

function zap_api($endpoint) {
    $url = "http://127.0.0.1:8090" . $endpoint;
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function iniciar_escaneo($url) {
    $encoded_url = urlencode($url);
    zap_api("/JSON/core/action/accessUrl/?url=$encoded_url&followRedirects=true");
    $scan = zap_api("/JSON/ascan/action/scan/?url=$encoded_url");
    return $scan['scan'] ?? null;
}

function esperar_escaneo($scan_id) {
    do {
        sleep(2);
        $status = zap_api("/JSON/ascan/view/status/?scanId=$scan_id");
        $progress = $status['status'] ?? 0;
        echo "<p>Progreso del escaneo: $progress%</p>";
        flush();
    } while ($progress < 100);
}

function obtener_alertas($url, $timestamp) {
    $encoded_url = urlencode($url);
    $alertas = zap_api("/JSON/core/view/alerts/?baseurl=$encoded_url");
    $alertas = $alertas['alerts'] ?? [];

    $json_path = __DIR__ . "/logs/zap/zap_result_$timestamp.json";

    // Guardar cada alerta como una línea JSON (formato json_lines para Logstash)
    $fp = fopen($json_path, 'w');
    foreach ($alertas as $alerta) {
        fwrite($fp, json_encode($alerta) . PHP_EOL);
    }
    fclose($fp);

    return [$alertas, $json_path];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = $_POST['url'];
    $timestamp = date("Ymd_His");

    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
    <title>Resultado del Escaneo ZAP</title>
    <style>
      body { font-family: 'Segoe UI', sans-serif; background-color: #f2f2f2; margin: 0; padding: 0; }
      header { background-color: white; padding: 1rem 2rem; display: flex; align-items: center; border-bottom: 3px solid #0099cc; }
      header img { height: 60px; margin-right: 1.5rem; }
      header h1 { color: #0099cc; margin: 0; font-size: 1.6rem; }
      main { padding: 2rem; max-width: 800px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 2rem; }
      footer { text-align: center; font-size: 0.9rem; color: #666; margin-top: 3rem; }
      .alerta { border: 1px solid #ccc; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; }
      .alerta h3 { margin: 0; color: #cc0000; }
      .alerta p { margin: 0.5rem 0; }
      .pdf-btn { display: inline-block; margin: 1rem 0; text-align: center; }
      .pdf-btn button { background: none; border: none; cursor: pointer; }
      .pdf-btn img { height: 40px; vertical-align: middle; }
    </style></head><body>
    <header><img src='./img/logo.png' alt='Logo UOC'><h1>Resultado del Escaneo ZAP</h1></header>
    <main>";

    $scan_id = iniciar_escaneo($url);
    if ($scan_id === null) {
        echo "<p>Error al iniciar el escaneo.</p></main><footer>&copy; " . date("Y") . " Pablo Martínez Bernal · TFM · UOC</footer></body></html>";
        exit;
    }

    esperar_escaneo($scan_id);
    list($alertas, $archivo) = obtener_alertas($url, $timestamp);

    echo "<h2>Vulnerabilidades encontradas para $url</h2>";
    if (count($alertas) > 0) {
        foreach ($alertas as $alerta) {
            echo "<div class='alerta'>
                <h3>{$alerta['alert']} ({$alerta['risk']})</h3>
                <p><strong>URL:</strong> {$alerta['url']}</p>
                <p><strong>Descripción:</strong> {$alerta['description']}</p>
                <p><strong>Solución:</strong> {$alerta['solution']}</p>
            </div>";
        }
        echo "<div class='pdf-btn'>
            <form action='generar_pdf.php' method='post'>
              <input type='hidden' name='json_path' value='$archivo'>
              <button type='submit' title='Exportar a PDF'>
                <img src='./adobe.png' alt='Exportar a PDF'> Exportar informe PDF
              </button>
            </form>
          </div>";
    } else {
        echo "<p>No se detectaron vulnerabilidades.</p>";
    }
    echo "<p>Archivo guardado: <code>$archivo</code></p>
    <a href='zap_form.php'>🔁 Nuevo escaneo</a></main>
    <footer>&copy; " . date("Y") . " Pablo Martínez Bernal · TFM · UOC</footer></body></html>";
} else {
    header('Location: zap_form.php');
    exit;
}
?>
