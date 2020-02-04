import { h, render } from 'preact'
import { Listing } from './Components/Listing'

function App(props) {
  return <Listing files={props.files} directories={props.directories} />
}

const directories = ENTRIES_DATA.filter(entry => entry.isDirectory)
const files = ENTRIES_DATA.filter(entry => !entry.isDirectory)

render(<App directories={directories} files={files} />, document.getElementById('preact_container'))
