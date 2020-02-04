import { h } from 'preact'
import { ListItem } from './ListItem.jsx'

function Listing({ directories, files }) {
  return (
    <div>
      <div className='container mx-auto pt-12'>
        {directories.map(item => (
          <ListItem className='dir' item={item} key={item.uri} />
        ))}
        {files.map(item => (
          <ListItem item={item} key={item.uri} />
        ))}
      </div>
    </div>
  )
}

export { Listing }
