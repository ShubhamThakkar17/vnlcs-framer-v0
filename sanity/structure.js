// Desk layout: Site settings (single document) first, then the content lists.
export const structure = (S) =>
  S.list()
    .title('Veer New Look')
    .items([
      S.listItem()
        .title('Site settings')
        .id('siteSettings')
        .child(S.document().schemaType('siteSettings').documentId('siteSettings').title('Site settings')),
      S.divider(),
      S.documentTypeListItem('notice').title('Notices and circulars'),
      S.documentTypeListItem('newsletter').title('Newsletters'),
      S.documentTypeListItem('album').title('Photo albums'),
    ])
