<?php
declare(strict_types=1);

$now = new \DateTimeImmutable();
$placeholderText = cache('placeholderText', new \DateInterval('PT10M'), PlaceholderText::generate(...));

$bodyGenerator = new PlaceholderBodyGenerator($placeholderText);

$textSize ??= TextSize::Medium;

return [
  // The title of the article
  'title' => \ucfirst($placeholderText->words(\random_int(4, 10))),
  // The best link to associate with the article for linking out.
  'preferred_link' => 'https://www.example.com/',

  // A localized label for the external link.
  'external_link_label' => 'External Link',
  // The external link minus the scheme. Useful for displaying the external link.
  'external_link_stripped' => 'www.example.com',
  // The external link of the article if there is one provided by the author.
  'external_link' => 'https://www.example.com/',

  // The name of the feed associated with this article.
  'feed_link_title' => \ucwords($placeholderText->words(\random_int(2, 8))),
  // The URL of the feed associated with this article.
  'feed_link' => 'https://www.example.com/',
  // HTML that combines all the authors and links to them if available.
  'byline' => \ucwords($placeholderText->words(\random_int(2, 8))),
  // The image source URL for the feed icon / avatar.
  'avatar_src' => 'https://gravatar.com/avatar/' . \urlencode(\hash('sha256', \strtolower('adam@averay.com'))),

  // Either "articleDateline" or "articleDatelineTitle" depending on if the title was populated or not.
  'dateline_style' => 'articleDatelineTitle',

  // Long version of a combined publish date and time.
  'datetime_long' => formatDateTime($now, 'datetime', \IntlDateFormatter::LONG),
  // Medium length version of a combined publish date and time.
  'datetime_medium' => formatDateTime($now, 'datetime', \IntlDateFormatter::MEDIUM),
  // Short version of a combined publish date and time.
  'datetime_short' => formatDateTime($now, 'datetime', \IntlDateFormatter::SHORT),

  // Long version of the publish date.
  'date_long' => formatDateTime($now, 'date', \IntlDateFormatter::LONG),
  // Medium version of the publish date.
  'date_medium' => formatDateTime($now, 'date', \IntlDateFormatter::MEDIUM),
  // Long version of the publish date.
  'date_short' => formatDateTime($now, 'date', \IntlDateFormatter::SHORT),

  // Long version of the publish time.
  'time_long' => formatDateTime($now, 'time', \IntlDateFormatter::LONG),
  // Medium version of the publish time.
  'time_medium' => formatDateTime($now, 'time', \IntlDateFormatter::MEDIUM),
  // Long version of the publish time.
  'time_short' => formatDateTime($now, 'time', \IntlDateFormatter::SHORT),

  // The size class that the user has selected in Preferences for article text.
  'text_size_class' => $textSize->value,
  // The body of the article.
  'body' => $bodyGenerator->generate(),
];
