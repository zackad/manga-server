import { h } from 'preact'
import { useContext } from 'preact/hooks'

import { ButtonIcon } from 'App/Components/Button/ButtonIcon'
import { IconBookOpen } from 'App/Components/Icon/IconBookOpen'
import { IconCog } from 'App/Components/Icon/IconCog'
import { IconHome } from 'App/Components/Icon/IconHome'
import { SearchField } from 'App/Components/Search/SearchField'
import { AppContext } from 'App/AppContext'

export function AppBar() {
  const { toggleSettingDialog, toggleReaderMode } = useContext(AppContext)

  return (
    <div className='bg-gray-800 border-b'>
      <div className='container mx-auto flex py-1'>
        <a href='/'>
          <ButtonIcon margin='mx-2'>
            <IconHome />
          </ButtonIcon>
        </a>
        <SearchField />
        <div className='flex-grow'></div>
        <ButtonIcon onClick={toggleSettingDialog} margin='mx-2'>
          <IconCog />
        </ButtonIcon>
        <ButtonIcon onClick={toggleReaderMode} margin='mr-2'>
          <IconBookOpen />
        </ButtonIcon>
      </div>
    </div>
  )
}
