<?php
declare(strict_types=1);

final readonly class TemplateHtmlString implements \Stringable
{
  public function __construct(public string $html) {}

  public function __toString(): string
  {
    return $this->html;
  }
}
