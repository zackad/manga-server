import { h } from 'preact'

import { Wrapper } from './Wrapper'
import { Thumbnail } from '../Thumbnail'
import { ListLabel } from './ListLabel'

export function ArchiveItem({ cover, label, uri }) {
  return (
    <Wrapper>
      <div className='w-1/2 overflow-hidden'>{cover && <Thumbnail image={cover} />}</div>
      <div className='w-1/2 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </Wrapper>
  )
}
