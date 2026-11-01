import {defineConfig} from 'sanity'
import {structureTool} from 'sanity/structure'
import {visionTool} from '@sanity/vision'
import {schemaTypes} from './schemaTypes'
import {structure} from './structure'

const projectId = process.env.SANITY_STUDIO_PROJECT_ID || 'REPLACE_WITH_PROJECT_ID'
const dataset = process.env.SANITY_STUDIO_DATASET || 'production'

export default defineConfig({
  name: 'default',
  title: 'Veer New Look · Notice Board',
  projectId,
  dataset,
  plugins: [structureTool({structure}), visionTool()],
  schema: {
    types: schemaTypes,
    // Site settings is a single document: hide it from "Create new"
    templates: (templates) => templates.filter(({schemaType}) => schemaType !== 'siteSettings'),
  },
  document: {
    actions: (actions, {schemaType}) =>
      schemaType === 'siteSettings'
        ? actions.filter(({action}) => ['publish', 'discardChanges', 'restore'].includes(action))
        : actions,
  },
})
