import { h, render } from 'preact'

import { App } from './App'

type ENTRY = {
  uri: string
  label: string
  type: 'directory' | 'archive' | 'file'
  cover?: string
}

declare const ENTRIES_DATA: ENTRY[]

const directories = ENTRIES_DATA.filter((entry) => entry.type === 'directory')
const archive = ENTRIES_DATA.filter((entry) => entry.type === 'archive')
const files = ENTRIES_DATA.filter((entry) => entry.type === 'file')

render(<App directories={directories} files={files} archive={archive} />, document.getElementById('preact_container'))
