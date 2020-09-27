import { h, render } from 'preact'

import { App } from 'App/App'
import { EntryData } from 'App/types/EntryData.ts'

declare const ENTRIES_DATA: EntryData[]

const directories = ENTRIES_DATA.filter((entry) => entry.type === 'directory')
const archive = ENTRIES_DATA.filter((entry) => entry.type === 'archive')
const files = ENTRIES_DATA.filter((entry) => entry.type === 'file')

render(<App directories={directories} files={files} archive={archive} />, document.getElementById('preact_container'))
