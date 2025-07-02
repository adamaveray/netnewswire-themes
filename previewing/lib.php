<?php
declare(strict_types=1);

require_once __DIR__ . '/classes/Resources.php';

require_once __DIR__ . '/classes/TextSize.php';
require_once __DIR__ . '/classes/Library.php';
require_once __DIR__ . '/classes/PlaceholderBodyGenerator.php';
require_once __DIR__ . '/classes/PlaceholderText.php';
require_once __DIR__ . '/classes/Template.php';
require_once __DIR__ . '/classes/TemplateHtmlString.php';
require_once __DIR__ . '/classes/Theme.php';

function e(string $string, string $type = 'html'): string
{
  return match ($type) {
    'html' => \htmlspecialchars($string, \ENT_QUOTES | \ENT_HTML5),
    'css' => \str_replace('<', '&lt;', $string),
    'js' => \preg_replace('~</script\b~', '<\\/script', $string),
    default => throw new \InvalidArgumentException('Invalid type "' . $type . '".'),
  };
}

/**
 * @param 'date'|'time'|'datetime' $type
 * @param \IntlDateFormatter::* $format
 */
function formatDateTime(\DateTimeInterface $dateTime, string $type, int $format): string
{
  $formatter = new \IntlDateFormatter(
    null,
    dateType: match ($type) {
      'datetime', 'date' => $format,
      default => \IntlDateFormatter::NONE,
    },
    timeType: match ($type) {
      'datetime', 'date' => $format,
      default => \IntlDateFormatter::NONE,
    },
  );
  return $formatter->format($dateTime);
}

/**
 * @template T
 * @param callable():T $generator
 * @return T
 */
function cache(string $id, \DateInterval $duration, callable $generator): mixed
{
  $cacheDir = __DIR__ . '/../.cache';
  if (!\is_dir($cacheDir) && !\mkdir($cacheDir, 0700, true) && !\is_dir($cacheDir)) {
    throw new \RuntimeException(\sprintf('Failed creating directory "%s".', $cacheDir));
  }

  $path = $cacheDir . '/' . $id . '.json';
  $expiry = (new \DateTimeImmutable())->sub($duration);
  $isValid = \file_exists($path) && \filemtime($path) >= $expiry->getTimestamp();
  if ($isValid) {
    $result = \unserialize(\file_get_contents($path), ['allowed_classes' => true]);
    if ($result !== false) {
      return $result;
    }
  }

  $result = $generator();
  \file_put_contents($path, \serialize($result));
  return $result;
}
