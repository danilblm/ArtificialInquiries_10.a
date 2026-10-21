<?php
/*
 * Passerelle d'authentification pour response.html
 * - GET  auth.php?config          → configuration publique (client IDs, serveur CAS)
 * - POST auth.php {provider, ...} → vérifie l'identité et renvoie la clé Omeka associée
 *   provider = google  : { credential }        (ID token Google)
 *   provider = github  : { code }              (code OAuth GitHub)
 *   provider = cas     : { ticket, service }   (ticket CAS)
 */
$cfg = require __DIR__ . '/config.php';

header('Access-Control-Allow-Origin: ' . $cfg['allowed_origin']);
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function reply($code, $data) { http_response_code($code); echo json_encode($data, JSON_UNESCAPED_UNICODE); exit; }

function http($method, $url, $headers = [], $body = null) {
    if (!function_exists('curl_init')) {           // repli si l'extension curl est désactivée
        $ctx = stream_context_create(['http' => [
            'method' => $method, 'ignore_errors' => true, 'timeout' => 15,
            'header' => implode("\r\n", array_merge(['User-Agent: ArtificialInquiries-auth',
                        'Content-Type: application/x-www-form-urlencoded'], $headers)),
            'content' => $body ?? '',
        ]]);
        $res = @file_get_contents($url, false, $ctx);
        if ($res === false) reply(502, ['error' => 'Appel réseau impossible (activer curl ou openssl dans PHP)']);
        preg_match('#\s(\d{3})\s#', $http_response_header[0] ?? '', $m);
        return [(int)($m[1] ?? 0), $res];
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => array_merge(['User-Agent: ArtificialInquiries-auth'], $headers),
        CURLOPT_TIMEOUT        => 15,
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $res = curl_exec($ch);
    if ($res === false) reply(502, ['error' => 'Appel réseau impossible : ' . curl_error($ch)]);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$status, $res];
}

if (isset($_GET['config'])) {
    reply(200, [
        'google_client_id' => $cfg['google_client_id'],
        'github_client_id' => $cfg['github_client_id'],
        'cas_server'       => $cfg['cas_server'],
    ]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') reply(405, ['error' => 'Méthode non autorisée']);
$in = json_decode(file_get_contents('php://input'), true) ?: [];

switch ($in['provider'] ?? '') {

    case 'google':
        if (!$cfg['google_client_id']) reply(500, ['error' => 'google_client_id manquant dans config.php']);
        [$s, $r] = http('GET', 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($in['credential'] ?? ''));
        $t = json_decode($r, true);
        if ($s !== 200 || ($t['aud'] ?? '') !== $cfg['google_client_id'] || ($t['email_verified'] ?? '') !== 'true')
            reply(401, ['error' => 'Jeton Google invalide']);
        $id = 'google:' . strtolower($t['email']);
        $name = $t['name'] ?? $t['email'];
        break;

    case 'github':
        if (!$cfg['github_client_id'] || !$cfg['github_client_secret'])
            reply(500, ['error' => 'github_client_id / github_client_secret manquants dans config.php']);
        [$s, $r] = http('POST', 'https://github.com/login/oauth/access_token', ['Accept: application/json'],
            http_build_query(['client_id' => $cfg['github_client_id'], 'client_secret' => $cfg['github_client_secret'], 'code' => $in['code'] ?? '']));
        $tok = json_decode($r, true)['access_token'] ?? null;
        if (!$tok) reply(401, ['error' => 'Code GitHub invalide ou expiré']);
        [$s, $r] = http('GET', 'https://api.github.com/user', ['Authorization: Bearer ' . $tok, 'Accept: application/vnd.github+json']);
        $u = json_decode($r, true);
        if ($s !== 200 || empty($u['login'])) reply(401, ['error' => 'Profil GitHub illisible']);
        $id = 'github:' . strtolower($u['login']);
        $name = $u['name'] ?: $u['login'];
        break;

    case 'cas':
        $url = rtrim($cfg['cas_server'], '/') . '/serviceValidate?service=' . urlencode($in['service'] ?? '')
             . '&ticket=' . urlencode($in['ticket'] ?? '');
        [$s, $r] = http('GET', $url);
        if (!preg_match('#<cas:user>([^<]+)</cas:user>#', $r, $m)) reply(401, ['error' => 'Ticket CAS invalide']);
        $id = 'cas:' . strtolower(trim($m[1]));
        $name = trim($m[1]);
        break;

    default:
        reply(400, ['error' => 'Fournisseur inconnu']);
}

$keys = $cfg['users'][$id] ?? null;
reply(200, ['user' => ['id' => $id, 'name' => $name], 'keys' => $keys]);
