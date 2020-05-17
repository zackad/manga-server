import { h } from 'preact'
import lozad from 'lozad'
import { useEffect } from 'preact/hooks'

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
