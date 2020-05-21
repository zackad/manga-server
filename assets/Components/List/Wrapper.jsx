import { h } from 'preact'

export function Wrapper({ children }) {
  return (
    <div className='w-full md:w-1/2 lg:w-1/3 px-2 pt-2 mt-2'>
      <div className='flex'>{children}</div>
    </div>
  )
}
