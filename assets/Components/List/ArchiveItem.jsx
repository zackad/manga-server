import { h } from 'preact'

import { ArchiveWrapper } from './ArchiveWrapper'
import { Thumbnail } from '../Thumbnail'
import { ListLabel } from './ListLabel'

export function ArchiveItem({ cover, label, uri }) {
  return (
    <ArchiveWrapper>
      <div className='w-1/2 overflow-hidden'>{cover && <Thumbnail image={cover} />}</div>
      <div className='w-1/2 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </ArchiveWrapper>
  )
}
