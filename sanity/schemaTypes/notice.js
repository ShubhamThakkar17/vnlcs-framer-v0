import {defineField, defineType} from 'sanity'

export const NOTICE_CATEGORIES = [
  {title: 'CBSE Circular', value: 'cbse'},
  {title: 'School Notice', value: 'school'},
  {title: 'Event', value: 'event'},
  {title: 'Result', value: 'result'},
  {title: 'Holiday', value: 'holiday'},
]

const today = () => new Date().toISOString().slice(0, 10)

export default defineType({
  name: 'notice',
  title: 'Notice',
  type: 'document',
  fields: [
    defineField({
      name: 'title',
      title: 'Title',
      type: 'string',
      description: 'Short and clear, e.g. "Half-yearly exam timetable, Classes 6 to 10".',
      validation: (Rule) => Rule.required().max(120),
    }),
    defineField({
      name: 'slug',
      title: 'Web address',
      type: 'slug',
      description: 'Click "Generate". This becomes veernewlookschool.com/notices/…',
      options: {source: 'title', maxLength: 80},
      validation: (Rule) => Rule.required(),
    }),
    defineField({
      name: 'category',
      title: 'Category',
      type: 'string',
      options: {list: NOTICE_CATEGORIES, layout: 'radio'},
      initialValue: 'school',
      validation: (Rule) => Rule.required(),
    }),
    defineField({
      name: 'date',
      title: 'Date',
      type: 'date',
      options: {dateFormat: 'DD MMM YYYY'},
      initialValue: today,
      validation: (Rule) => Rule.required(),
    }),
    defineField({
      name: 'summary',
      title: 'Summary',
      type: 'text',
      rows: 3,
      description: 'One or two sentences shown on the notice board (200 characters at most).',
      validation: (Rule) => Rule.max(200),
    }),
    defineField({
      name: 'body',
      title: 'Full notice (optional)',
      type: 'blockContent',
    }),
    defineField({
      name: 'attachment',
      title: 'PDF attachment (optional)',
      type: 'file',
      options: {accept: 'application/pdf'},
    }),
    defineField({
      name: 'externalLink',
      title: 'Official link (optional)',
      type: 'url',
      description: 'For CBSE circulars, paste the link from cbse.gov.in or cbseacademic.nic.in.',
      validation: (Rule) => Rule.uri({scheme: ['http', 'https']}),
    }),
    defineField({
      name: 'circularNo',
      title: 'Circular number (optional)',
      type: 'string',
      description: 'As printed on the circular, e.g. "Acad-67/2026".',
    }),
    defineField({
      name: 'issuedBy',
      title: 'Issued by (optional)',
      type: 'string',
      description: 'e.g. "CBSE, Controller of Examinations".',
    }),
    defineField({
      name: 'forClasses',
      title: 'For which classes (optional)',
      type: 'string',
      description: 'e.g. "Class X, Class XII". Shown on the notice.',
    }),
    defineField({
      name: 'pinned',
      title: 'Pin to the top of the board',
      type: 'boolean',
      initialValue: false,
    }),
    defineField({
      name: 'expiryDate',
      title: 'Take off the board on (optional)',
      type: 'date',
      options: {dateFormat: 'DD MMM YYYY'},
      description: 'After this date the notice moves to the archive. It is never deleted.',
      validation: (Rule) =>
        Rule.custom((value, ctx) =>
          value && ctx.document?.date && value < ctx.document.date ? 'Must be on or after the notice date' : true,
        ),
    }),
  ],
  orderings: [{title: 'Newest first', name: 'dateDesc', by: [{field: 'date', direction: 'desc'}]}],
  preview: {
    select: {title: 'title', category: 'category', date: 'date', pinned: 'pinned'},
    prepare({title, category, date, pinned}) {
      const cat = NOTICE_CATEGORIES.find((c) => c.value === category)?.title || ''
      return {title: (pinned ? '📌 ' : '') + title, subtitle: [cat, date].filter(Boolean).join(' · ')}
    },
  },
})
