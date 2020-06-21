import { h } from 'preact'
import { useEffect, useState } from 'preact/hooks'

import { Breadcrumbs } from './Components/Breadcrumbs'
import { Listing } from './Components/Listing'
import { Reader } from './Components/Reader'
import { SettingsDialog } from './Components/SettingsDialog'
import { IconBookOpen } from './Components/Icon/IconBookOpen'
import { IconCog } from './Components/Icon/IconCog'
import './css/tailwind.src.css'
import { ButtonIcon } from './Components/Button/ButtonIcon'

function App(props) {
  const [readerMode, setReaderMode] = useState(false)
  const [maxImageWidth, setMaxImageWidth] = useState(null)
  const [openSettingDialog, setOpenSettingDialog] = useState(false)

  const regexFilter = new RegExp('.jpe?g$|.png$|.gif$|.webp$', 'i')
  const images = props.files.filter(image => image.uri.match(regexFilter))

  useEffect(() => {
    document.addEventListener('keydown', handleKeydown)
    setMaxImageWidth(localStorage.getItem('maxImageWidth') || maxImageWidth)
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

  const handleMaxImageWidthChange = event => {
    const value = parseInt(event.target.value)
    setMaxImageWidth(value)
    localStorage.setItem('maxImageWidth', value)
  }

  const toggleReaderMode = () => {
    if (images.length > 0) {
      setReaderMode(prevState => !prevState)
    }
  }

  const toggleSettingDialog = () => {
    setOpenSettingDialog(prevState => !prevState)
  }

  const list = <Listing files={props.files} directories={props.directories} archive={props.archive} />
  const reader = <Reader images={images} maxImageWidth={maxImageWidth} />
  const toggleReaderButton = (
    <ButtonIcon onClick={toggleReaderMode}>
      <IconBookOpen />
    </ButtonIcon>
  )

  const toggleSettingButton = (
    <ButtonIcon onClick={toggleSettingDialog}>
      <IconCog />
    </ButtonIcon>
  )

  return (
    <div className='min-h-screen bg-gray-900 text-white'>
      <Breadcrumbs toggleReader={toggleReaderButton} toggleSetting={toggleSettingButton} />
      {openSettingDialog && (
        <SettingsDialog
          value={maxImageWidth}
          onChange={handleMaxImageWidthChange}
          onClick={() => setOpenSettingDialog(false)}
        />
      )}
      {readerMode ? reader : list}
    </div>
  )
}

export { App }
