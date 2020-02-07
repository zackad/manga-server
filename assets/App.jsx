import { h } from 'preact'
import { useState } from 'preact/hooks'
import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'

function App(props) {
  const [mode, setMode] = useState('list')

  const list = <Listing files={props.files} directories={props.directories} />
  const reader = <Reader images={props.files} />

  return (
    <div>
      <Breadcrumbs />
      {mode === 'list' ? list : reader}
    </div>
  )
}

export { App }
