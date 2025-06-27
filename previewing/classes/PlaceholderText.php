<?php
declare(strict_types=1);

final readonly class PlaceholderText
{
  private array $words;

  public function __construct(private string $text)
  {
    $this->words = \array_unique(
      \array_filter(\preg_split('~[\s.]+~', \strtolower($this->text)), static fn(string $word): bool => $word !== ''),
    );
  }

  public function words(int $count): string
  {
    /** @var list<string> $words */
    $words = [];
    for ($i = 0; $i < $count; $i++) {
      $words[] = $this->words[\array_rand($this->words)];
    }
    return \implode(' ', $words);
  }

  private function sentences(int $count, int $minPerSentence = 5, int $maxPerSentence = 20): string
  {
    /** @var list<string> $sentences */
    $sentences = [];
    for ($i = 0; $i < $count; $i++) {
      $sentences[] = \ucfirst($this->words(\random_int($minPerSentence, $maxPerSentence))) . '.';
    }
    return \implode(' ', $sentences);
  }

  public function paragraphs(
    int $count,
    int $minSentences = 1,
    int $maxSentences = 4,
    int $minPerSentence = 5,
    int $maxPerSentence = 20,
    string $separator = "\n\n",
  ): string {
    /** @var list<string> $paragraphs */
    $paragraphs = [];
    for ($i = 0; $i < $count; $i++) {
      $paragraphs[] = $this->sentences(\random_int($minSentences, $maxSentences), $minPerSentence, $maxPerSentence);
    }
    return \implode($separator, $paragraphs);
  }

  public static function generate(): self
  {
    $paragraphs = \rtrim(\file_get_contents('https://lorem-api.com/api/lorem?paragraphs=' . 10));
    $text = \preg_replace('~\R+~', ' ', $paragraphs);
    return new self($text);
  }
}
