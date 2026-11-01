import {defineField, defineType} from 'sanity'

export default defineType({
  name: 'siteSettings',
  title: 'Site settings',
  type: 'document',
  fields: [
    defineField({
      name: 'sessionYear',
      title: 'Admission session',
      type: 'string',
      description: 'e.g. 2027-28. Used in "Admissions open for …".',
      validation: (Rule) => Rule.max(20),
    }),
    defineField({
      name: 'admissionBannerText',
      title: 'Admission banner text (optional)',
      type: 'string',
      description: 'Replaces "Admissions open for …" at the top of the home page.',
      validation: (Rule) => Rule.max(80),
    }),
    defineField({
      name: 'tickerEnabled',
      title: 'Show the notice ticker on the home page',
      type: 'boolean',
      initialValue: true,
    }),
    defineField({
      name: 'emergencyBanner',
      title: 'Emergency banner (optional)',
      type: 'string',
      description: 'Shown on every page in red, e.g. "School closed tomorrow due to heavy rain." Clear it when no longer needed.',
      validation: (Rule) => Rule.max(160),
    }),
  ],
  preview: {prepare: () => ({title: 'Site settings'})},
})
