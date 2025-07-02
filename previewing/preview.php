<?php
declare(strict_types=1);

require_once __DIR__ . '/lib.php';

try {
  $theme = new Theme($_GET['theme'] ?? Theme::getDefaultThemeName());
  $textSize = TextSize::from($_GET['textSize'] ?? TextSize::Medium->value);
} catch (\InvalidArgumentException $exception) {
  \header('content-type: text/plain', response_code: 404);
  echo $exception->getMessage();
  exit();
}

$library = new Library();

$replacements = require __DIR__ . '/previewContent.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?= e('NetNewsWire "' . $theme->name . '" Theme Preview') ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style><?= e($library->loadResource('core.css'), type: 'css') ?></style>
  <style><?= e($theme->loadResource('stylesheet.css'), type: 'css') ?></style>
</head>
<body>
  <?= $theme->template->render($replacements) ?>
  <script><?= e($library->loadResource('main_ios.js'), type: 'js') ?></script>
  <script><?= e($library->loadResource('main.js'), type: 'js') ?></script>
  <script><?= e($library->loadResource('newsfoot.js'), type: 'js') ?></script>
</body>
</html>
