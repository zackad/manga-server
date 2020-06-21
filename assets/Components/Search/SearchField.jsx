import { h } from 'preact'

export function SearchField() {
  return (
    <form action='/search' className='flex-grow max-w-md'>
      <input
        type='text'
        name='q'
        className='w-full px-1 h-8 bg-gray-900 focus:bg-white text-sm text-gray-900 border border-gray-700 rounded'
        placeholder='Search'
      />
    </form>
  )
}
