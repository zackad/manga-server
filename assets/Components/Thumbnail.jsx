import React, { useEffect } from 'react'
import lozad from 'lozad'

export function Thumbnail({ image }) {
  const observer = lozad()

  useEffect(() => {
    observer.observe()
  }, [])

  return (
    <div className='p-1'>
      <img data-src={image} alt='Cover Thumbnail' className='w-full lozad' />
    </div>
  )
}
