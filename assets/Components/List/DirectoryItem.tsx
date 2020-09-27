import { h } from 'preact'

import { IconDirectory } from 'App/Components/Icon/IconDirectory'
import { ListLabel } from 'App/Components/List/ListLabel'
import { Wrapper } from 'App/Components/List/Wrapper'

export function DirectoryItem({ label, uri }) {
  return (
    <Wrapper>
      <div className='w-12 h-6 overflow-hidden'>
        <IconDirectory />
      </div>
      <div className='w-full h-6 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </Wrapper>
  )
}
