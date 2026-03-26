<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Subdirectory installs (e.g. http://localhost/mergersales/...)
|--------------------------------------------------------------------------
|
| Some Apache + mod_rewrite setups report SCRIPT_NAME as "/index.php" even when
| the app lives in a subfolder. Laravel then sees the path as "/mergersales/About-Us"
| instead of "/About-Us" and returns 404. Align SCRIPT_NAME with the real folder.
|
*/

if (! empty($_SERVER['DOCUMENT_ROOT']) && ! empty($_SERVER['SCRIPT_FILENAME'])) {
    $doc = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']) ?: '');
    $dir = str_replace('\\', '/', realpath(dirname($_SERVER['SCRIPT_FILENAME'])) ?: '');
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

    if ($doc !== '' && $dir !== '' && str_starts_with($dir, $doc)) {
        // Only correct SCRIPT_NAME when Apache reports it as "/index.php".
        // If Apache already reports "/<subdir>/index.php" (any casing), do not touch it.
        if ($scriptName === '' || $scriptName === '/index.php' || $scriptName === 'index.php') {
            $subFromFs = trim(substr($dir, strlen($doc)), '/');

            // Prefer the first URL segment (preserves casing like "/Mergersales/...").
            $uriPath = explode('?', (string) ($_SERVER['REQUEST_URI'] ?? ''), 2)[0] ?? '';
            $firstSeg = explode('/', trim($uriPath, '/'), 2)[0] ?? '';

            $sub = $firstSeg !== '' ? $firstSeg : $subFromFs;
            if ($sub !== '') {
                $expected = '/' . $sub . '/index.php';
                $_SERVER['SCRIPT_NAME'] = $expected;
                $_SERVER['PHP_SELF'] = $expected;
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
