import { h } from 'preact'

function EndOfPage() {
  const segmentName = decodeURIComponent(location.pathname).split('/').pop()
  return (
    <div class='text-center text-gray-600 py-2 border-t-2 border-dashed border-gray-700'>
      <span>End Of: </span>
      <span className='font-semibold text-gray-500'>{segmentName}</span>
      <span class='mx-1'>/</span>
      <a href={location.pathname + '?next'} className='font-semibold text-gray-500'>
        Next Chapter
      </a>
    </div>
  )
}

export { EndOfPage }
