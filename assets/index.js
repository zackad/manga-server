import { h, render } from 'preact'

function App(props) {
  return <div>Manga Server App</div>
}

const directories = ENTRIES_DATA.filter(entry => entry.isDirectory)
const files = ENTRIES_DATA.filter(entry => !entry.isDirectory)

render(<App directories={directories} files={files} />, document.getElementById('preact_container'))
