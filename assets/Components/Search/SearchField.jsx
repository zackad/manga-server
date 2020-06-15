import { h } from 'preact'

export function SeacrhField() {
  return (
    <form action='/search' className='w-full py-1 mt-2 border-t'>
      <label htmlFor=''>Search</label>
      <input type='text' name='q' className=' w-full px-1 h-8 text-sm text-gray-900' placeholder='Search' />
    </form>
  )
}
