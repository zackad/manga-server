import { h } from 'preact'

import { ListLabel } from 'App/Components/List/ListLabel'
import { Wrapper } from 'App/Components/List/Wrapper'

export function FileItem({ label, uri }) {
  return (
    <Wrapper>
      <div className='w-full overflow-hidden'>
        <ListLabel uri={uri} label={label} />
      </div>
    </Wrapper>
  )
}
