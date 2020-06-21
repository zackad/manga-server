import { h } from 'preact'
import { useContext } from 'preact/hooks'

import { ButtonIcon } from './Button/ButtonIcon'
import { IconBookOpen } from './Icon/IconBookOpen'
import { IconCog } from './Icon/IconCog'
import { IconHome } from './Icon/IconHome'
import { SearchField } from './Search/SearchField'
import { AppContext } from '../App'

export function AppBar() {
  const { toggleSettingDialog, toggleReaderMode } = useContext(AppContext)

  return (
    <div className='bg-gray-800 border-b'>
      <div className='container mx-auto flex py-1'>
        <a href='/'>
          <ButtonIcon>
            <IconHome />
          </ButtonIcon>
        </a>
        <SearchField />
        <div className='flex-grow'></div>
        <ButtonIcon onClick={toggleSettingDialog}>
          <IconCog />
        </ButtonIcon>
        <ButtonIcon onClick={toggleReaderMode}>
          <IconBookOpen />
        </ButtonIcon>
      </div>
    </div>
  )
}
