import { h, render } from 'preact'
import { App } from './App'

const directories = ENTRIES_DATA.filter(entry => entry.isDirectory)
const files = ENTRIES_DATA.filter(entry => !entry.isDirectory)

render(<App directories={directories} files={files} />, document.getElementById('preact_container'))
