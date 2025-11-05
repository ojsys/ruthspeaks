<?php
use const App\ADS_ENABLED;
use const App\AD_HEAD_SNIPPET;
use const App\AD_LEADERBOARD_HTML;
use const App\AD_IN_ARTICLE_HTML;
use const App\AD_SIDEBAR_HTML;
use const App\AD_FOOTER_HTML;

// $placement: 'leaderboard' | 'in-article' | 'sidebar' | 'footer' | 'head'
$placement = $placement ?? '';
if (!ADS_ENABLED) return;

if ($placement === 'head') {
  if (!empty(AD_HEAD_SNIPPET)) { echo AD_HEAD_SNIPPET; }
  return;
}

$map = [
  'leaderboard' => AD_LEADERBOARD_HTML,
  'in-article' => AD_IN_ARTICLE_HTML,
  'sidebar' => AD_SIDEBAR_HTML,
  'footer' => AD_FOOTER_HTML,
];

$html = $map[$placement] ?? '';
if (!empty($html)) { echo $html; }
// If no HTML configured for this slot, render nothing to keep UI clean.
