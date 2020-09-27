import React from 'preact/compat'
import { h, render } from 'preact'

import { App } from './App'

declare const ENTRIES_DATA: any

const directories = ENTRIES_DATA.filter((entry) => entry.type === 'directory')
const archive = ENTRIES_DATA.filter((entry) => entry.type === 'archive')
const files = ENTRIES_DATA.filter((entry) => entry.type === 'file')

render(<App directories={directories} files={files} archive={archive} />, document.getElementById('preact_container'))
