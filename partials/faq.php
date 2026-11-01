<?php
/** Admissions FAQ, shared by Home and Admissions. Answers in [brackets] await the school. */
$faqs = [
    ['Which board is the school affiliated to?', 'We are affiliated to the Central Board of Secondary Education (CBSE). Teaching is in English, and the school is co-educational.'],
    ['Which classes do you have?', 'Nursery, LKG and UKG, then Class 1 to Class 12.'],
    ['Which streams are offered in Class 11 and 12?', 'Science (PCM and PCB) and Commerce. <span class="placeholder">[STREAMS FOR THE NEXT SESSION TO CONFIRM]</span>'],
    ['How do we apply?', 'Fill in the <a href="/admissions/#enquire">enquiry form</a>, call the office or send us a WhatsApp message. We will guide you through the next steps.'],
    ['What is the age for Nursery admission?', '<span class="placeholder">[AGE CRITERIA FOR EACH CLASS TO CONFIRM]</span>'],
    ['Can we visit the campus first?', 'Yes, please do. Book a visit through the <a href="/admissions/?visit=1#enquire">enquiry form</a> or call the office. <span class="placeholder">[OFFICE HOURS]</span>'],
    ['Is school transport available?', '<span class="placeholder">[TRANSPORT DETAILS TO CONFIRM]</span>'],
];
?>
<div class="faq__list">
<?php foreach ($faqs as $i => [$q, $a]): ?>
  <details class="faq__item"<?= $i === 0 ? ' open' : '' ?>>
    <summary><span class="faq__q" aria-hidden="true">Q.</span><span class="faq__text"><?= e($q) ?></span><span class="faq__sign" aria-hidden="true"></span></summary>
    <div class="faq__a"><span class="faq__q" aria-hidden="true">A.</span><p><?= $a ?></p></div>
  </details>
<?php endforeach; ?>
</div>
