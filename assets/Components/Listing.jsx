import { h } from 'preact'
import { ListItem } from './ListItem.jsx'
import { Breadcrumbs } from './Breadcrumbs'

function Listing({ directories, files, archive }) {
  return (
    <div>
      <div className='container mx-auto'>
        <Breadcrumbs />
        <div className='flex flex-wrap'>
          {directories.map(item => (
            <ListItem className='dir' item={item} key={item.uri} />
          ))}
          {archive.map(item => (
            <ListItem item={item} key={item.uri} />
          ))}
          {files.map(item => (
            <ListItem item={item} key={item.uri} />
          ))}
        </div>
      </div>
    </div>
  )
}

export { Listing }
