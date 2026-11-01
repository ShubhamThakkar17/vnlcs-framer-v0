import {defineArrayMember, defineField, defineType} from 'sanity'

const YT = /^https:\/\/(www\.)?(youtube\.com\/(watch\?v=|shorts\/|live\/)|youtu\.be\/)[A-Za-z0-9_-]{11}/

export default defineType({
  name: 'album',
  title: 'Photo album',
  type: 'document',
  fields: [
    defineField({name: 'name', title: 'Event name', type: 'string', validation: (Rule) => Rule.required().max(80)}),
    defineField({
      name: 'slug',
      title: 'Web address',
      type: 'slug',
      options: {source: 'name', maxLength: 80},
      validation: (Rule) => Rule.required(),
    }),
    defineField({name: 'date', title: 'Event date', type: 'date', validation: (Rule) => Rule.required()}),
    defineField({name: 'cover', title: 'Cover photo', type: 'image', options: {hotspot: true}}),
    defineField({
      name: 'photos',
      title: 'Photos (up to 40)',
      description: 'Please do not write children\'s full names in captions.',
      type: 'array',
      options: {layout: 'grid'},
      of: [
        defineArrayMember({
          type: 'image',
          options: {hotspot: true},
          fields: [
            defineField({
              name: 'alt',
              title: 'What is in the photo?',
              type: 'string',
              description: 'Short description for visually impaired visitors, e.g. "Class 3 students at the science fair".',
            }),
          ],
        }),
      ],
      validation: (Rule) => Rule.max(40),
    }),
    defineField({
      name: 'youtubeUrls',
      title: 'YouTube videos (optional)',
      type: 'array',
      of: [defineArrayMember({type: 'url', validation: (Rule) => Rule.custom((v) => !v || YT.test(v) || 'Paste a YouTube link')})],
    }),
  ],
  orderings: [{title: 'Newest first', name: 'dateDesc', by: [{field: 'date', direction: 'desc'}]}],
  preview: {select: {title: 'name', subtitle: 'date', media: 'cover'}},
})
