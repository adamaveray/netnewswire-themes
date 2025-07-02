<?php
declare(strict_types=1);

final readonly class PlaceholderBodyGenerator
{
  private const SECTIONS = [
    'heading',
    'paragraphs',
    'pseudoParagraphs',
    'blockquote',
    'orderedList',
    'unorderedList',
    'image',
    'br',
    'hr',
  ];

  public function __construct(private PlaceholderText $placeholderText) {}

  public function generate(int $sectionsCount = 50): \Stringable
  {
    $sections = [];
    for ($i = 0; $i < $sectionsCount; $i++) {
      $sections[] = self::SECTIONS[\array_rand(self::SECTIONS)];
    }

    $html = '';
    foreach ($sections as $section) {
      $html .= match ($section) {
        'heading' => $this->buildHeading(level: \random_int(1, 6), words: \random_int(1, 8)),
        'paragraphs' => $this->buildParagraphs(count: \random_int(1, 5), style: 'html'),
        'pseudoParagraphs' => $this->buildParagraphs(count: \random_int(1, 5), style: 'pseudo'),
        'blockquote' => $this->buildBlockquote(paragraphs: \random_int(1, 2)),
        'orderedList' => $this->buildList(type: 'ordered', items: \random_int(2, 5)),
        'unorderedList' => $this->buildList(type: 'unordered', items: \random_int(2, 5)),
        'image' => $this->buildImage(),
        'br' => '<br />',
        'hr' => '<hr />',
      };
    }

    return Template::safeString($html);
  }

  private function buildHeading(int $level, int $words): string
  {
    $tag = 'h' . $level;
    return '<' . $tag . '>' . e(\ucfirst($this->placeholderText->words($words))) . '</' . $tag . '>';
  }

  /**
   * @param 'html'|'pseudo' $style
   */
  private function buildParagraphs(
    int $count,
    string $style,
    int $minSentences = 1,
    int $maxSentences = 4,
    int $minPerSentence = 5,
    int $maxPerSentence = 20,
  ): string {
    $paragraphs = \explode(
      "\n\n",
      $this->placeholderText->paragraphs($count, $minSentences, $maxSentences, $minPerSentence, $maxPerSentence),
    );
    return match ($style) {
      'html' => \implode(
        "\n\n",
        \array_map(static fn(string $paragraph): string => '<p>' . e($paragraph) . '</p>', $paragraphs),
      ),
      'pseudo' => \implode('<br /><br />', \array_map(e(...), $paragraphs)),
    };
  }

  private function buildBlockquote(int $paragraphs): string
  {
    return '<blockquote>' .
      $this->buildParagraphs(
        $paragraphs,
        style: 'html',
        minSentences: 1,
        maxSentences: 3,
        minPerSentence: 4,
        maxPerSentence: 12,
      ) .
      '</blockquote>';
  }

  private function buildList(string $type, int $items): string
  {
    $tag = match ($type) {
      'ordered' => 'ol',
      'unordered' => 'ul',
    };
    $list = '';
    for ($i = 0; $i < $items; $i++) {
      $list .= '<li>' . e(\ucfirst($this->placeholderText->words(\random_int(1, 5)))) . '</li>';
    }
    return '<' . $tag . '>' . $list . '</' . $tag . '>';
  }

  private function buildImage(): string
  {
    $width = \random_int(500, 1500);
    $height = \random_int(400, 800);
    $url = 'https://picsum.photos/' . \urlencode((string) $width) . '/' . \urlencode((string) $height);
    return '<img src="' . e($url) . '" alt="(Placeholder)" />';
  }
}
