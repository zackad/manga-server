import { h } from 'preact'

import { ArchiveWrapper } from 'App/Components/List/ArchiveWrapper'
import { ListLabel } from 'App/Components/List/ListLabel'
import { Thumbnail } from 'App/Components/Thumbnail'

type ArchiveItemProps = {
  uri: string
  cover?: string
  label?: string
}

export function ArchiveItem({ cover, label, uri }: ArchiveItemProps) {
  return (
    <ArchiveWrapper>
      <div className='w-1/2 md:w-full md:h-80 overflow-hidden'>{cover && <Thumbnail image={cover} uri={uri} />}</div>
      <div className='w-1/2 md:w-full md:h-12 overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </ArchiveWrapper>
  )
}
