import { h } from 'preact'

import { IconDirectory } from './IconDirectory'
import { Thumbnail } from './Thumbnail'

export function ListItem({ className, item }) {
  return (
    <div className='w-full md:w-1/2 lg:w-1/3 px-2 pt-2 mt-2'>
      <div className='flex border-b border-gray-700'>
        <div className='w-1/4 h-32 overflow-hidden'>
          {item.isDirectory && <IconDirectory />}
          {item.cover && <Thumbnail image={item.cover} />}
        </div>
        <div className='w-3/4 h-32 overflow-hidden'>
          <h1 className='border-b border-gray-600'>
            <a className={`${className} inline-flex items-start max-w-full`} href={item.uri}>
              <span>{item.label}</span>
            </a>
          </h1>
        </div>
      </div>
    </div>
  )
}
