<?php
if (!defined('ROOT')) {
    require __DIR__ . '/inc/bootstrap.php';
} elseif (!function_exists('cfg')) {
    require ROOT . '/inc/bootstrap.php';
}
http_response_code(404);
$page = [
    'title' => 'Page not found',
    'description' => 'This page has been wiped off the board. Find your way back to Veer New Look Central School, Halol.',
    'path' => '/404',
    'robots' => 'noindex, follow',
    'body_class' => 'page-404',
];
require ROOT . '/partials/head.php';
?>
<main id="main" class="section lost">
  <div class="container lost__board">
    <div class="lost__smudge" aria-hidden="true"></div>
    <p class="eyebrow">Error 404</p>
    <h1><span class="line">Oops! This page</span> <span class="line">was <span class="hl">wiped off</span> the board</span></h1>
    <p class="lead">The page you wanted is not here any more, or the link has a typo.</p>
    <div class="lost__home">
      <?= doodle('arrow', 'lost__arrow') ?>
      <a class="btn btn--primary" href="/">Back to the home page</a>
    </div>
    <ul class="lost__links" role="list">
      <li><a href="/notices/">Notice Board</a></li>
      <li><a href="/admissions/">Admissions</a></li>
      <li><a href="/about/">About the school</a></li>
      <li><a href="/cbse-disclosure/">CBSE Disclosure</a></li>
    </ul>
  </div>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
