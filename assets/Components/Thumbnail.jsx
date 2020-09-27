import { h } from 'preact'
import lozad from 'lozad'
import { useEffect } from 'preact/hooks'

export function Thumbnail({ image, uri }) {
  const observer = lozad()

  useEffect(() => {
    observer.observe()
  }, [])

  return (
    <div className='p-1'>
      <a href={uri}>
        <img data-src={image} alt='Cover Thumbnail' className='w-full lozad' />
      </a>
    </div>
  )
}
