import { h } from 'preact'

import { Wrapper } from './Wrapper'

export function FileItem({ label, uri }) {
  return (
    <Wrapper>
      <div className='w-full overflow-hidden'>
        <h1>
          <a className='inline-flex items-start max-w-full' href={uri}>
            <span>{label}</span>
          </a>
        </h1>
      </div>
    </Wrapper>
  )
}
