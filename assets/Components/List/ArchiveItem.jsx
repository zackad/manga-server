import { h } from 'preact'

import { Wrapper } from './Wrapper'
import { Thumbnail } from '../Thumbnail'

export function ArchiveItem({ cover, label, uri }) {
  return (
    <Wrapper>
      <div className='w-1/4 h-32 overflow-hidden'>{cover && <Thumbnail image={cover} />}</div>
      <div className={`w-3/4 h-32 overflow-hidden`}>
        <h1 className='border-b border-gray-600'>
          <a className={`inline-flex items-start max-w-full`} href={uri}>
            <span>{label}</span>
          </a>
        </h1>
      </div>
    </Wrapper>
  )
}
