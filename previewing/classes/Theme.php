<?php
declare(strict_types=1);

final readonly class Theme
{
  private const THEMES_DIRECTORY = __DIR__ . '/../..';
  private const THEME_EXTENSION = 'nnwtheme';

  private string $directory;
  public Template $template;

  public function __construct(public string $name)
  {
    $directory = self::THEMES_DIRECTORY . '/' . $name . '.' . self::THEME_EXTENSION;
    if (!\is_dir($directory)) {
      throw new \InvalidArgumentException('Theme "' . $name . '" not found.');
    }
    $this->directory = $directory;
    $this->template = new Template($this->loadResource('template.html'), isHtml: true);
  }

  public function loadResource(string $name): string
  {
    return \rtrim(\file_get_contents($this->directory . '/' . $name));
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
