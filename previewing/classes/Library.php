<?php
declare(strict_types=1);

final readonly class Library extends Resources
{
  public function __construct()
  {
    parent::__construct(__DIR__ . '/../resources');
  }
}
