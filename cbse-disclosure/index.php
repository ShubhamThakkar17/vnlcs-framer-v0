<?php
require __DIR__ . '/../inc/bootstrap.php';
$crumbs = [['Home', '/'], ['CBSE Disclosure', '/cbse-disclosure/']];
$page = [
    'title' => 'CBSE Mandatory Public Disclosure',
    'description' => 'CBSE Mandatory Public Disclosure (Appendix IX) for Veer New Look Central School, Halol: general information, documents, results, staff and infrastructure.',
    'path' => '/cbse-disclosure/',
    'crumbs' => $crumbs,
    'body_class' => 'page-disclosure',
];
require ROOT . '/partials/head.php';

/*
 * Update this date whenever a document changes. Documents live in /docs/ with these
 * exact file names; a row links to its PDF as soon as the file is uploaded.
 */
$lastUpdated = '';   // e.g. '2026-11-01'

$general = [
    ['Name of the school', 'Veer New Look Central School, Halol'],
    ['Affiliation number', '[AFFILIATION NO.]'],
    ['School code', '[SCHOOL CODE]'],
    ['Complete address with PIN code', cfg('address.street') . ', ' . cfg('address.locality') . ', ' . cfg('address.district') . ', ' . cfg('address.region') . ' ' . cfg('address.postcode')],
    ['Principal name', '[PRINCIPAL\'S NAME]'],
    ['Principal qualification', '[QUALIFICATION]'],
    ['School email ID', implode(', ', cfg('emails'))],
    ['Contact details', implode(', ', array_map(fn($p) => $p['label'], cfg('phones')))],
];
$documents = [
    ['Copies of affiliation / upgradation letter and recent extension of affiliation, if any', 'affiliation-letter.pdf'],
    ['Copies of society / trust / company registration and renewal certificate, as applicable', 'trust-registration.pdf'],
    ['Copy of No Objection Certificate (NOC) issued by the State Government / UT, if applicable', 'noc.pdf'],
    ['Copies of recognition certificate under the RTE Act, 2009, and its renewal, if applicable', 'rte-recognition.pdf'],
    ['Copy of valid building safety certificate as per the National Building Code', 'building-safety.pdf'],
    ['Copy of valid fire safety certificate issued by the competent authority', 'fire-safety.pdf'],
    ['Copy of the DEO certificate submitted by the school for affiliation / upgradation / extension, or self-certification by the school', 'deo-certificate.pdf'],
    ['Copies of valid water, health and sanitation certificates', 'water-health-sanitation.pdf'],
];
$academics = [
    ['Fee structure of the school', 'fee-structure.pdf'],
    ['Annual academic calendar', 'academic-calendar.pdf'],
    ['List of School Management Committee (SMC)', 'smc-list.pdf'],
    ['List of Parents Teachers Association (PTA) members', 'pta-list.pdf'],
    ['Last three years\' result of the board examination, as applicable', 'board-results.pdf'],
];
$staff = [
    ['Principal', '[NAME]'],
    ['Total number of teachers', '[NUMBER]'],
    ['PGT', '[NUMBER]'],
    ['TGT', '[NUMBER]'],
    ['PRT', '[NUMBER]'],
    ['Teachers section ratio', '[RATIO]'],
    ['Details of special educator', '[DETAILS]'],
    ['Details of counsellor and wellness teacher', '[DETAILS]'],
];
$infra = [
    ['Total campus area of the school (in square metres)', '[TO CONFIRM]'],
    ['Number and size of the classrooms (in square metres)', '[TO CONFIRM]'],
    ['Number and size of laboratories, including computer labs (in square metres)', 'Physics, Chemistry, Biology, Maths and Fine Arts labs, and two computer labs. Sizes: [TO CONFIRM]'],
    ['Internet facility', '[YES / NO]'],
    ['Number of girls\' toilets', '[NUMBER]'],
    ['Number of boys\' toilets', '[NUMBER]'],
    ['Link of YouTube video of the inspection of the school covering the infrastructure', '[YOUTUBE LINK]'],
];

function doc_cell(string $file): string
{
    $path = '/docs/' . $file;
    if (is_file(ROOT . $path)) {
        $kb = (int) ceil(filesize(ROOT . $path) / 1024);
        return '<a class="doc-link" href="' . e($path) . '">' . icon('file') . 'View PDF <span class="muted small">(' . $kb . ' KB)</span></a>';
    }
    return '<span class="placeholder">[PDF PENDING: ' . e($file) . ']</span>';
}
?>
<main id="main" class="calm">
  <?= page_hero('Appendix IX', ['CBSE', '<span class="hl">Disclosure</span>'],
      'Mandatory Public Disclosure, as required by the CBSE Affiliation Bye-Laws.',
      $crumbs) ?>

  <section class="section-tight disclosure">
    <div class="container">
      <p class="updated"><?= icon('calendar') ?> Last updated: <?= $lastUpdated !== '' ? '<time datetime="' . e($lastUpdated) . '">' . e(nice_date($lastUpdated, 'j F Y')) . '</time>' : '<span class="placeholder">[DATE]</span>' ?></p>

      <h2 class="disclosure__h" id="general">A. General information</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="A. General information (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">A. General information</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Information</th><th scope="col">Details</th></tr></thead>
        <tbody>
<?php foreach ($general as $i => [$k, $v]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= e($v) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="documents">B. Documents and information</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="B. Documents and information (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">B. Documents and information</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Document</th><th scope="col">Link</th></tr></thead>
        <tbody>
<?php foreach ($documents as $i => [$k, $f]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= doc_cell($f) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>
      <p class="muted small">Note: the school will upload certificates attested by the chairman, manager or secretary and the principal.</p>

      <h2 class="disclosure__h" id="results">C. Result and academics</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="C. Result and academics (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">C. Result and academics</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Document</th><th scope="col">Link</th></tr></thead>
        <tbody>
<?php foreach ($academics as $i => [$k, $f]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= doc_cell($f) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h3 class="disclosure__sub">Board examination results</h3>
      <div class="table-wrap" tabindex="0" role="region" aria-label="Class 10 and Class 12 results for the last three years (table)"><table class="data-table disclosure__table">
        <caption>Class 10 and Class 12 results for the last three years</caption>
        <thead><tr><th scope="col">Year</th><th scope="col">Class</th><th scope="col">Registered</th><th scope="col">Passed</th><th scope="col">Pass %</th></tr></thead>
        <tbody>
          <tr><td>[YEAR]</td><td>10</td><td>[ ]</td><td>[ ]</td><td>[ ]</td></tr>
          <tr><td>[YEAR]</td><td>12</td><td>[ ]</td><td>[ ]</td><td>[ ]</td></tr>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="staff">D. Staff (teaching)</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="D. Staff (teaching) (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">D. Staff (teaching)</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Information</th><th scope="col">Details</th></tr></thead>
        <tbody>
<?php foreach ($staff as $i => [$k, $v]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= e($v) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="infrastructure">E. School infrastructure</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="E. School infrastructure (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">E. School infrastructure</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Information</th><th scope="col">Details</th></tr></thead>
        <tbody>
<?php foreach ($infra as $i => [$k, $v]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= e($v) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="tc">Transfer certificates</h2>
      <p><span class="placeholder">[TRANSFER CERTIFICATE INFORMATION: how parents request a TC, or the list of TCs issued, as the school prefers]</span></p>

      <aside class="official-links" aria-labelledby="cbse-links-title">
        <h2 id="cbse-links-title" class="disclosure__sub">Official CBSE websites</h2>
        <ul role="list">
          <li><a href="https://www.cbse.gov.in/" rel="noopener"><?= icon('external') ?>cbse.gov.in</a></li>
          <li><a href="https://cbseacademic.nic.in/" rel="noopener"><?= icon('external') ?>cbseacademic.nic.in</a></li>
          <li><a href="https://saras.cbse.gov.in/" rel="noopener"><?= icon('external') ?>CBSE SARAS (affiliation details)</a></li>
        </ul>
      </aside>
    </div>
  </section>
</main>
<?php require ROOT . '/partials/footer.php'; ?>
