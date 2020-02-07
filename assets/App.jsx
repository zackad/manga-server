import { h } from 'preact'
import { useEffect, useState } from 'preact/hooks'
import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'
import './css/tailwind.css'

function App(props) {
  const [mode, setMode] = useState('list')

  const regexFilter = new RegExp('.jpe?g$|.png$|.gif$|.webp$', 'i')
  const images = props.files.filter(image => image.uri.match(regexFilter))

  useEffect(() => {
    document.addEventListener('keydown', handleKeydown)
  }, [])

  const handleKeydown = event => {
    const { keyCode } = event

    switch (keyCode) {
      // 'Enter/Return' key
      case 13:
        if (images.length > 0) {
          setMode('reader')
        }
        break
      // ';' (Semicolon)
      case 59:
        break
      // '\' key
      case 220:
        setMode('list')
        break
    }
  }

  const list = <Listing files={props.files} directories={props.directories} />
  const reader = <Reader images={images} />

  return (
    <div>
      <Breadcrumbs />
      {mode === 'list' ? list : reader}
    </div>
  )
}

export { App }
