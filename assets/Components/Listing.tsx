import { h } from 'preact'

import { ListItem } from './ListItem'
import { Breadcrumbs } from './Breadcrumbs'
import { ErrorMessage } from './Message/ErrorMessage'

function Listing({ directories, files, archive }) {
  return (
    <div>
      <div className='container mx-auto'>
        <Breadcrumbs />
        <ErrorMessage />
        <div className='flex flex-wrap'>
          <div className='flex flex-wrap w-full'>
            {directories.map((item) => (
              <ListItem item={item} key={item.uri} />
            ))}
            {files.map((item) => (
              <ListItem item={item} key={item.uri} />
            ))}
          </div>
          {archive.map((item) => (
            <ListItem item={item} key={item.uri} />
          ))}
        </div>
      </div>
    </div>
  )
}

export { Listing }
