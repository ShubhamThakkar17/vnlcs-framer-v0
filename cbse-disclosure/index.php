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
$lastUpdated = '2026-11-08';   // e.g. '2026-11-01'

$general = [
    ['Name of the school', 'Veer New Look Central School, Halol'],
    ['Affiliation number', '430235'],
    ['School code', '10214'],
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
    ['Fee structure of the school', ['fee-structure.pdf', 'fee-structure-2025-26.pdf' => '2025-26']],
    ['Annual academic calendar', ['academic-calendar.pdf', 'academic-calendar-2025-26.pdf' => '2025-26']],
    ['List of School Management Committee (SMC)', 'smc-list.pdf'],
    ['List of Parents Teachers Association (PTA) members', 'pta-list.pdf'],
    ['Last three years\' result of the board examination, as applicable', 'board-results.pdf'],
];
// Staff, results and infrastructure: from the school's own Appendix IX for 2025-26 (published on the old website).
$staff = [
    ['Principal', '1'],
    ['Total number of teachers', '58'],
    ['PGT', '9'],
    ['TGT', '15'],
    ['PRT', '28 [TO CONFIRM: PGT, TGT and PRT add up to 52 of the 58 teachers]'],
    ['Teachers section ratio', '1.40'],
    ['Details of special educator', '1 (Bhagyashri Narayan Badgujar)'],
    ['Details of counsellor and wellness teacher', '1 (Sijomon P C)'],
];
$results = [
    // year, class, registered, passed, pass %
    ['2022-23', 'X', 76, 69, '90.78%'],
    ['2023-24', 'X', 59, 57, '96.61%'],
    ['2024-25', 'X', 65, 54, '83.07%'],
    ['2022-23', 'XII', 4, 3, '75%'],
    ['2023-24', 'XII', 18, 13, '[TO CONFIRM: 13 of 18 is 72.22%, the school\'s sheet says 89%]'],
    ['2024-25', 'XII', 44, 36, '81.81%'],
];
$infra = [
    ['Total campus area of the school (in square metres)', '8,130'],
    ['Number and size of the classrooms (in square metres)', 'Size: 46.45. Number of classrooms: [TO CONFIRM]'],
    ['Number and size of laboratories, including computer labs (in square metres)', 'Physics, Chemistry, Biology, Maths and Fine Arts labs, and two computer labs. Size: 55.74. [TO CONFIRM: number of labs, and whether 55.74 is the size of each]'],
    ['Internet facility', 'Yes'],
    ['Number of girls\' toilets', '20'],
    ['Number of boys\' toilets', '20'],
    ['Link of YouTube video of the inspection of the school covering the infrastructure', 'https://www.youtube.com/watch?v=klomY5iPD2M'],
];

/**
 * Link to a document in /docs/. $file is a file name, or [current name, older name => 'session label']:
 * the current file wins; otherwise the older one is linked with its session, e.g. "(2025-26)".
 */
function doc_cell($file): string
{
    $tries = is_array($file) ? $file : [$file];
    foreach ($tries as $k => $v) {
        [$name, $label] = is_int($k) ? [$v, ''] : [$k, $v];
        $path = '/docs/' . $name;
        if (is_file(ROOT . $path)) {
            $kb = (int) ceil(filesize(ROOT . $path) / 1024);
            $text = $label !== '' ? 'View PDF for ' . $label : 'View PDF';
            return '<a class="doc-link" href="' . e($path) . '">' . icon('file') . e($text) . ' <span class="muted small">(' . $kb . ' KB)</span></a>';
        }
    }
    $want = is_array($file) ? $file[0] : $file;
    return '<span class="placeholder">[PDF PENDING: ' . e($want) . ']</span>';
}

/** Table cell text: [BRACKETS] become visible placeholders, web addresses become links. */
function disclosure_cell(string $v): string
{
    if (preg_match('#^https://\S+$#', $v)) {
        return '<a href="' . e($v) . '" rel="noopener">' . icon('external') . e(preg_replace('#^https://(www\.)?#', '', $v)) . '</a>';
    }
    return preg_replace('#\[[^\]]+\]#', '<span class="placeholder">$0</span>', e($v));
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
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= disclosure_cell((string) $v) ?></td></tr>
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
<?php foreach ($results as [$yr, $cls, $reg, $pass, $pct]): ?>
          <tr><td><?= e($yr) ?></td><td><?= e($cls) ?></td><td><?= (int) $reg ?></td><td><?= (int) $pass ?></td><td><?= disclosure_cell($pct) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="staff">D. Staff (teaching)</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="D. Staff (teaching) (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">D. Staff (teaching)</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Information</th><th scope="col">Details</th></tr></thead>
        <tbody>
<?php foreach ($staff as $i => [$k, $v]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= disclosure_cell((string) $v) ?></td></tr>
<?php endforeach; ?>
        </tbody>
      </table></div>

      <h2 class="disclosure__h" id="infrastructure">E. School infrastructure</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="E. School infrastructure (table)"><table class="data-table disclosure__table">
        <caption class="visually-hidden">E. School infrastructure</caption>
        <thead><tr><th scope="col">S. No.</th><th scope="col">Information</th><th scope="col">Details</th></tr></thead>
        <tbody>
<?php foreach ($infra as $i => [$k, $v]): ?>
          <tr><td><?= $i + 1 ?></td><th scope="row"><?= e($k) ?></th><td><?= disclosure_cell((string) $v) ?></td></tr>
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
