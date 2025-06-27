<?php
declare(strict_types=1);

final readonly class Template
{
  public function __construct(private string $content, private bool $isHtml) {}

  public function render(array $replacements): string
  {
    return \preg_replace_callback(
      '~\[\[(\w+)]]~',
      fn(array $matches): string => isset($replacements[$matches[1]])
        ? $this->processReplacement($replacements[$matches[1]])
        : $matches[0],
      $this->content,
    );
  }

  private function processReplacement(string|\Stringable $replacement): string
  {
    if ($this->isHtml && !($replacement instanceof TemplateHtmlString)) {
      $replacement = e((string) $replacement);
    }
    return (string) $replacement;
  }

  public static function safeString(string $html): TemplateHtmlString
  {
    return new TemplateHtmlString($html);
  }
}
