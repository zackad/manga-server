import { h, render } from 'preact'
import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'

function App(props) {
  return (
    <div>
      <Breadcrumbs />
      <Listing files={props.files} directories={props.directories} />
      <Reader images={files} />
    </div>
  )
}

const directories = ENTRIES_DATA.filter(entry => entry.isDirectory)
const files = ENTRIES_DATA.filter(entry => !entry.isDirectory)

render(<App directories={directories} files={files} />, document.getElementById('preact_container'))
