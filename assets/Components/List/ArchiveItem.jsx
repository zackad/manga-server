import React from 'react'

import { Wrapper } from './Wrapper'
import { Thumbnail } from '../Thumbnail'
import { ListLabel } from './ListLabel'

export function ArchiveItem({ cover, label, uri }) {
  return (
    <Wrapper>
      <div className='w-1/4 max-h-32 lg:max-h-48 overflow-hidden'>{cover && <Thumbnail image={cover} />}</div>
      <div className='w-3/4 max-h-32 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </Wrapper>
  )
}
