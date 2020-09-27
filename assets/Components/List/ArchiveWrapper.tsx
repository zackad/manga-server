import { h } from 'preact'

export function ArchiveWrapper({ children }) {
  return (
    <div className='w-full md:w-1/3 lg:w-1/4 xl:w-1/6 px-2 pt-2 mt-2'>
      <div className='flex flex-wrap border-b border-gray-600'>{children}</div>
    </div>
  )
}
