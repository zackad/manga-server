import { h } from 'preact'

import { Wrapper } from './Wrapper'
import { IconDirectory } from '../IconDirectory'
import { ListLabel } from './ListLabel'

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
