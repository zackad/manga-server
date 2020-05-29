import { h } from 'preact'

import { Wrapper } from './Wrapper'
import { IconDirectory } from '../IconDirectory'

export function DirectoryItem({ label, uri }) {
  return (
    <Wrapper>
      <div className='w-12 h-6 overflow-hidden'>
        <IconDirectory />
      </div>
      <div className='w-full h-6 overflow-hidden'>
        <h1>
          <a className='inline-flex items-start w-full' href={uri}>
            <span>{label}</span>
          </a>
        </h1>
      </div>
    </Wrapper>
  )
}
