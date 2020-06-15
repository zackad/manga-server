import { h } from 'preact'

export function SeacrhField() {
  return (
    <span className='mr-2'>
      <form action='/search' className='flex'>
        <input type='text' name='q' className='mr-2 px-1 h-8 text-sm text-gray-900' />
        <button type='submit' className='p-1 h-8 text-xs border'>
          Search
        </button>
      </form>
    </span>
  )
}
