import { h } from 'preact'

export function SeacrhField() {
  return (
    <span className='mr-2'>
      <form action='/search' className='flex'>
        <input type='text' name='q' className='px-1 h-8 text-sm text-gray-900' />
      </form>
    </span>
  )
}
