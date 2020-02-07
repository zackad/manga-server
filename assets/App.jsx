import { h } from 'preact'
import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'

function App(props) {
  return (
    <div>
      <Breadcrumbs />
      <Listing files={props.files} directories={props.directories} />
      <Reader images={props.files} />
    </div>
  )
}

export { App }
