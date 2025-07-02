<?php
declare(strict_types=1);

abstract readonly class Resources
{
  public function __construct(protected string $directory)
  {
    if (!\is_dir($directory)) {
      throw new \InvalidArgumentException('Resources directory "' . $directory . '" not found.');
    }
  }

  final public function loadResource(string $name): string
  {
    return \rtrim(\file_get_contents($this->directory . '/' . $name));
  }
}
