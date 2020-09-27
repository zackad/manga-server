import { h } from 'preact'

declare const ERROR_MESSAGE: string

export function ErrorMessage() {
  if (typeof ERROR_MESSAGE === 'undefined' || ERROR_MESSAGE === '') {
    return null
  }

  return (
    <h1 className='mt-2 text-center text-gray-600 py-12 border-2 border-dashed border-gray-700'>
      <span className='font-semibold text-gray-500'>{ERROR_MESSAGE}</span>
    </h1>
  )
}
