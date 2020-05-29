import { h } from 'preact'

import { Wrapper } from './Wrapper'
import { ListLabel } from './ListLabel'

export function FileItem({ label, uri }) {
  return (
    <Wrapper>
      <div className='w-full overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </Wrapper>
  )
}
