<?php
/**
 * Shared helpers for Dance Dimension.
 *
 * URL helpers work both at the domain root and in a subdirectory
 * such as /dance-dimension/.
 */
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

const DD_MAX_AUDIO_BYTES = 8 * 1024 * 1024;
const DD_MAX_IMAGE_BYTES = 3 * 1024 * 1024;
const DD_MAX_CHART_BYTES = 256 * 1024;

/**
 * Project root on disk (directory that contains this file).
 */
function dd_root(): string
{
    return __DIR__;
}

/**
 * URL path of the project root, e.g. "/dance-dimension" or "".
 */
function dd_base(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    $root = realpath(dd_root());
    $doc = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    if ($root && $doc && strpos($root, $doc) === 0) {
        $base = str_replace('\\', '/', substr($root, strlen($doc)));
        $base = rtrim($base, '/');
        return $base;
    }
    $base = '';
    return $base;
}

/**
 * Relative prefix from the current script directory to the project root
 * ("" or "../" or "../../" …).
 */
function dd_prefix(): string
{
    static $prefix = null;
    if ($prefix !== null) {
        return $prefix;
    }
    $script = isset($_SERVER['SCRIPT_FILENAME']) ? realpath($_SERVER['SCRIPT_FILENAME']) : false;
    $root = realpath(dd_root());
    if (!$script || !$root) {
        $prefix = '';
        return $prefix;
    }
    $dir = dirname($script);
    $up = '';
    $guard = 0;
    while ($dir !== $root && $dir !== '/' && $dir !== '.' && $guard++ < 12) {
        if ($root && strpos($dir, $root) !== 0) {
            break;
        }
        $up .= '../';
        $parent = dirname($dir);
        if ($parent === $dir) {
            break;
        }
        $dir = $parent;
    }
    $prefix = $up;
    return $prefix;
}

/**
 * URL, relative to the current page, for a project-root path like
 * "2-Web_Assets/styles.css".
 */
function dd_url(string $fromRoot): string
{
    $fromRoot = ltrim(str_replace('\\', '/', $fromRoot), '/');
    return dd_prefix() . $fromRoot;
}

/**
 * Filesystem path for a project-root path.
 */
function dd_fs(string $fromRoot): string
{
    $fromRoot = ltrim(str_replace('\\', '/', $fromRoot), '/');
    return dd_root() . '/' . $fromRoot;
}

function dd_h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * JSON safe to embed inside a <script> tag.
 */
function dd_json($data): string
{
    $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    $out = json_encode($data, $flags);
    return $out === false ? 'null' : $out;
}

function dd_load_json(string $fromRoot, $default)
{
    $path = dd_fs($fromRoot);
    if (!is_file($path)) {
        return $default;
    }
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
        return $default;
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $default;
}

function dd_save_json(string $fromRoot, $data, bool $objectKeys = false): bool
{
    $path = dd_fs($fromRoot);
    $dir = dirname($path);
    if (!is_dir($dir)) {
        return false;
    }
    $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    if ($objectKeys) {
        $obj = new stdClass();
        foreach ($data as $key => $value) {
            $obj->{$key} = $value;
        }
        $data = $obj;
    }
    $json = json_encode($data, $flags);
    if ($json === false) {
        return false;
    }
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}

function dd_save_scores(array $scores): bool
{
    return dd_save_json('1-Song_Data/scores.json', $scores, true);
}

function dd_load_songs(): array
{
    $songs = dd_load_json('1-Song_Data/song_info.json', []);
    if (!is_array($songs)) {
        return [];
    }
    $isList = $songs === [] || array_keys($songs) === range(0, count($songs) - 1);
    return $isList ? $songs : array_values($songs);
}

function dd_load_scores(): array
{
    $scores = dd_load_json('1-Song_Data/scores.json', []);
    if (!is_array($scores)) {
        return [];
    }
    $out = [];
    foreach ($scores as $key => $rows) {
        $out[(string) $key] = is_array($rows) ? $rows : [];
    }
    return $out;
}

function dd_head(string $title, array $scripts = []): void
{
    $css = dd_url('2-Web_Assets/styles.css');
    $icon = dd_url('2-Web_Assets/The Dance Dimension.png');
    echo "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n";
    echo "    <meta charset=\"UTF-8\">\n";
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
    echo "    <title>" . dd_h($title) . "</title>\n";
    echo "    <link rel=\"stylesheet\" href=\"" . dd_h($css) . "\">\n";
    echo "    <link rel=\"icon\" type=\"image/png\" href=\"" . dd_h($icon) . "\">\n";
    echo "    <script>window.DD_PREFIX = " . dd_json(dd_prefix()) . ";</script>\n";
    foreach ($scripts as $src) {
        echo "    <script src=\"" . dd_h($src) . "\"></script>\n";
    }
    echo "</head>\n";
}

function dd_config(): array
{
    $file = dd_fs('config.php');
    if (!is_file($file)) {
        return [];
    }
    $cfg = include $file;
    return is_array($cfg) ? $cfg : [];
}

function dd_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function dd_writes_allowed(): bool
{
    $token = (string) (dd_config()['write_token'] ?? '');
    if ($token === '') {
        return true;
    }
    dd_session();
    return !empty($_SESSION['dd_write_ok']);
}

function dd_require_write(string $returnTo = ''): void
{
    if (dd_writes_allowed()) {
        return;
    }
    $target = 'unlock.php';
    if ($returnTo !== '') {
        $target .= '?next=' . rawurlencode($returnTo);
    }
    header('Location: ' . dd_url($target), true, 302);
    exit;
}

function dd_redirect(string $fromRoot): void
{
    header('Location: ' . dd_url($fromRoot), true, 302);
    exit;
}

function dd_extension(string $name): string
{
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    return $ext;
}

function dd_is_allowed_audio(array $file): bool
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return false;
    }
    if (($file['size'] ?? 0) <= 0 || $file['size'] > DD_MAX_AUDIO_BYTES) {
        return false;
    }
    $ext = dd_extension($file['name'] ?? '');
    return in_array($ext, ['mp3', 'ogg', 'wav', 'oga'], true);
}

function dd_is_allowed_image(array $file): bool
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return false;
    }
    if (($file['size'] ?? 0) <= 0 || $file['size'] > DD_MAX_IMAGE_BYTES) {
        return false;
    }
    $ext = dd_extension($file['name'] ?? '');
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        return false;
    }
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        return false;
    }
    $ok = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
    return in_array($info[2], $ok, true);
}

function dd_is_allowed_chart_upload(array $file): bool
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return false;
    }
    if (($file['size'] ?? 0) <= 0 || $file['size'] > DD_MAX_CHART_BYTES) {
        return false;
    }
    return dd_extension($file['name'] ?? '') === 'txt';
}

function dd_sanitize_filename(string $name): string
{
    $base = basename($name);
    $base = preg_replace('/[^A-Za-z0-9._-]/', '_', $base) ?? 'file';
    return $base === '' ? 'file' : $base;
}
