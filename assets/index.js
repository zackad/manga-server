import React from 'react'
import { render } from 'react-dom'
import { App } from './App'

const directories = ENTRIES_DATA.filter((entry) => entry.type === 'directory')
const archive = ENTRIES_DATA.filter((entry) => entry.type === 'archive')
const files = ENTRIES_DATA.filter((entry) => entry.type === 'file')

render(<App directories={directories} files={files} archive={archive} />, document.getElementById('preact_container'))
