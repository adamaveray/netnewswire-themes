<?php
declare(strict_types=1);

final readonly class Theme extends Resources
{
  private const string THEMES_DIRECTORY = __DIR__ . '/../..';
  private const string THEME_EXTENSION = 'nnwtheme';

  public Template $template;

  public function __construct(public string $name)
  {
    $directory = self::THEMES_DIRECTORY . '/' . $name . '.' . self::THEME_EXTENSION;
    try {
      parent::__construct($directory);
    } catch (\InvalidArgumentException $exception) {
      throw new \InvalidArgumentException('Theme "' . $name . '" not found.', previous: $exception);
    }
    $this->template = new Template($this->loadResource('template.html'), isHtml: true);
  }

  public static function getDefaultThemeName(): string
  {
    $themes = \glob(self::THEMES_DIRECTORY . '/*.' . self::THEME_EXTENSION);
    if ($themes === false || empty($themes)) {
      throw new \UnexpectedValueException('No themes found.');
    }
    return \basename($themes[0], '.' . self::THEME_EXTENSION);
  }
}
