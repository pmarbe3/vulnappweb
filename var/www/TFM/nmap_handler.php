<?php
// Configuración
set_time_limit(120);

function escanearRed($subnet) {
    $subnet = escapeshellarg($subnet);
    $timestamp = date("Ymd_His");
    $filename_txt = "nmap_$timestamp.txt";
    $filename_json = "nmap_hosts_$timestamp.json";
    $path_txt = __DIR__ . "/logs/nmap/" . $filename_txt;
    $path_json = __DIR__ . "/logs/nmap/" . $filename_json;

    // Ejecutar escaneo de red
    $command = "sudo nmap -sn $subnet -oN $path_txt";
    exec($command);

    $ips_activas = [];
    if (file_exists($path_txt)) {
        $lines = file($path_txt);
        foreach ($lines as $line) {
            if (preg_match('/Nmap scan report for ([0-9\.]+)/', $line, $matches)) {
                $host = [
                    "ip" => $matches[1],
                    "estado" => "activo",
                    "timestamp" => date(DATE_ISO8601),
                    "tool" => "NMAP"
                ];
                $ips_activas[] = $host;
            }
        }
    }

    // Guardar cada IP como línea JSON independiente (JSON Lines)
    $handle = fopen($path_json, 'w');
    foreach ($ips_activas as $item) {
        fwrite($handle, json_encode($item) . "\n");
    }
    fclose($handle);

    return [$ips_activas, $path_txt, $path_json];
}

function escanearPuertos($ip) {
    $ip_clean = trim($ip);
    $ip_escaped = escapeshellarg($ip_clean);
    $timestamp = date("Ymd_His");

    $filename_txt = "nmap_ports_$ip_clean_$timestamp.txt";
    $filename_json = "nmap_ports_$timestamp.json";
    $path_txt = __DIR__ . "/logs/nmap/" . $filename_txt;
    $path_json = __DIR__ . "/logs/nmap/" . $filename_json;

    // Ejecutar Nmap
    $command = "sudo nmap -sS -sV -Pn $ip_escaped -oN $path_txt";
    exec($command, $output_lines);

    $parsed = [];
    foreach ($output_lines as $line) {
        if (preg_match('/^([0-9]+)\\/tcp\\s+(open|closed|filtered)\\s+(\\S+)(\\s+.+)?$/', trim($line), $m)) {
            $parsed[] = [
                "ip" => $ip_clean,
                "puerto" => $m[1],
                "estado" => $m[2],
                "servicio" => $m[3],
                "version" => trim($m[4] ?? ''),
                "timestamp" => date(DATE_ISO8601),
                "tool" => "NMAP"
            ];
        }
    }

    // Guardar cada puerto como línea JSON independiente
    $handle = fopen($path_json, 'w');
    foreach ($parsed as $item) {
        fwrite($handle, json_encode($item) . "\n");
    }
    fclose($handle);

    return [$output_lines, $path_txt];
}

// Manejo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>
    <title>Resultados del Escaneo</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f2f2f2; margin: 0; padding: 0; }
        header { background-color: white; padding: 1rem 2rem; display: flex; align-items: center; border-bottom: 3px solid #0099cc; }
        header img { height: 60px; margin-right: 1.5rem; }
        header h1 { color: #0099cc; margin: 0; font-size: 1.6rem; }
        main { padding: 2rem; max-width: 800px; margin: auto; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 2rem; }
        footer { text-align: center; font-size: 0.9rem; color: #666; margin-top: 3rem; }
        .ip-box { border: 1px solid #ccc; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; }
        .ip-box form { display: inline; }
        button { background: #0099cc; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; }
        button:hover { background: #0077a3; }
        pre { background: #f8f8f8; padding: 1rem; border-radius: 6px; overflow-x: auto; }
    </style>
    </head><body>
    <header><img src='logo.png' alt='Logo UOC'><h1>Resultado del Escaneo de Red</h1></header>
    <main>";

    if (isset($_POST['ip'])) {
        $ip = $_POST['ip'];
        echo "<h2>Puertos abiertos en $ip</h2>";
        list($salida, $ruta_txt) = escanearPuertos($ip);
        echo "<pre>" . htmlspecialchars(implode("\n", $salida)) . "</pre>";
        
    } elseif (isset($_POST['subnet'])) {
        $subnet = $_POST['subnet'];
        list($ips, $txt, $json) = escanearRed($subnet);
        echo "<h2>Hosts activos encontrados en $subnet:</h2>";
        if (count($ips) > 0) {
            foreach ($ips as $item) {
                $ip = $item['ip'];
                echo "<div class='ip-box'>
                    <strong>$ip</strong>
                    <form method='post'>
                        <input type='hidden' name='ip' value='$ip'>
                        <button type='submit'>Escanear puertos</button>
                    </form>
                </div>";
            }
        } else {
            echo "<p>No se encontraron hosts activos.</p>";
        }

    }

    echo "<a href='nmap_form.php'>⬅ Volver a escanear otra red</a></main>
    <footer>&copy; " . date("Y") . " Pablo Martínez Bernal · TFM · UOC</footer></body></html>";
} else {
    header('Location: nmap_form.php');
    exit;
}
?>
