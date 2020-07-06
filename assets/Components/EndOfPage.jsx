function EndOfPage() {
  const segmentName = decodeURIComponent(location.pathname).split('/').pop()
  return (
    <div>
      <h1 className='text-center text-gray-600 py-2 border-t-2 border-dashed border-gray-700'>
        End Of: <span className='font-semibold text-gray-500'>{segmentName}</span>
      </h1>
    </div>
  )
}

export { EndOfPage }
