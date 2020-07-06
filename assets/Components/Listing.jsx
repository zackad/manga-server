import React from 'react'

import { ListItem } from './ListItem.jsx'
import { Breadcrumbs } from './Breadcrumbs'
import { ErrorMessage } from './Message/ErrorMessage'

function Listing({ directories, files, archive }) {
  return (
    <div>
      <div className='container mx-auto'>
        <Breadcrumbs />
        <ErrorMessage />
        <div className='flex flex-wrap'>
          {directories.map((item) => (
            <ListItem className='dir' item={item} key={item.uri} />
          ))}
          {archive.map((item) => (
            <ListItem item={item} key={item.uri} />
          ))}
          {files.map((item) => (
            <ListItem item={item} key={item.uri} />
          ))}
        </div>
      </div>
    </div>
  )
}

export { Listing }
