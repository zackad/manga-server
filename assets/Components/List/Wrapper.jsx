import { h } from 'preact'

export function Wrapper({ children }) {
  return (
    <div className='w-full md:w-1/2 lg:w-1/3 px-2 pt-2 mt-2'>
      <div className='flex border-b border-gray-600'>{children}</div>
    </div>
  )
}
