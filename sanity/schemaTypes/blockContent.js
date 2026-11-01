import {defineArrayMember, defineType} from 'sanity'

// Rich text for notice and newsletter bodies: paragraphs, headings, lists, bold, italic, links.
export default defineType({
  name: 'blockContent',
  title: 'Text',
  type: 'array',
  of: [
    defineArrayMember({
      type: 'block',
      styles: [
        {title: 'Paragraph', value: 'normal'},
        {title: 'Heading', value: 'h3'},
        {title: 'Small heading', value: 'h4'},
        {title: 'Quote', value: 'blockquote'},
      ],
      lists: [
        {title: 'Bullets', value: 'bullet'},
        {title: 'Numbered', value: 'number'},
      ],
      marks: {
        decorators: [
          {title: 'Bold', value: 'strong'},
          {title: 'Italic', value: 'em'},
          {title: 'Underline', value: 'underline'},
        ],
        annotations: [
          {
            name: 'link',
            type: 'object',
            title: 'Link',
            fields: [
              {
                name: 'href',
                type: 'url',
                title: 'Web address',
                validation: (Rule) => Rule.uri({scheme: ['http', 'https', 'mailto', 'tel']}),
              },
            ],
          },
        ],
      },
    }),
  ],
})
