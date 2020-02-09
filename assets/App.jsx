import { h } from 'preact'
import { useEffect, useState } from 'preact/hooks'
import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'
import './css/tailwind.src.css'

function App(props) {
  const [readerMode, setReaderMode] = useState(false)

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
          setReaderMode(true)
        }
        break
      // ';' (Semicolon)
      case 59:
        break
      // '\' key
      case 220:
        setReaderMode(false)
        break
    }
  }

  const toggleReaderMode = () => {
    if (images.length > 0) {
      setReaderMode(prevState => !prevState)
    }
  }

  const list = <Listing files={props.files} directories={props.directories} />
  const reader = <Reader images={images} />
  const toggleReaderButton = (
    <button className='ml-2 uppercase' onClick={toggleReaderMode}>
      read
    </button>
  )

  return (
    <div className='min-h-screen bg-gray-900 text-white'>
      <Breadcrumbs toggleReader={toggleReaderButton} />
      {readerMode ? reader : list}
    </div>
  )
}

export { App }
