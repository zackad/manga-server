import { h } from 'preact'
import { useContext } from 'preact/hooks'

import { ButtonIcon } from './Button/ButtonIcon'
import { IconCog } from './Icon/IconCog'
import { IconBookOpen } from './Icon/IconBookOpen'
import { AppContext } from '../App'

export function AppBar() {
  const { toggleSettingDialog, toggleReaderMode } = useContext(AppContext)

  return (
    <div className='flex py-1 bg-gray-800 border-b'>
      <div className='flex-grow'></div>
      <ButtonIcon onClick={toggleSettingDialog}>
        <IconCog />
      </ButtonIcon>
      <ButtonIcon onClick={toggleReaderMode}>
        <IconBookOpen />
      </ButtonIcon>
    </div>
  )
}
