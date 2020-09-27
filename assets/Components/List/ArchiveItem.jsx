import { h } from 'preact'

import { ArchiveWrapper } from './ArchiveWrapper'
import { Thumbnail } from '../Thumbnail'
import { ListLabel } from './ListLabel'

export function ArchiveItem({ cover, label, uri }) {
  return (
    <ArchiveWrapper>
      <div className='w-1/2 md:w-full md:h-80 overflow-hidden'>{cover && <Thumbnail image={cover} uri={uri} />}</div>
      <div className='w-1/2 md:w-full md:h-12 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </ArchiveWrapper>
  )
}
