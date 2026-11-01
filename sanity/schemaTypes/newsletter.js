import {defineField, defineType} from 'sanity'

export default defineType({
  name: 'newsletter',
  title: 'Newsletter',
  type: 'document',
  fields: [
    defineField({name: 'title', title: 'Title', type: 'string', validation: (Rule) => Rule.required().max(120)}),
    defineField({
      name: 'slug',
      title: 'Web address',
      type: 'slug',
      description: 'Click "Generate".',
      options: {source: 'title', maxLength: 80},
      validation: (Rule) => Rule.required(),
    }),
    defineField({
      name: 'month',
      title: 'Month',
      type: 'date',
      description: 'Pick any day in the newsletter month.',
      options: {dateFormat: 'MMMM YYYY'},
      validation: (Rule) => Rule.required(),
    }),
    defineField({name: 'cover', title: 'Cover image', type: 'image', options: {hotspot: true}}),
    defineField({name: 'intro', title: 'Intro', type: 'text', rows: 3, validation: (Rule) => Rule.max(300)}),
    defineField({name: 'pdf', title: 'Newsletter PDF', type: 'file', options: {accept: 'application/pdf'}}),
    defineField({name: 'body', title: 'Text (optional)', type: 'blockContent'}),
  ],
  orderings: [{title: 'Newest first', name: 'monthDesc', by: [{field: 'month', direction: 'desc'}]}],
  preview: {select: {title: 'title', subtitle: 'month', media: 'cover'}},
})
